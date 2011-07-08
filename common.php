<?php

//$root = isset($root) ? $root : preg_replace('/\/[^\/]+/','../',dirname($_SERVER['PHP_SELF']));

function db_connect($db) {
	$dbhandle = mysql_connect($db['host'], $db['user'], $db['pass']) or die(mysql_error());
	if(!mysql_select_db($db['name'], $dbhandle))
		die('Database Connection Error');
}

function set_root($layers) {
	global $root;

	for($i = 0; $i < $layers; $i++)
		$root .= '../';
}

function __autoload($className) {
	$className = uncamelize($className);

	$attempts = array(
		'controllers/' . $className . '.php',
		'models/' . $className . '.php',
		'helpers/' . $className . '.php',
		'lib/' . $className . '.class.php'
	);

	foreach($attempts as $attempt) {
		if(file_exists($attempt)) {
			require_once($attempt);
			return;
		}
	}
	// Else
	trigger_error('Class file for \''.$className.'\' not found');
}

function get_lat_lon($address) {
	$geocoder_base_url = "http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=";
    $res = file_get_contents($geocoder_base_url . urlencode($address));
    $json = json_decode($res);

    # http://code.google.com/apis/maps/documentation/geocoding/index.html#JSON
    if (strcmp($json->{"status"}, "OK") == 0) {
        return $json->{"results"}[0]->{"geometry"}->{"location"};
    } else {
        return null;
    }
}

function geocode($address) {
	$geocoder_base_url = "http://local.yahooapis.com/MapsService/V1/geocode?appid=p4ijf_LV34FYk0k6MlPyG9sungHnguquK.ACmo9kh6YjZtB2CV4kMvoFVjXMM0UC5gQ_MA53uNgMouVcrzQj5tac1U9_TfY-&output=php&location=";
    $res = file_get_contents($geocoder_base_url . urlencode($address));
    $results = unserialize($res);
	$result = $results['ResultSet']['Result'];

    # http://code.google.com/apis/maps/documentation/geocoding/index.html#JSON
    if (!isset($result['warning'])) {
        return $result;
    } else {
        return null;
    }
}

// Check if environment is development and display errors
/*function setReporting() {
	if (defined('DEVELOPMENT_ENVIRONMENT') && DEVELOPMENT_ENVIRONMENT == true) {
		error_reporting(E_ALL);
		ini_set('display_errors','On');
	} else {
		error_reporting(E_ALL);
		ini_set('display_errors','Off');
		ini_set('log_errors', 'On');
		ini_set('error_log', ROOT.DS.'tmp'.DS.'logs'.DS.'error.log');
	}
}*/

function camelize($string, $pascalCase = false) 
{ 
	$string = str_replace(array('-', '_'), ' ', $string); 
	$string = ucwords($string); 
	$string = str_replace(' ', '', $string);  

	if (!$pascalCase) { 
		return lcfirst($string); 
	} 
	return $string; 
}

function uncamelize($camel,$splitter="_") {
	$camel=preg_replace('/(?!^)[[:upper:]][[:lower:]]/', '$0', preg_replace('/(?!^)[[:upper:]]+/', $splitter.'$0', $camel));
	return strtolower($camel);
}

function get_include_contents($filename) {
	if (is_file($filename)) {
		ob_start();
		include $filename;
		return ob_get_clean();
	}
	return false;
}

function time_parts($minutes) {
	$d = floor ($minutes / 1440);
	$h = floor (($minutes - $d * 1440) / 60);
	$m = $minutes - ($d * 1440) - ($h * 60);

	return array(
		'days' => $d, 
		'hours' => $h, 
		'minutes' => $m);
}

function colonize_time($time_parts) {
	$txt = '';
	if($time_parts['days']) $txt = $time_parts['days'].':';
	if($time_parts['hours']) $txt .= $time_parts['hours'].':';
	$txt .= $time_parts['minutes'];
	return $txt;
}

function humanize_time($time_parts) {
	$txt = '';
	if($time_parts['days']) $txt = $time_parts['days'].' days, ';
	if($time_parts['hours']) $txt .= $time_parts['hours'].' hours, ';
	$txt .= $time_parts['minutes'].' mins';
	return $txt;
}

function sanitize($string) {
	if(is_array($string))
		return sanitize_array($string);
	else
		return mysql_real_escape_string(trim($string));
}

function sanitize_array($array) {
	return array_map('sanitize', $array);
}

?>