<?php

namespace app\services\querybuilder\builders;

use app\services\Database;

class DeleteBuilder extends AbstractBuilder {
	
	private $column;
	private $placeholder;
	private $value;
	
	public function __construct( $tablename ) {
		$this->tablename = $tablename;
		$this->query = "DELETE FROM $tablename ";
	}
	
	public function build() {
		
		$this->where( $this->column, $this->placeholder );
		
		$this->query .= $this->where;
		
		return $this;
	}
	
	public function execute() {
		
		$this->build();
		
		Database::query( $this->toString() );
		Database::bind( $this->placeholder, $this->value );
		
		return Database::execute();
	}
	
	public function addColumn( $name ) {
		$this->column = $name;
		return $this;
	}
	
	public function addPlaceholder( $name ) {
		$this->placeholder = $name;
		return $this;
	}
	
	public function addValue( $value ) {
		$this->value = $value;
		return $this;
	}
}