<?php
/**
* base.php
* 
* Base template
* 
* @author Prahlad Yeri <prahladyeri@yahoo.com>
* @license GPL v3
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title><?=$title?></title>
	<link rel="stylesheet" href="<?=base_url()?>static/css/app.css?v=1">
</head>
<body>
<div class='main'>
<p class='strong'>Welcome to minimal-mvc.
<br><br>
This is an attempt to remove the usual cruft that goes around the monstrous PHP frameworks and other software components in webdev world.
<br><br>
Together, we will create a more frugal and elegant world without cruft, enjoy!
</p>
<?php require($__content_file) ?>
</div>
</body>
</html>