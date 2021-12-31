<?php

namespace app\services\querybuilder\builders;

use app\services\Database;

class InsertBuilder extends AbstractBuilder {
	
	public function __construct( $tablename ) {
		$this->tablename = $tablename;
		$this->query = "INSERT INTO $tablename ";
	}
	
	public function build() {
		
		$columns = implode(", ", $this->columns);
		$placeholders = implode(", ", $this->placeholders);
		
		$this->query .= " ( $columns ) VALUES ( $placeholders ) ";
		
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