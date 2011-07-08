<?php
// Globals
$root = '';
$url = isset($_GET['url']) ? $_GET['url'] : '';

// Includes
require_once('config.php');
require_once('common.php');

// Clean post
if(isset($_POST))
	$_POST = sanitize_array($_POST);

// Connect to database
db_connect($db);

// Execute the url
$router = new Router($url);

?>