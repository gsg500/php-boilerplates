<?php

namespace app\services;

use app\services\libraries\MessageType;

final class Message {
	
	private static $messages = [];
	
	public function __construct () {

		/*
		 * If session has messages add them to message array
		 */
		if ( ! empty( $_SESSION[ 'messages' ] ) ) {
			print_r($_SESSION['messages'] );
			$this->messages = $_SESSION[ 'messages' ];
		}
		
	}
	
	public static function createMessage ( $type, $message ) {
		
		/*
		 * Add new message to array
		 */
		self::$messages[] = [
				"type"    => $type,
				"message" => $message
		];
		
		/*
		 * Synchronize messages session
		 */
		$_SESSION[ 'messages' ] = self::$messages;
		
	}
	
	public static function createDatabaseError () {
		
		/*
		 * Create default message when something is going wrong in the database so the user does not see unexplaineble errors
		 */
		self::createMessage(
				MessageType::Error,
				"Er is iets mis gegaan bij het verwerken van de database."
		);
		
	}
	
	/**
	 * @return array
	 */
	public function getMessages () {
		
		return $this::$messages;
		
	}
	
	/**
	 * @return array
	 */
	public function getErrors () {
		
		$errors = [];
		
		foreach ( $this::$messages as $message ) {
			
			if ( $message[ 'type' ] == MessageType::Error ) {
				$errors[] = $message[ 'message' ];
			}
			
		}
		
		return $errors;
		
	}
	
	/**
	 * @return array
	 */
	public function getSuccess () {
		
		$success = [];
		
		foreach ( $this::$messages as $message ) {
			
			if ( $message[ 'type' ] == MessageType::Success ) {
				$success[] = $message[ 'message' ];
			}
			
		}
		
		return $success;
		
	}
	
	/**
	 * @return array
	 */
	public function getNotifications () {
		
		$notifications = [];
		
		foreach ( $this::$messages as $message ) {
			
			if ( $message[ 'type' ] == MessageType::Notification ) {
				$notifications[] = $message[ 'message' ];
			}
			
		}
		
		return $notifications;
		
	}
	
	public function clear () {
		
		unset( $_SESSION[ 'messages' ] );
		
	}
}