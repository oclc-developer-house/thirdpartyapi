<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH . 'models/entity.php';

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

		$this->load->helper('form');
		$this->load->helper('dev');

		$this->load->library('yaml');
	}

	public function index()
	{
		$this->load->view('main');
	}

	protected function get_dbpedia_results() {
		$sparql = $this->yaml->parse_file("sparql.yml");

		$results = array();

		if ($sparql['queries']) {
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
		$today = date('Y-m-d');
		$month = date('m');
		$day = date('d');

		$query = str_replace('%TODAY%', $today, $query);
		$query = str_replace('%MONTH%', $month, $query);
		$query = str_replace('%DAY%', $day, $query);

		return $query;
	}

	public function json() {
	
		//$this->output->set_content_type("application/json");
		// step 1 : get dbpedia results
		$dbpedia_results = $this->get_dbpedia_results();


		// step 2 : get wiki counts for each 

		// step 3 : normalize counts for each entity

		// step 4 : get dapi results for each entity
		$entities = $this->get_dapi_data($entities);

		//$dapi_results = $dapi->search("name:john+smith+or+name:smith+john");
		
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
    
	    $revisions_min = min($revisions);
	    $revisions_max = max($revisions);
	    
	    $length_min = min($lengths);
	    $length_max = max($lengths);
	    
	    $links_min = min($links);
	    $links_max = max($links); 

	    $full_entities = array();
	    foreach ($entities as $e){
	    	$rank_map = $e->get_rank_map();
	    	$rank_map['normalized_revision_count'] = 
	    		($rank_map['revision_count'] - $revisions_min) / ($revisions_max - $revisions_min);
	    	$rank_map['normalized_article_length'] = 
	    		($rank_map['article_length'] - $length_min) / ($length_max - $length_min);
	    	$rank_map['normalized_num_external_links'] = 
	    		($rank_map['num_external_links'] - $links_min) / ($links_max - $links_min);
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

			yell($dapi_query);
			$dapi_response = $dapi->search($dapi_query);
			$dapi_results = $dapi_response->getSearchResults();

			$dapi_total_count = $dapi_response->getTotalResults();
			$result_count[] = $dapi_total_count;

			echo count($dapi_results);
			//yell($dapi_results);

			foreach($dapi_results as $dapi_result) {
				yell($dapi_result->getName()->getValue());
				yell($dapi_result->getAuthor()->getValue());
			}

			break;

			$rank_map = $entity->get_rank_map();
			$rank_map['dapi_count'] = $dapi_total_count;
			$entity->set_rank_map($rank_map);

			// set holdings

			//yell($entity);
		}

		// now, enter normalized count values into each entity
		$count_max = max($result_count);
		$count_min = min($result_count);		

		foreach ($entities as &$entity) {
			$rank_map = $entity->get_rank_map();
			$rank_map['normalized_dapi_count'] = 
				($rank_map['dapi_count'] - $count_min) / ($count_max - $count_min);
			$entity->set_rank_map($rank_map);

			yell($entity);
		}

		return $entities;
	}

	public function test_data() {
		$this->output->set_content_type("application/json");

		$this->load->helper('file');
		echo read_file(FCPATH . "testdbpedia.json");
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */