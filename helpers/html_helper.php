<?php

class HtmlHelper extends Helper {
	function link($title, $data) {
		$href = $this->parseUrl($data);
		return '<a href="'.$href.'">'.$title.'</a>';
	}

	function img($path, $properties = array()) {
		global $root;
		$path = ltrim($path, '/');
		$attributesString = '';

		if(!empty($properties)) {
			foreach($properties as $attribute => $value) {
				$attributes []= $attribute . '="' . $value . '"';
			}
			$attributesString = ' ' . implode(' ', $attributes);
		}

		if(substr($path, 0, 7) == 'http://')
			$src = $path;
		else
			$src = $root.'assets/img/'.$path;

		return '<img src="'.$src.'"'.$attributesString.' />';
	}

	function css($path) {
		global $root;

		if(substr($path, 0, 7) == 'http://')
			$href = $path;
		else
			$href = $root.'assets/css/'.$path;

		return '<link rel="stylesheet" type="text/css" href="'.$href.'" />';
	}

	function javascript($path) {
		global $root;

		if(substr($path, 0, 7) == 'http://')
			$src = $path;
		else
			$src = $root.'assets/js/'.$path;

		return '<script type="text/javascript" src="'.$src.'" /></script>';
	}
}

?>