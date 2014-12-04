<?php

include_once "Rest.php";

class MY_Sparql {
	
	private $rest;

	private $default_query = 'SELECT DISTINCT ?type 
		WHERE {
		    ?s rdf:type ?type
		    FILTER regex(str(?type), "http://dbpedia.org/ontology")
		}
		LIMIT 100';

	public function __construct($config = array()) {
		$restconfig = array(
				'server' => 'http://dbpedia.org/'
			);

		$this->rest = new REST($restconfig);
	}

	public function query($query = "") {
		

		//$this->rest->initialize($restconfig);

		$results = $this->rest->get('sparql',
			array('default-graph-uri' => 'http://dbpedia.org',
					'query' => $query,
					'output' => 'json'),
			'application/sparql-results+json'
			);

		//$this->rest->debug();

		return $results;
	}

}