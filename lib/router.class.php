<?php

class Router {

	function __construct($url = '') {
		$querystring = array();
		$json = false;

		if($url) {
			// Parse url path
			$parts = explode('/', $url);
			$parts = sanitize_array($parts);
			$parts = array_map('strtolower', $parts); // all lowercase

			// Set root
			set_root(sizeof($parts)-1);

			$controller = $parts[0];
			array_shift($parts);
			if(!empty($parts)) { // if action specified
				if(substr($parts[0], -5) == '.json') {
					$parts[0] = rtrim($parts[0], '.json');
					$json = true;
				}
				$action = $parts[0];
				array_shift($parts);
				if(!empty($parts)) // if querystring specified
					$querystring = $parts;
			}
			else
				$action = DEFAULT_ACTION;
		}
		else {
			$controller = DEFAULT_CONTROLLER;
			$action = DEFAULT_ACTION;
		}

		$controllerName = camelize($controller, true) . 'Controller';
		return new $controllerName($controller, $action, $querystring, $json);

	}
}

?>