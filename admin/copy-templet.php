<?php


	/*
	====================================

	== Templet Page

	====================================
	*/

	ob_start(); // Output Beffering Start

	session_start();

	$pagetiltle = '';

    if (isset($_SESSION['Username'])) {

        include 'init.php';


        $do = isset($_GET['do']) ? $_GET['do'] : 'manage';

        if ($do == 'manage') {

        } elseif ($do == 'add') {

        } elseif ($do == 'insert') {

        } elseif ($do == 'edit') {

        } elseif ($do == 'update') {

        } elseif ($do == 'delete') {

        } elseif ($do == 'activate') {


        }




        include $temp . 'footer.php';

    } else {

    	header('Location: index.php');

    	exit();
    }

    ob_end_flush(); // Release The output