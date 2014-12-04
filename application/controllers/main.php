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
				$name = $query_obj['name'];
				$query = $query_obj['query'];
				$query = $this->set_query_dates($query);
				array_push($results, $this->DBPedia->get_results($query));
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
	
		$this->output->set_content_type("application/json");
		// step 1 : get dbpedia results
		$dbpedia_results = $this->get_dbpedia_results();


		// step 2 : get wiki counts for each 

		// step 3 : normalize counts for each entity

		// step 4 : get dapi results for each entity

		echo json_encode($dbpedia_results);
		
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */