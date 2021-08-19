<?php

	include 'connect.php';

	// Pathes to files
	$temp = 'includes/templates/'; // templete file
	$lang= 'includes/languages/';  // language file
	$func = 'includes/functions/'; // Function Files
	$css = 'layout/css/'; // css file
	$js = 'layout/js/'; // Js path to file
	
	
	// include the important Files
	include $func . 'functions.php';
	include $lang . 'eng.php';
	include $temp . 'header.php';

	// include navbar in all pages except the one with $nonavbar variable

	if (!isset($nonavbar)) {
		
		include $temp . 'navbar.php';
	}
	
	