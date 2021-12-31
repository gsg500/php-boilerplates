<?php

namespace app\services\querybuilder\builders;

class UpdateBuilder extends AbstractBuilder {
	
	public function __construct( $tablename ) {
		$this->tablename = $tablename;
		$this->query = "UPDATE $tablename SET ";
	}
	
	/**
	 * @return $this
	 */
	public function build() {
		
		$column = array_values( $this->columns );
		$placeholder = array_values( $this->placeholders );
		
		$numberOfColumns = count( $column );
		
		for ( $i = 0; $i < $numberOfColumns; $i++ ) {
		
			$this->query .= "`$column[$i]` = $placeholder[$i]";
			$this->query .= ( $i != $numberOfColumns - 1 ) ? ", " : " ";
		
		}
		
		if ( !empty( $this->where ) ) {
			$this->query .= $this->where;
		}
		
		return $this;
		
	}
}