<?php


class Entity implements \JsonSerializable {
	protected $id;
	protected $terms; // DAPI search terms
	protected $label;
	protected $image;
	protected $rank;
	protected $rank_map; // key value for ranking components
	protected $dapi_map; // indicies for DAPI queries
	protected $holdings;

	public function __construct() {
		$this->terms = array();
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
		$this->label = $label;
	}

	public function get_id() {
		return $this->id;
	}

	public function set_id($id = "") {
		$this->id = $id;
	}

	public function get_terms() {
		return $this->terms;
	}

	public function set_terms($terms = array()) {
		$this->terms = $terms;
	}

	public function get_image() {
		return $this->image;
	}

	public function set_image($uri = "") {
		$this->image = $uri;
	}

	public function get_dapi_map() {
		return $this->dapi_map;
	}

	public function set_dapi_map($map = array()) {
		$this->dapi_map = $map;
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

	public function JsonSerialize() {
		return get_object_vars($this);
	}

}