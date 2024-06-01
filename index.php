<?php
/**
* index.php
* 
* App entry point
* 
* @author Prahlad Yeri<prahladyeri@yahoo.com>
* @license GPL v3
*/



require('core/router.php');
require('core/util.php');

//for easy access to your static paths with base_url()
//Router::$base_url = "http://localhost:8000/"; 

//set blank if you rewrite index.php in .htaccess
//Router::$index_file = 'index.php'; 

Router::get('/', function(){
	echo "<h1>It Works!</h1>";
});

Router::get("/testmvc", function(){
	$vars = ["foo"=>'bar', 'title'=>'Testing'];
	load_template('templates/dummy.php', $vars);
});

Router::get("/testform", function(){
	echo "<form method='post'><input name='sample_field' value=''><button type='submit'>Submit</button></form>";
});
Router::post("/testform", function(){
	echo "You posted!<br>" . print_r($_POST, true);
});


Router::dispatch();