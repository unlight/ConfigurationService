<?php

class Configuration {

	private $data = array();
	
	private function __construct() {

	}

	public static function getInstance() {
		static $self;
		if ($self === null) {
			$self = new static();
		}
		return $self;
	}

	public function get($name, $default = false) {
		if (isset($this->data[$name])) {
			return $this->data[$name];
		}
		if (strpos($name, '.') === false) {
			return GetValue($name, $this->data, $default);
		}
		return GetValueR($name, $this->data, $default);
	}

	public function set($name, $value = null, $options = false) {
		if (is_array($name)) {
			$oneByOne = GetValue("oneByOne", $options);
			if ($oneByOne) {
				foreach ($name as $key => $value) {
					$this->set($key, $value);
				}
			} else {
				$this->data = mergeArrays($name, $this->data);
			}
		} elseif (strpos($name, '.') !== false) {
			$path = explode('.', $name);
			$array =& $this->data;
			for ($i = 0, $count = count($path); $i < $count; $i++) {
				$key = $path[$i];
				if ($key == '') $key = '.';
				if (!array_key_exists($key, $array)) {
					$array[$key] = array();
				}
				$array =& $array[$key];
			}
			$array = $value;
		} else {
			$application = application();
			$application[$name] = $value;
			return $this->set(".$name", $value);
		}
	}

	public function save() {
		$app = application();
		$pathConf = $app['path.conf'];
		$file = "$pathConf/config.php";
		$data = "<?php return " . var_export($this->data, true) . ";";
		$data = str_replace("' => \n", "' => ", $data);
		file_put_contents($file, $data);
	}
}