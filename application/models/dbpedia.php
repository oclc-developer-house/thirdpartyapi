<?php

include_once('entity.php');

class DBPedia extends CI_Model {

	function __construct() {
		parent::__construct();

		$this->load->library('MY_Sparql');
		$this->load->library("Dapi");
		$this->load->helper('dev');
	}

	function get_results($query) {
		$sparql = new MY_Sparql();

		// get sparql data
		$sparql_results = $sparql->query($query);

		return $sparql_results;
	}

}