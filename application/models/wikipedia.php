<?php

require_once FCPATH."vendor/autoload.php";

use GuzzleHTTP\Client;

class Wikipedia extends CI_Model {
  var $wikipedia = 'http://en.wikipedia.org/w/api.php/';
  var $format = 'json';
  var $rvlimit = 100;
  var $ellimit = 200;
  var $title = '';

  function __construct() {
    parent::__construct();
  }

  function initialize($title){
    $this->title = $title;
  }

  function get_revision_count(){
    $base_query = '?action=query&prop=revisions&'.
                  'rvlimit='.$this->rvlimit.'&rvprop=timestamp&'.
                  'format='.$this->format;

    $start = date('Y-m-d\TH:m:s\Z', strtotime('now'));
    $end = date('Y-m-d\TH:m:s\Z', strtotime('-6 months'));

    $query = $base_query.'&titles='.urlencode($this->title).'&rvstart='.$start.'&rvend='.$end;

    $client = new GuzzleHttp\Client();
    $response = $client->get($this->wikipedia.$query);
    $json = $response->json();
    $pages = array_keys($json['query']['pages']);
    $page = $pages[0];
    if (isset($json['query']['pages'][$page]['revisions'])){
      return count($json['query']['pages'][$page]['revisions']);
    }else{
      return 0;
    }    
  }

  function get_article_length(){
    $base_query = '?action=query&prop=info&inprop=length&'.
                  'format='.$this->format;

    $query = $base_query.'&titles='.urlencode($this->title);

    $client = new GuzzleHttp\Client();
    $response = $client->get($this->wikipedia.$query);
    $json = $response->json();
    $pages = array_keys($json['query']['pages']);
    $page = $pages[0];
    if (isset($json['query']['pages'][$page]['length'])){
        return $json['query']['pages'][$page]['length'];
    }else{
      return 0;
    }    
  }

  function get_num_external_links(){
    $base_query = '?action=query&prop=extlinks&'.
                  'ellimit='.$this->ellimit.'&'.
                  'format='.$this->format;

    $query = $base_query.'&titles='.urlencode($this->title);

    $client = new GuzzleHttp\Client();
    $response = $client->get($this->wikipedia.$query);
    $json = $response->json();
    $pages = array_keys($json['query']['pages']);
    $page = $pages[0];
    if (isset($json['query']['pages'][$page]['extlinks'])){
        return count($json['query']['pages'][$page]['extlinks']);
    }else{
      return 0;
    }     
  }
}