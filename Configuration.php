<?php

class Configuration {

	private $data = array();
	
	private function __construct() {

	}

	public function getInstance() {
		static $self;
		if ($self === null) {
			$self = new static();
		}
		return $self;
	}

	public function get($name, $default = false) {
		return GetValueR($name, $this->data, $default);
	}

	public function set($name, $value) {

	}
}