<?php

class View {
	protected $variables = array();
	protected $_controller;
	protected $_action;

	function __construct($controller, $action, $helpers = array()) {
		$this->_controller = $controller;
		$this->_action = $action;

		// Load Helpers
		if(isset($helpers)) {
			foreach($helpers as $helper) {
					$helperClass = $helper.'Helper';
					$this->$helper = new $helperClass($controller, $action);
			}
		}
	}

	function set($key, $value) {
		$this->variables[$key] = $value;
	}

	function render($json = false) {
		global $root;
		//Extract data to their own variables
		extract($this->variables);

		$file = 'views/'.$this->_controller.'/'.($json ? 'json/' : '').$this->_action.'.tmp';

		if(file_exists($file)) {
			ob_start();
			include $file;
			$content_for_layout = ob_get_clean();
			if($json)
				require_once('views/layouts/json/default.tmp');
			else
				require_once('views/layouts/default.tmp');
		}
		else
			trigger_error('View template \''.$this->_action.'.tmp\' not found');
	}

}

?>