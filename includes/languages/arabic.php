<?php
	
	function lang( $phrase ) {

		static $lang = array (

			'MESSAGE' => 'Ahln',
			'ADMIN'   => 'Ya modir'

		);

		return $lang[$phrase];
	}
