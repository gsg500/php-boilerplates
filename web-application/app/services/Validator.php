<?php

namespace app\services;

use app\services\libraries\MessageType;
use \PDO;
use app\services\libraries\ValueType;

class Validator {
	
	/** @var PDO  */
	private $db;
	/** @var Message  */
	private $message;
	private $tablename;
	private $validationRules;
	private $validationErrors;
	
	/**
	 * Validator constructor.
	 *
	 * @param PDO     $db
	 * @param Message $message
	 * @param         $tablename
	 */
	public function __construct ( PDO $db, Message $message, $tablename ) {
		
		$this->db        = $db;
		$this->tablename = $tablename;
		$this->message   = $message;
		
	}
	
	/**
	 * @param $rule
	 */
	public function setValidationRule ( $rule ) {
		
		$this->validationRules[] = $rule;
		
	}
	
	/**
	 * @param $error
	 */
	private function setValidationError ( $error ) {
		
		$this->validationErrors[] = $error;
		
	}
	
	/**
	 * @return bool
	 */
	public function validate () {
		
		foreach ( $this->validationRules as $rule ) {
			
			switch ( $rule[ 'type' ] ) {
				
				case ValueType::String :
					$valid = filter_input( INPUT_POST, $rule[ 'name' ], FILTER_SANITIZE_STRING );
					break;
				
				case ValueType::Boolean :
					$valid = filter_input( INPUT_POST, $rule[ 'name' ], FILTER_VALIDATE_BOOLEAN );
					break;
				
				case ValueType::Email :
					$valid = filter_input( INPUT_POST, $rule[ 'name' ], FILTER_VALIDATE_EMAIL );
					break;
				
				case ValueType::Integer :
					$valid = filter_input( INPUT_POST, $rule[ 'name' ], FILTER_VALIDATE_INT );
					break;
				
				case ValueType::NumberInt :
					$valid = filter_input( INPUT_POST, $rule[ 'name' ], FILTER_SANITIZE_NUMBER_INT );
					break;
				
				case ValueType::Url :
					$valid = filter_input( INPUT_POST, $rule[ 'name' ], FILTER_VALIDATE_URL );
					break;
				
				case ValueType::DateTime_local :
					$valid = filter_input( INPUT_POST, $rule[ 'name' ], FILTER_SANITIZE_STRING );
					break;
				
				case ValueType::Date :
					$valid = filter_input( INPUT_POST, $rule[ 'name' ], FILTER_SANITIZE_STRING );
					break;
				
			}
			
			if ( $valid ) {
				
				if ( $rule[ 'name' ] == ValueType::Date && isset( $rule[ 'required' ] ) && $rule[ 'required' ] ) {
					
					$date = \DateTime::createFromFormat( "Y-m-d", $valid );
					
					if ( ! empty ( \DateTime::getLastErrors()[ 'errors' ] ) ) {
						$this->setValidationError( [
								'errorType'    => MessageType::Error,
								'errorContent' => "Er is geen juiste datum ingevoerd"
						] );
					}
					
				}
				
				if ( isset( $rule[ 'dateTimeLocal' ] ) ) {
					
					$date = \DateTime::createFromFormat( "Y-m-d\TH:i", $valid );
					
					if ( ! empty( \DateTime::getLastErrors()[ 'errors' ] ) ) {
						$this->setValidationError( [
								'errorType'    => MessageType::Error,
								'errorContent' => "Bij veld " . $rule[ 'name' ] . " Is geen geldige datum en tijd gegeven"
						] );
					}
					else {
						$_POST[ $rule[ 'name' ] ] = $date->format( 'Y-m-d H:i:s' );
					}
				}
				
				if ( isset( $rule[ 'mustBeSelected' ] ) && $rule[ 'mustBeSelected' ] == true ) {
					if ( $valid == 0 ) {
						$this->setValidationError( [
								'errorType'    => MessageType::Error,
								'errorContent' => 'Er moet een ' . $rule[ 'name' ] . ' worden ingevuld'
						] );
					}
				}
				
				if ( isset( $rule[ 'required' ] ) && $rule[ 'required' ] == true ) {
					if ( strlen( trim( $valid ) ) == 0 ) {
						$this->setValidationError( [
								'errorType'    => MessageType::Error,
								'errorContent' => "Het veld met de naam " . $rule[ 'name' ] . " moet worden ingevuld."
						] );
					}
				}
				
				if ( isset ( $rule[ 'checkIfExists' ] ) ) {
					if ( $this->checkIfExists( $rule, $valid ) ) {
						$this->setValidationError( [
								'errorType'    => MessageType::Error,
								'errorContent' => 'Er is al een waarde met deze ' . $rule[ 'name' ] . '.'
						] );
					}
				}
				
				if ( isset ( $rule[ 'maxLength' ] ) ) {
					if ( strlen( $valid ) > $rule[ 'maxLength' ] ) {
						$this->setValidationError( [
								'errorType'    => MessageType::Error,
								'errorContent' => $rule[ 'name' ] . " Mag maximaal " . $rule[ 'maxLength' ] . " aantal tekens bevatten"
						] );
					}
				}
				
				if ( isset ( $rule[ 'minLength' ] ) ) {
					if ( strlen( $valid ) < $rule[ 'minLength' ] ) {
						$this->setValidationError( [
								'errorType'    => MessageType::Error,
								'errorContent' => $rule[ 'name' ] . " moet minimaal " . $rule[ 'minLength' ] . " aantal tekens bevatten"
						] );
					}
				}
				
			}
			else {
				$this->setValidationError( [
						'errorType'    => MessageType::Error,
						'errorContent' => "Er is geen geldige waarde voor: " . $rule[ 'name' ] . " gegeven."
				] );
			}
			
		}
		
		if ( count( $this->validationErrors ) > 0 ) {
			$this->createMessages();
			return false;
		}
		else {
			return true;
		}
		
	}
	
	private function createMessages () {
		
		foreach ( $this->validationErrors as $error ) {
			
			$this->message->createMessage( $error[ 'errorType' ], $error[ 'errorContent' ] );
			
		}
		
	}
	
	/**
	 * @param $rule
	 * @param $value
	 *
	 * @return bool
	 */
	private function checkIfExists ( $rule, $value ) {
		
		$sql       = "SELECT " . $rule[ 'checkIfExists' ] . " FROM " . $this->tablename . " WHERE " . $rule[ 'checkIfExists' ] . " = :value";
		$statement = $this->db->prepare( $sql );
		
		$statement->bindValue( ":value", $value, PDO::PARAM_STR );
		
		$result = $statement->execute();
		
		if ( $result ) {
			
			if ( $statement->rowCount() > 0 ) {
				
				return true;
				
			}
			
		}
		
		return false;
		
	}
	
}