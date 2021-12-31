<?php

namespace app\services\querybuilder\builders;

class SelectBuilder extends AbstractBuilder {
	
	/**
	 * SelectBuilder constructor.
	 *
	 * @param $tablename
	 * @param $columns
	 */
	public function __construct( $tablename, $columns = null ) {
		$this->tablename = $tablename;
		$this->select( $columns );
	}
	
	/**
	 * @param array $columns
	 *
	 * @return $this
	 */
	public function select( Array $columns ) {

		$this->addColumn( $columns );
		
		$columnCollection = "";
		$numberOfColumns = count( $columns );
		$counter = 1;
		
		foreach ( $columns as $key => $value ) {
			if ( is_string( $key ) ) {
				$columnCollection .= $key . " AS " . $value;
			}
			else {
				$columnCollection .= $value;
			}
			$columnCollection .= ( $counter != $numberOfColumns ) ? ", " : " ";
			$counter++;
		}
		
		$columns = $columnCollection;
		
		$this->query = "SELECT $columns FROM $this->tablename";
	
		return $this;
	}
	
	/**
	 * @param $table
	 * @param $selfColumn
	 * @param $refcolumn
	 *
	 * @return $this
	 */
	public function join( $table, $selfColumn, $refcolumn ) {
		
		$this->query .= " JOIN " . $table . " ON " . $selfColumn . " = " . $refcolumn . " ";
		return $this;
	}
	
	/**
	 * @return $this
	 */
	public function build() {
		
		if ( !empty( $this->where ) ) {
			$this->query .= $this->where;
		}
		
		if ( !empty( $this->order_by ) ) {
			$this->query .= $this->order_by;
		}
		
		if ( !empty( $this->limit ) ) {
			$this->query .= $this->limit;
		}
		
		return $this;
		
	}
	
}