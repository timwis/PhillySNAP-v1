<?php

class Controller {
	var $models;
	var $helpers;
	var $_view;

	function __construct($controller, $action, $querystring, $json = false) {
		
		// Load Models
		if(isset($this->models)) {
			foreach($this->models as $model) {
				$this->$model = new $model;
			}
		}

		// Load View
		$this->_view = new View($controller, $action, $this->helpers);

		// Execute Action
		if(method_exists($this, $action))
			call_user_func_array(array($this, $action), $querystring);
		else
			trigger_error('Method not found for action \''.$action.'\'');

		// Render View
		$this->_view->render($json);
	}

	function set($key, $value) {
		$this->_view->set($key, $value);
	}
}

?>