<?php
/**
* util.php
* 
* Common Utilities
* 
* @author Prahlad Yeri <prahladyeri@yahoo.com>
* @license LGPL v2.1
*/

/* MISC UTILITIES */
function get_method() {
	return $_SERVER["REQUEST_METHOD"];
}
function default_vars($module) {
	return ["module"=>$module,
		'errors'=>[],
		"messages"=>[],
	];
}

/* DATABASE UTILITIES */
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

//returns the url segment like "main" in case of uri_segment(2) where uri is "index/main"
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
function uri_segments() {
	$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	if (strpos($uri, "/index.php") === 0) {
		$uri = substr($uri, 10);
	}
	return explode('/', $uri);
}