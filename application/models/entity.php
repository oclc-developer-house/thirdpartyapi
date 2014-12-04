<?php


class Entity {

	protected $label;
	protected $image;
	protected $rank;
	protected $rank_map;

	protected $dapi_map;
	protected $holdings;

	public function __construct() {
		parent::__construct();

		$this->label = "Test Label";
		$this->image = "image uri";
		$this->rank_map = array();
		$this->dapi_map = array();
		$this->holdings = array();
	}

	public function get_label() {
		return $this->label;
	}

	public function set_label($label = "") {
		$this->label = label;
	}

	public function get_image() {
		return $this->image;
	}

	public function set_image($uri = "") {
		$this->image = $uri;
	}

	public function get_rank_map() {
		return $this->rank_map;
	}

	public function set_rank_map($map = array()) {
		$this->rank_map = $map;
	}

	public function get_computed_rank() {
		// compute from the rank_map
		return 0;
	}

	public function get_holdings() {
		return $this->holdings;
	}

	public function set_holdings($holdings = array()) {
		$this->holdings = $holdings;
	}

	public function add_holding($holding = array()) {
		array_push($this->holdings, $holding);
	}

	public function get_num_holdings() {
		return count($this->holdings);
	}

}