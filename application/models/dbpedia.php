<?php

include_once('entity.php');

class DBPedia extends CI_Model {
	protected $sparql_config;

	function __construct() {
		parent::__construct();

		$this->load->library('MY_Sparql');
		$this->load->library("Dapi");
		$this->load->helper('dev');
	}

	function initialize($sparql_config){
		$this->sparql_config = $sparql_config;
	}

	function get_results($query) {
		$sparql = new MY_Sparql();

		// get sparql data
		$sparql_results = $sparql->query($query);
		$dedup_results = $this->dedup_results($sparql_results);
		$entities = array();
		foreach  ($dedup_results as $uri => $props){
			$entity = new Entity();
			$entity->set_id($uri);
			$entity->set_terms($props['name']);
			$entity->set_dapi_map($this->sparql_config['discovery_map']);

			$description = $this->sparql_config['description'];
			$description = str_replace('%NAME%', $props['name'][0], $description);
			$description = str_replace('%DATE%', date('F j, Y', strtotime($props['date'])), $description);
			$entity->set_label($description);
			$entities[] = $entity;
		}
		return $entities;
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