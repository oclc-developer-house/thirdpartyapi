<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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

		$this->load->Model('Sparql');

		$this->load->helper('form');
		$this->load->helper('dev');

		$this->load->library('yaml');
	}

	public function index()
	{
		$this->load->view('main');
	}

	public function json() {
		$welcome = new stdClass();
		$welcome->message = "hi";

		$this->output->set_content_type("application/json");

		echo json_encode($welcome);
	}

	public function sparql() {

		//$this->output->set_content_type("application/json");
		
		$sparql = $this->yaml->parse_file("sparql.yml");

		if ($sparql['queries']) {
			foreach($sparql['queries'] as $query_obj) {
				$name = $query_obj['name'];
				$namespaces = $query_obj['namespaces'];
				$query = $query_obj['query'];
				//yell($query);

				$results = $this->Sparql->get_results($namespaces, $query);
				yell($results);
				break;
			}
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */