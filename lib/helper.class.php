<?php

class Helper {
	protected $_controller;
	protected $_action;

	function __construct($controller, $action) {
		$this->_controller = $controller;
		$this->_action = $action;
	}

	function parseUrl($data) {
		global $root;
		$url = $root;

		if(is_array($data)) {

			if(isset($data['controller']) && $data['controller'])
				$url .= $data['controller'];
			elseif(!isset($data['controller']) && isset($this->_controller))
				$url .= $this->_controller;

			if(isset($data['action']) && $data['action'])
				$url .= '/'.$data['action'];
			elseif(!isset($data['action']) && isset($this->_action))
				$url .= '/'.$this->_action;

			if(isset($data['id']))
				$url .= '/'.$data['id'];
		}
		else {
			if(substr($data, 0, 7) == 'http://' || substr($data, 0, 7) == 'mailto:' || substr($data, 0, 8) == 'steam://')
				$url = $data;
			else
				$url .= $data;
		}
		return $url;
	}
}

?>