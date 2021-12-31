<?php

namespace app\services;

use app\services\querybuilder\QueryBuilder;
use \PDO;

final class Database {
	
	private $host = DB_HOST;
	private $user = DB_USERNAME;
	private $pass = DB_PASSWORD;
	private $dbName = DB_NAME;
	private $driver = DB_CONNECTION;
	private $port = DB_PORT;
	
	private static $dbh;
	/** @var  \PDOStatement */
	private static $stmt;
	
	/**
	 * Database constructor.
	 */
	function __construct() {
		
		$dsn     = $this->driver . ":host=" . $this->host . ";port= " . $this->port . ";dbname=" . $this->dbName;
		$options = [
				PDO::ATTR_PERSISTENT => true,
				PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION
		];
		
		try {
			$this::$dbh = new PDO( $dsn, $this->user, $this->pass, $options );
		}
		catch ( \PDOException $e ) {
			$message = new Message();
			$message->createDatabaseError();
		}
		
	}
	
	/**
	 * @param $query
	 */
	public static function query( $query ) {
		
		self::$stmt = self::$dbh->prepare( $query );
	}
	
	/**
	 * @param      $param
	 * @param      $value
	 * @param null $type
	 */
	public static function bind( $param, $value, $type = null ) {
		
		if ( is_null( $type ) ) {
			switch ( true ) {
				case is_int( $value ) :
					$type = PDO::PARAM_INT;
					break;
				case is_bool( $value ) :
					$type = PDO::PARAM_BOOL;
					break;
				case is_null( $value ) :
					$type = PDO::PARAM_NULL;
					break;
				default:
					$type = PDO::PARAM_STR;
					break;
			}
		}
		self::$stmt->bindValue( $param, $value, $type );
	}
	
	/**
	 * @return array
	 * Return all records from query
	 */
	public static function all() {
		
		self::execute();
		return self::$stmt->fetchAll( PDO::FETCH_ASSOC );
	}
	
	/**
	 * @return mixed
	 * Return a single row
	 */
	public static function single() {
		self::execute();
		return self::$stmt->fetch( PDO::FETCH_ASSOC );
	}
	
	/**
	 * @return bool
	 * Execute query, if an error is present create a message
	 */
	public static function execute() {
		
		try {
			return self::$stmt->execute();
		}
		catch ( \PDOException $e ) {
			var_dump( $e );
			echo "<br />";
			self::$stmt->debugDumpParams();
			echo "<br />";
			Message::createDatabaseError();
			return false;
		}
		
	}
	
	/**
	 * @return int
	 * Count affected rows
	 */
	public static function affectedRows() {
		return self::$stmt->rowCount();
	}
	
	/**
	 * @return int
	 * Get last id
	 */
	public static function lastId() {
		return (int) self::$dbh->lastInsertId();
	}
	
	/**
	 * @param $tablename
	 * Start up QueryBuilder to create querys
	 * @return QueryBuilder
	 */
	public static function table( $tablename ) {
		return new QueryBuilder( $tablename );
	}
	
}