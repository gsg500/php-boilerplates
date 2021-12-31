<?php

namespace app\services\querybuilder;

use app\services\querybuilder\builders\DeleteBuilder;
use app\services\querybuilder\builders\InsertBuilder;
use app\services\querybuilder\builders\SelectBuilder;
use app\services\querybuilder\builders\UpdateBuilder;

class QueryBuilder {
	
	private $tablename;
	
	public function __construct( $tablename ) {
		$this->tablename = $tablename;
	}
	
	public function select( $columns ) {
		return new SelectBuilder( $this->tablename, $columns );
	}
	
	public function insert() {
		return new InsertBuilder( $this->tablename );
	}
	
	public function update() {
		return new UpdateBuilder( $this->tablename );
	}
	
	public function delete() {
		return new DeleteBuilder( $this->tablename );
	}
	
}