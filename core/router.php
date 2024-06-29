<?php
/**
* router.php
* 
* Routing capabilities
* 
* @author Prahlad Yeri <prahladyeri@yahoo.com>
* @license MIT
*/
const MMVC_VER = "1.0";
define('APP_PATH', dirname(__DIR__ ));
$base_url = null;
$index_file = 'index.php';
//$routes = array();

function site_url($uri = "") {
	$idx = ($index_file==''?'' : $index_file .'/');
	return base_url() . $idx . $uri;
}

function base_url() {
	global $base_url;
	return $base_url;
}


function dispatch($pre_dispatch_func) {
	//$method = $_SERVER['REQUEST_METHOD'];
	global $base_url, $index_file;
	$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	if ($base_url === null) { //set it automatically
		//echo "setting base_url automatically";
		$base_url = "http://" . $_SERVER['HTTP_HOST'];
		$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
		error_log("index file is " . $index_file);
		error_log( "auto setting base_url as ".$base_url);
	} else { //strip off from uri
		//@todo: validate base_url is proper
		$turi = parse_url($base_url, PHP_URL_PATH);
		$turi=  rtrim($turi, '/');
		//echo "uri:$uri<br>turi$turi<br>";
		$uri = substr($uri, strlen($turi));
		//echo "uri:$uri<br>";
	}
	if (strpos($uri, "/index.php") === 0) {
		$uri = substr($uri, 10);
	}
	if ($uri=="") $uri = "/";
	if (is_callable($pre_dispatch_func)) {
		$call = $pre_dispatch_func; 
		$call();
	}
	//echo 'uri_segment[0]::' . uri_segment(1);
	//error_log( "uri::$uri");
	//@todo: ensure a way of limiting total segments, lenghty urls like /foo/bar/baz/xyz/1234
	// ideal way is to let functions have their parameters, then pass it on to them after a count validation.
	if (uri_segment(1) == '' && function_exists('index')) {
		index();
	} else if (function_exists(uri_segment(1))) {
		$func = uri_segment(1);
		call_user_func($func, $uri);
	} else {
		http_response_code(404);
		echo '404 Not Found';		
	}
}
