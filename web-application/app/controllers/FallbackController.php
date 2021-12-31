<?php

namespace app\controllers;

class FallbackController extends Controller {
	
	/**
	 * @param $url
	 */
	public function index ( $url ) {
		
		header( "HTTP/1.0 404 Not Found" );
		$stack[ 'title' ] = "404 - not found";
		$stack[ 'url' ]   = $url;
		
		$this->render( 'static.404', $stack );
		
	}
	
}