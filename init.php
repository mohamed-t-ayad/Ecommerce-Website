<?php

	// Error Reporting
	ini_set('display_error' , 'On');
	error_reporting('E_ALL');

	include 'admin/connect.php';

	$sessionUser = '';

	if (isset($_SESSION['user'])){
		$sessionUser = $_SESSION['user'];
	}
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
 
	