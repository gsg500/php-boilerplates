<?php

namespace app\controllers;

use app\ServiceLoader;
use app\services\Router;
use app\models\Model;

class Controller {
	
	const MODEL = "Model";
	
	/** @var Model */
	protected $model;
	
	/**
	 * Controller constructor.
	 *
	 * @param ServiceLoader $loader
	 */
	public function __construct ( $loader ) {
		
		$model = 'app\\models\\' . $this::MODEL;
		
		if ( class_exists( $model ) ) {
			$this->model = new $model( $loader );
		}
		
	}
	
	/**
	 * @return bool
	 */
	public function isPost () {
		
		return ( $_SERVER[ 'REQUEST_METHOD' ] == "POST" ) ? true : false;
	}
	
	/**
	 * @param       $view
	 * @param array $data
	 */
	public function render ( $view, $stack = [] ) {
		
		$view = str_replace( '.', '/', $view );
		$view = 'public/views/' . $view . '.php';
		
		if ( file_exists( $view ) ) {
			
			$stack[ 'messages' ][ 'errors' ]        = $this->model->message->getErrors();
			$stack[ 'messages' ][ 'notifications' ] = $this->model->message->getNotifications();
			$stack[ 'messages' ][ 'success' ]       = $this->model->message->getSuccess();
			$stack[ 'title' ]                       = ( isset( $stack[ 'title' ] ) ) ? $stack[ 'title' ] : "";
			$stack[ 'description' ]                 = ( isset( $stack[ 'description' ] ) ) ? $stack[ 'description' ] : "";
			
			extract( $stack );
			
			$this->model->message->clear();
			
			if ( isset( $data[ 'title' ] ) ) {
				unset ( $data[ 'title' ] );
			}
			
			include_once( 'public/template/index.php' );
			
		}
		else {
			
			echo "View file not found";
			
		}
		
	}
	
}
/*
 * Do not edit this file
 */