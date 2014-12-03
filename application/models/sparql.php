<?php

class Sparql extends CI_Model {

	function __construct() {
		parent::__construct();
		$this->load->library("Rdf");
		$this->load->helper('dev');
	}

	function get_results($namespaces = array(), $query) {
	
		foreach($namespaces as $namespace) {
			EasyRdf_Namespace::set(key($namespace),current($namespace));
		}

		$sparql = new EasyRdf_Sparql_Client('http://dbpedia.org/sparql');
		$result = $sparql->query($query);

		//$serialiser = new EasyRdf_Serialiser_JsonLd();
		//yell($serialiser->serialise($result, 'jsonld'));

		return $result;
	}

}