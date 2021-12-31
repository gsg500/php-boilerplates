<?php

namespace app\services;

class Autoloader {
	
	function __construct () {
		
		spl_autoload_register( function ( $file ) {
			if ( ! class_exists( $file ) ) {
				$file = str_replace( "\\", "/", $file );
				include $file . '.php';
			}
		} );
		
	}
	
}