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
		$dedup_results = $this->dedup_results($sparql_results);
		return $dedup_results;
	}

	// De-dup baed on the entity URI, compile list of names
	function dedup_results($sparql_results){
		$ret = array();
		$results = json_decode($sparql_results, true);
		$entities = $results['results']['bindings'];
		foreach ($entities as $entity){
			if (isset($ret[$entity['entity']['value']])){
				$ret[$entity['entity']['value']]['name'][] = $entity['name']['value'];
			}else{
				$ret[$entity['entity']['value']] = array(
					'date' => $entity['date']['value'],
					'name' => array($entity['name']['value'])
				);
			}
		}
		return $ret;
	}

}