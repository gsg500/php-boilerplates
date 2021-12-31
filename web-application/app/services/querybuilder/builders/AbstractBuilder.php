<?php

namespace app\services\querybuilder\builders;

use app\services\Database;

abstract class AbstractBuilder implements BuilderInterface {
	
	/** @var array Collection of columns */
	protected $columns = [];
	/** @var array Collection of placeholders */
	protected $placeholders = [];
	/** @var array Collection of values to bind */
	protected $values = [];
	
	/** @var string with the query */
	protected $query;
	/** @var  string with the tablename */
	protected $tablename;
	
	/** @var  string with where clause for update builder */
	protected $where;
	protected $order_by;
	protected $limit;
	
	/**
	 * @param array $columns
	 * Add needed columns
	 *
	 * @return $this
	 */
	public function addColumn( $columns ) {
		
		$this->columns = $columns;
		
		return $this;
	}
	
	/**
	 * @param array $placeholders
	 * Add all placeholders
	 *
	 * @return $this
	 */
	public function addPlaceholder( $placeholders ) {
		
		$this->placeholders = $placeholders;
		
		return $this;
	}
	
	/**
	 * @param array $values
	 * Add all values
	 *
	 * @return $this
	 */
	public function addValue( $values ) {
		
		$this->values = $values;
		
		return $this;
	}
	
	/**
	 * @return String
	 * Return the query string
	 */
	public function toString() {
		
		return $this->query;
	}
	
	/**
	 * @param      $column
	 * @param      $value
	 * @param null $op
	 *
	 * @return $this
	 */
	public function where( $column, $value, $op = null ) {
		
		$whereBuilder = new WhereBuilder();
		
		$this->where  .= $whereBuilder->start();
		$this->where .= $whereBuilder->where( $column, $value, $op );
		
		return $this;
	}
	
	/**
	 * @param      $column
	 * @param      $value
	 * @param null $op
	 *
	 * @return $this
	 */
	public function and_where( $column, $value, $op = null ) {
		
		$whereBuilder = new WhereBuilder();
		
		$this->where .= " AND ";
		$this->where .= $whereBuilder->where( $column, $value, $op );
		
		return $this;
	}
	
	/**
	 * @param      $column
	 * @param      $value
	 * @param null $op
	 *
	 * @return $this
	 */
	public function or_where( $column, $value, $op = null ) {
		
		$whereBuilder = new WhereBuilder();

		$this->where .= " OR ";
		$this->where .= $whereBuilder->where( $column, $value, $op );
		
		return $this;
	}
	
	/**
	 * @param      $column
	 * @param null $direction
	 *
	 * @return $this
	 */
	public function order_by( $column, $direction = null ) {
		
		$this->order_by = " ORDER BY {$column} {$direction} ";
		
		return $this;
	}
	
	/**
	 * @param $limit
	 *
	 * @return $this
	 */
	public function limit( $limit, $offset = null ) {
		
		$this->limit = ( ! is_null( $offset ) ) ? " LIMIT $limit, $offset " : " LIMIT $limit ";
		
		return $this;
	}
	
	/**
	 * @return bool
	 */
	public function execute() {
		
		$this->build();
		
		Database::query( $this->query );
		
		$placeholder = array_values( $this->placeholders );
		$values      = array_values( $this->values );
		
		$numberOfValues = count( $values );
		
		for ( $i = 0; $i < $numberOfValues; $i ++ ) {
			if ( $placeholder[ $i ]{0} == ":" ) {
				Database::bind( $placeholder[ $i ], $values[ $i ] );
			}
		}
		
		return Database::execute();
	}
	
	/**
	 * @return object
	 */
	public abstract function build();
	
}