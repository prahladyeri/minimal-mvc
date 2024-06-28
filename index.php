<?php
/**
* index.php
* 
* App entry point
* 
* @author Prahlad Yeri <prahladyeri@yahoo.com>
* @license MIT
*/

require('core/router.php');
require('core/util.php');
session_start();

//$base_url = "http://localhost:8000/"; //for easy access to static paths
//$index_file = 'index.php';  //set blank if you rewrite index.php in .htaccess

//@todo: initialize constants and vars
const VERSION = "0.1";

//@todo: initialize database
$dbh = new PDO("sqlite:minimal-mvc.db");

function index() {
	echo "<h1>It Works!</h1>";
};

function arcane(){
	echo "<p>Pattern Match!</p>";
	echo "<p>The uri segments are :".print_r(uri_segments(),true)."</p>";
};

function testmvc(){
	$vars = ["foo"=>'bar', 'title'=>'Testing'];
	load_template('templates/dummy.php', $vars);
};

function testform(){
	echo "<form method='post'><input name='sample_field' value=''><button type='submit'>Submit</button></form>";
};

dispatch(function(){
	//check install and any other common initialization
	//global $dbh;
	error_log("pre-dispatch:");
});