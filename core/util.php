<?php

function load_template($fname, $vars) {
	extract($vars);
	$__content_file = $fname;
	require("templates/base.php");
}

//returns the url segment like "main" in case of get_segment(2) where uri is "index/main"
function get_segment($idx) {
	$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$parts = explode('/', $uri);
	//print_r($parts);
	return $parts[$idx];
}