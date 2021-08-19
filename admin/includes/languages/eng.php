<?php
	
	function lang( $phrase ) {

		static $lang = array (

			// Nabar links

			'HOME PAGE' => 'Home',
			'CATEGORIES' => 'Categories',
			'ITEMS' => 'Items',
			'MEMBERS' => 'Members',
			'STATICS' => 'Statics',
			'COMMENTS' => 'Comments',
			'LOGS' => 'Logs',
			'ACCOUNT NAME' => 'Ayad',
			'EDITE PROFILE' => 'Edite Profile',
			'SETTINGS' => 'Setting',
			'LOGOUT' => 'Log out',

			// Function.php Words
			'DEFAULT' => 'Default'

		);

		return $lang[$phrase];
	}
