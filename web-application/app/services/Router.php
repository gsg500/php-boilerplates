<?php

namespace app\services;

use app\services\libraries\ParamType;
use app\controllers\FallbackController;
use app\ServiceLoader;

class Router {
	
	/**
	 * @var ServiceLoader
	 */
	private $loader;
	private $routes = [];
	private $notFound;
	
	/**
	 * Router constructor.
	 *
	 * @param ServiceLoader $loader
	 */
	public function __construct ( ServiceLoader $loader ) {
		
		$this->loader   = $loader;
		$this->notFound = function ( $url ) {
			
			$fallBack = new FallbackController( $this->loader );
			$fallBack->index( $url );
		};
	}
	
	/**
	 * @param $url
	 * @param $action
	 */
	public function add ( $url, $action ) {
		
		$this->routes[ $url ] = $action;
	}
	
	/**
	 * @param $action
	 */
	public function setNotFound ( $action ) {
		
		$this->notFound = $action;
	}
	
	/**
	 * @return mixed
	 */
	public function dispatch () {
		
		foreach ( $this->routes as $url => $action ) {
			
			if ( preg_match( "%{(.*)}%", $url, $matches ) ) {
				$expression = $this->getExpression( $matches );
				$url        = str_replace( "{" . $expression[ 0 ] . "}", $expression[ 1 ], $url );
			};
			
			if ( preg_match( "%^" . $url . "$%", $_SERVER[ 'REQUEST_URI' ], $matches ) ) {
				if ( is_callable( $action ) ) {
					return $action();
				}
				
				$actionArray = explode( '@', $action );
				$controller  = 'app\\controllers\\' . $actionArray[ 0 ];
				$method      = $actionArray[ 1 ];
				
				$param = ( isset( $matches[ 1 ] ) ) ? $matches[ 1 ] : "";
				
				if ( class_exists( $controller ) ) {
					return ( new $controller( $this->loader ) )->$method( $param );
				}
				
			}
			
		}
		
		call_user_func( $this->notFound, [ $_SERVER[ 'REQUEST_URI' ] ] );
		
	}
	
	/**
	 * @param $matches
	 *
	 * @return array|string
	 */
	public function getExpression ( $matches ) {
		
		switch ( $matches[ 1 ] ) {
			
			case 'id':
				return [ 'id', ParamType::INTEGER ];
				break;
			
			case 'name':
			
				return ['name', ParamType::NAME];
				
			break;
			
			case 'title':
				
				return ['title', ParamType::TITLE];
				
			break;
			
			default:
				return ParamType::INTEGER;
				break;
			
		}
		
	}
	
}