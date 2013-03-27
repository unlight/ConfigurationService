<?php

/**
 * Configuration service provider for Silex.
 * Usage:
 * $app->register(new ConfigurationServiceProvider('conf'))
 * conf - is a directory where your config files are stored.
 */

use Silex\ServiceProviderInterface;
use Silex\Application;

class ConfigurationServiceProvider implements ServiceProviderInterface {

	protected $path = 'conf';

	public function __construct($path = null) {
		loadFunctions('silex');
		loadFunctions('general');
		if ($path !== null) {
			$this->path = $path;
		}
	}

	/**
	 * Registers services on the given app.
	 *
	 * This method should only be used to configure services and parameters.
	 * It should not get services.
	 *
	 * @param Application $app An Application instance
	 */
	public function register(Application $app) {
		if (!isset($app['path.conf'])) {
			$app['path.conf'] = $this->path;
		}

		$path = $app['path.conf'];
		if (empty($path)) throw new Exception("Application value 'path.conf' is not defined.");
		if (!is_dir($path)) throw new Exception("Directory '$path' specified in 'path.conf' does not exists.");

		$configuration = array();
		if (file_exists("$path/config-defaults.php")) {
			$configuration = require "$path/config-defaults.php";
		}
		if (file_exists("$path/config.php")) {
			$configuration = mergeArrays(require "$path/config.php", $configuration);
		}
		// Loading configuration.
		$app['configuration'] = $configuration;
		$root = getValue('.', $configuration);
		if (is_array($root)) {
			foreach ($root as $key => $value) {
				$app[$key] = $value;
			}
		}

		// Function to get config value.
		$app['config'] = $app->share(function($app) {
			return function($name, $default = false) use ($app) {
				$configuration = $app['configuration'];
				$value = $default;
				if (isset($configuration[$name])) {
					$value = $configuration[$name];
				} else {
					$path = explode('.',  $name);
					$value = $configuration;
					for ($i = 0, $count = count($path); $i < $count; $i++) {
						$subkey = $path[$i];
						if (isset($value[$subkey])) {
							$value = $value[$subkey];
						} else {
							return $default;
						}
					}
					return $value;
				}
				return $value;
			};
		});
	}

	/**
	 * Bootstraps the application.
	 *
	 * This method is called after all services are registers
	 * and should be used for "dynamic" configuration (whenever
	 * a service must be requested).
	 */
	public function boot(Application $app) {

	}
}