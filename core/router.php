<?php
/**
* router.php
* 
* Routing capabilities
* 
* @author Prahlad Yeri <prahladyeri@yahoo.com>
* @license GPL v3
*/

function site_url($uri = "") {
	$idx = (Router::$index_file==''?'':Router::$index_file.'/');
	return base_url() . $idx . $uri;
}

function base_url() {
	return Router::$base_url;
}

class Router {
	public static $base_url = null;
	public static $index_file = 'index.php';
	public static $pre_dispatch = null;
	
	private static $routes = array();
	
	public static function addRoute($method, $route, $action) {
		self::$routes[$method][$route] = $action;
	}

	public static function get($route, $action) {
		self::addRoute('GET', $route, $action);
	}
	
	public static function post($route, $action) {
		self::addRoute('POST', $route, $action);
	}
	
	public static function put($route, $action) {
		self::addRoute('PUT', $route, $action);
	}
	
	public static function dispatch() {
		$method = $_SERVER['REQUEST_METHOD'];
		$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		if (strpos($uri, "/index.php") === 0) {
			$uri = substr($uri, 10);
		}
		if (self::$base_url === null) { //set it automatically
			//echo "setting base_url automatically";
			self::$base_url = "http://" . $_SERVER['HTTP_HOST'];
			self::$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
			error_log("index file is " . self::$index_file);
			error_log( "auto setting base_url as ".self::$base_url);
		} else { //strip off from uri
			//@todo: validate base_url is proper
			$turi = parse_url(self::$base_url, PHP_URL_PATH);
			$turi=  rtrim($turi, '/');
			//echo "uri:$uri<br>turi$turi<br>";
			$uri = substr($uri, strlen($turi));
			//echo "uri:$uri<br>";
		}
		if ($uri=="") $uri = "/";
		if (is_callable(self::$pre_dispatch)) {
			$call = self::$pre_dispatch; 
			$call();
		}
		foreach (self::$routes[$method] as $route => $action) {
			//echo "proc:$route::$uri<br>";
			if ($route === $uri) {
				return self::execute($action);
			}
			else if (substr($route, -1)==='*') {
				//pattern match
				$start = substr($route, 0, -1);
				if (strpos($uri, $start)===0) {
					return self::execute($action); 
				}
				
			}
		}
		http_response_code(404);
		echo '404 Not Found';		
	}
	
	private static function execute($action) {
		if (is_callable($action)) {
		  return $action();
		}
	}
}