<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH . 'models/entity.php';
include_once APPPATH . 'libraries/Dapi.php';

class Main extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

	function __construct() {
		parent::__construct();

		$this->load->Model('DBPedia');
		$this->load->model('Wikipedia');

		$this->load->helper('form');
		$this->load->helper('dev');

		$this->load->library('yaml');
	}

	public function index()
	{

		$data = $this->get_data();
		header('Content-Type: text/html; charset=utf-8');
		$this->load->view('entities', array('entities' => $data));
	}

	public function json() {
	
		$this->output->set_content_type("application/json");
		echo json_encode($this->get_data());
		
	}

	public function raw() {
		$data = $this->get_data();
		yell($data);
	}

	//////////////////////////////////////////////


	protected function get_data() {
		// step 1 : get dbpedia results
		$dbpedia_results = $this->get_dbpedia_results();


		// step 2 : get wiki counts for each 
		$entities = $this->get_wikipedia_data($dbpedia_results);

		// step 3 : normalize counts for each entity

		// step 4 : get dapi results for each entity
		$entities = $this->get_dapi_data($entities);

		// sort by rank
		$entities = $this->sort_by_rank($entities);

		return $entities;
	}

	protected function sort_by_rank($entities) {

		//first, remove all entities that don't have holdings
		$entities = array_filter($entities, function($entity) {
				return $entity->get_holdings() && count($entity->get_holdings());
			});

		foreach($entities as $entity) {
			$rank_map = $entity->get_rank_map();

			$weight_a = 1;
			$weight_b = 10;
			$weight_c = 1;
			$weight_d = 10;

			$rank = $rank_map['normalized_revision_count'] * $weight_a +
						$rank_map['normalized_article_length'] * $weight_b +
						$rank_map['normalized_num_external_links'] * $weight_c +
						$rank_map['normalized_dapi_count'] * $weight_d;

			$entity->set_rank($rank);
		}

		usort($entities, function($a, $b) {
				if ($a->get_rank() == $b->get_rank()) {
			    	return 0;
			    }
				return ($a->get_rank() < $b->get_rank()) ? 1 : -1;
			});

		return $entities;
	}

	protected function get_dbpedia_results() {
		$sparql = $this->yaml->parse_file("sparql.yml");

		$results = array();

		if ($sparql['queries']) {
			$count = 0;
			foreach($sparql['queries'] as $query_obj) {
				$this->DBPedia->initialize($query_obj);
				$query = $query_obj['query'];
				$query = $this->set_query_dates($query);
				$query_results = $this->DBPedia->get_results($query);
				$results = array_merge($results, $query_results);
				break;
			}
		}

		return $results;
	}

	protected function set_query_dates($query){
		
		$date_param = $this->input->get('date');

		if ($date_param) {
			$date_array = explode("-", $date_param);
			$today = $date_param;
			$month = $date_array[1];
			$day = $date_array[2];
		}else {
			$today = date('Y-m-d');
			$month = date('m');
			$day = date('d');
		}


		$query = str_replace('%TODAY%', $today, $query);
		$query = str_replace('%MONTH%', $month, $query);
		$query = str_replace('%DAY%', $day, $query);

		return $query;
	}

	protected function get_wikipedia_data($dbpedia_results){
		$entities = array();
		$revisions = array();
		$lengths = array();
		$links = array();

		foreach ($dbpedia_results as $entity){
			$uri = $entity->get_id();
			$title = str_replace('http://dbpedia.org/resource/', '', $uri);
			$this->Wikipedia->initialize($title);
			$wikipedia_results = array();
			$wikipedia_results['revision_count'] = $this->Wikipedia->get_revision_count();
			$wikipedia_results['article_length'] = $this->Wikipedia->get_article_length();
			$wikipedia_results['num_external_links'] = $this->Wikipedia->get_num_external_links();
		    $entity->set_rank_map($wikipedia_results);
			$entities[] = $entity;

			$revisions[] = $wikipedia_results['revision_count'];
			$lengths[] = $wikipedia_results['article_length'];
			$links[] = $wikipedia_results['num_external_links'];
    	}
    
	    $revisions_denom = max($revisions) - min($revisions);
	    $length_denom = max($lengths) - min($lengths);
	    $links_denom = max($links) - min($links);

	    $full_entities = array();
	    foreach ($entities as $e){
	    	$rank_map = $e->get_rank_map();
	    	$rank_map['normalized_revision_count'] = 0;
	    	$rank_map['normalized_article_length'] = 0;
	    	$rank_map['normalized_num_external_links'] = 0;

	    	if ($revisions_denom > 0){
	    		$rank_map['normalized_revision_count'] = 
	    			($rank_map['revision_count'] - min($revisions)) / $revisions_denom;
	    	}
	    	if ($revisions_denom > 0){
	    		$rank_map['normalized_article_length'] = 
	    			($rank_map['article_length'] - min($lengths)) / $length_denom;
	    	}
	    	if ($links_denom){
	    		$rank_map['normalized_num_external_links'] = 
	    			($rank_map['num_external_links'] - min($links)) / $links_denom;
	    	}
	    	$e->set_rank_map($rank_map);
	    	$full_entities[] = $e;
	    }

	    return $entities;

	}

	protected function get_dapi_data(&$entities) {

		$dapi = new Dapi();

		$result_count = array();


		foreach($entities as &$entity) {
			//yell($entity);

			$dapi_query_array = array();

			foreach($entity->get_dapi_map() as $param) {
				foreach($entity->get_terms() as $term) {
					array_push($dapi_query_array, "$param:$term");
				}
			}

			$dapi_query = implode(" OR ", $dapi_query_array);

			//yell($dapi_query);
			$dapi_response = $dapi->search($dapi_query);

			if ($dapi_response) {
				$dapi_results = $dapi_response->getSearchResults();
				// reduce to 5 elements
				$dapi_results = array_slice($dapi_results, 0, 5);

				$dapi_total_count = $dapi_response->getTotalResults();
				$result_count[] = $dapi_total_count;

				// add holdings
				foreach($dapi_results as $dapi_result) {
					if ($dapi_result->getName()) {
						$holding = array();
						$holding['name'] = $dapi_result->getName()->getValue();
						if ($dapi_result->getAuthor())
							$holding['author'] = $dapi_result->getAuthor()->getName()->getValue();
						else 
							$holding['author'] = "-";

						$entity->add_holding($holding);
					}
				}

				$rank_map = $entity->get_rank_map();
				$rank_map['dapi_count'] = $dapi_total_count;
				$entity->set_rank_map($rank_map);
			}

			//yell($entity);
		}

		// now, enter normalized count values into each entity
		$count_max = max($result_count);
		$count_min = min($result_count);

		foreach ($entities as &$entity) {
			$rank_map = $entity->get_rank_map();
			if ($count_max > $count_min && array_key_exists('dapi_count', $rank_map)) {
				$rank_map['normalized_dapi_count'] = 
					($rank_map['dapi_count'] - $count_min) / ($count_max - $count_min);
			}else {
				$rank_map['normalized_dapi_count'] = 0;
			}
			$entity->set_rank_map($rank_map);

			//yell($entity);
		}

		return $entities;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */