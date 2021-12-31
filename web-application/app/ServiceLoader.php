<?php

namespace app;

class ServiceLoader {
	
	protected $values = [];
	
	/**
	 * Whether the services should be shared amoung calls
	 * @var array
	 */
	protected $shared = [];
	
	/**
	 * The instances of shared services
	 * @var array
	 */
	protected $instances = [];
	
	/**
	 * Contains an array of configurators for each service
	 * @see configure()
	 * @var array
	 */
	protected $configurators = [];
	
	public function configure ( $key, $configurator ) {
		
		if ( ! is_callable( $configurator ) ) {
			throw new \InvalidArgumentException( 'The configurator should be a callable' );
		}
		$this->configurators[ $key ][] = $configurator;
	}
	
	public function set ( $key, $value, $shared = false ) {
		
		$this->values[ $key ] = $value;
		$this->shared[ $key ] = $shared;
	}
	
	public function get ( $key ) {
		
		if ( ! isset( $this->values[ $key ] ) ) {
			throw new \InvalidArgumentException( sprintf(
					"Value %s has not been set",
					$key
			) );
		}
		
		$value = $this->values[ $key ];
		
		// If value is a service
		if ( is_callable( $value ) ) {
			// If service is shared and already instanciated, return instance
			if ( $this->shared[ $key ] && isset( $this->instances[ $key ] ) ) {
				return $this->instances[ $key ];
			}
			
			// Call the closure and create the instance
			$instance = $value( $this );
			
			// If any configurators are set, apply each to the instance
			if ( isset( $this->configurators[ $key ] ) ) {
				foreach ( $this->configurators[ $key ] as $configurator ) {
					$instance = $configurator( $instance, $this );
				}
			}
			
			// Store shared services
			if ( $this->shared[ $key ] ) {
				$this->instances[ $key ] = $instance;
			}
			
			return $instance;
			
			// If value is a parameter
		}
		else {
			return $value;
		}
	}
	
	/**
	 * Set wheter the service should be shared
	 *
	 * @param string  $key
	 * @param boolean $shared
	 */
	public function setShared ( $key, $shared ) {
		
		$this->shared[ $key ] = $shared;
	}
	
	/**
	 * Set an array of parameters
	 *
	 * @param array $array
	 */
	public function setParameterArray ( array $array ) {
		
		foreach ( $array as $key => $value ) {
			$this->values[ $key ] = $value;
			$this->shared[ $key ] = false;
		}
	}
	
}