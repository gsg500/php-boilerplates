<?php

namespace app\services\querybuilder\builders;

interface BuilderInterface {
	
	/**
	 * @param array $columns
	 * Add needed columns
	 * @return object
	 */
	public function addColumn( $columns );
	
	/**
	 * @param array $placeholders
	 * Add all placeholders
	 * @return object
	 */
	public function addPlaceholder( $placeholders );
	
	/**
	 * @param array $values
	 * Add all values
	 * @return object
	 */
	public function addValue( $values );
	
	/**
	 * @return String
	 */
	public function toString();
	
	/**
	 * @return object
	 */
	public function build();
	
	/**
	 * @return bool
	 */
	public function execute();
	
	
}