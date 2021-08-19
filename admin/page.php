<?php
	
	/*
		Categories ==> [ insert | edite | add .. etc]
	*/


	/*
		if with abrevation way
		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
	*/

	$do = '';

	if (isset($_GET['do'])) {

		$do = $_GET['do'];

	} else {
		$do = 'Manage';
	}

	// if the page is main page 
	if ($do == 'Manage') {

		echo 'Welcome you are in mange page';
		echo '<a href="page.php?do=insert">+Add New item</a>';

	} elseif ($do == 'add') {

		echo 'Welcome you want to add item';

	} elseif ($do == 'insert') {

		echo 'Welcome you want to insert item';

	} else {
		
		echo 'error action occured';

	}