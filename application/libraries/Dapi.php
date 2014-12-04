<?php

require_once FCPATH . "vendor/autoload.php";

use OCLC\Auth\WSKey;
use OCLC\Auth\AccessToken;
use WorldCat\Discovery\Bib;

class Dapi {

	protected $wskey = 'esa7gqRn7ScQ2UVYrcdWYdYCHRYoEAh2mDXGhjTZRW5mc2eunG7BMbJoWAvg80YrBaqIIR7ZbqaapCWR';
	protected $secret = 'zkGvIDURRns5xUpiTSjqPQ==';

	protected $accessToken;

	public function __construct() {
		$options = array('services' => array('WorldCatDiscoveryAPI', 'refresh_token'));
		$wskey = new WSKey($this->wskey, $this->secret, $options);
		$this->accessToken = $wskey->getAccessTokenWithClientCredentials('128807', '128807');
	}	
	

	public function get_bib() {
		$bib = Bib::Find(7977212, $this->accessToken);

		yell($bib);
	}

}