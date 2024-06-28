<?php
/**
* util.php
* 
* Common Utilities
* 
* @author Prahlad Yeri <prahladyeri@yahoo.com>
* @license MIT
*/

/* MISC UTILITIES */
function get_method() {
	return $_SERVER["REQUEST_METHOD"];
}
function default_vars($module) {
	$vars = ["module"=>$module,
		'errors'=>[],
		"messages"=>[],
	];
	//error_log("default_vars::SESSION" . print_r($_SESSION,true));
	if (isset($_SESSION['message'])) {
		$vars['messages'][] = $_SESSION['message'];
		unset($_SESSION['message']);
	}
	if (isset($_SESSION['error'])) {
		$vars['errors'][] = $_SESSION['error'];
		unset($_SESSION['error']);
	}
	return $vars;
}

/* DATABASE UTILITIES */
function exec_sql($dbh, $sql, $vals) {
	$sth = $dbh->prepare($sql);
	$sth->execute($vals);
	return true;
}
function fetch_rows($dbh, $sql, $arr=null) {
	$sth = $dbh->prepare($sql);
	if ($arr)
		$sth->execute($arr);
	else
		$sth->execute();
	return $sth->fetchAll();
}
function build_insert_query($table, $values)
{
	$fields = [];
	$padding = [];
	$value_array = [];
	foreach($values as $key=>$value) {
		//if (!is_numeric($value)) $value = "'".$value."'";
		$fields[] = $key;
		$padding[] = ":$key";
		$value_array[] = $value;
	}
	return [ "insert into $table(" . implode(',', $fields) . ") values(" . implode(',', $padding) . ")",
	$value_array ];
}
function build_update_query($table, $values, $id)
{
	$ss = "update ".$table." set ";
	$fields = [];
	$value_array = [];
	foreach($values as $key=>$value) {
		//if (!is_numeric($value)) $value = "'".$value."'";
		$fields[] = "$key=:$key";
		$value_array[] = $value;
	}
	$fields[] = "modified_at=?";
	$value_array[] = (new DateTime())->format("Y-m-d H:i:s");
	$where = "id=:id";
	$value_array[] = $id;
	return [ $ss . implode(',', $fields) . " where " . $where,
	$value_array ];
}
function clean_sql($sql) {
	$parts = explode(";", $sql);
	$rval = "";
	for($i=0; $i<count($parts); $i++) {
		$lines= $array = preg_split("/\r\n|\n|\r/", $parts[$i]);
		$ss = "";
		for ($j=0;$j<count($lines);$j++) {
			$lines[$j] = trim($lines[$j]);
			if ($lines[$j] === '') continue;
			if (strpos($lines[$j], '--') !== 0) {
				$ss .= $lines[$j] . "\n";
			}
		}
		$ss = trim($ss);
		if ($ss !== "") $rval .= $ss . ";";
		// if (strlen($ss) > 0) { 
			// $rval .= $ss . ";";
			// echo "CLEAN_SQL:".  $ss . ":".strlen($ss) . "<br>";
		// }
	}
	return $rval;
};

/* URL UTILITIES */

function load_template($fname, $vars) {
	extract($vars);
	//echo "base_url::".base_url();
	$__content_file = $fname;
	require("templates/base.php");
}

function get_uri() {
	//$bup = parse_url($base_url, PHP_URL_PATH);
	$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	if (strpos($uri, "/index.php") === 0) {
		$uri = substr($uri, 10);
	}
	return $uri;
}

// @todo: trim any subdirs from the start of uri before parsing
function uri_segment($idx) {
	$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	if (strpos($uri, "/index.php") === 0) {
		$uri = substr($uri, 10);
	}
	$parts = explode('/', $uri);
	//print_r($parts);
	if ($idx<0) $idx = count($parts)+$idx;
	return $parts[$idx];
}

function uri_qs() {
	$parts = parse_url($_SERVER['REQUEST_URI']);
	if (isset($parts["query"])) {
		$out = [];
		parse_str($parts["query"], $out);
		return $out;
	}
	else {
		return null;
	}
}
function uri_segments() {
	$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	if (strpos($uri, "/index.php") === 0) {
		$uri = substr($uri, 10);
	}
	return explode('/', $uri);
}