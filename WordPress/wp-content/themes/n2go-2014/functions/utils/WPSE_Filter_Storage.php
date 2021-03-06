<?php

/**
 * Stores a value and calls any existing function with this value.
 */
class WPSE_Filter_Storage {
	/**
	 * Filled by __construct(). Used by __call().
	 *
	 * @type mixed Any type you need.
	 */
	private $values;

	/**
	 * Stores the values for later use.
	 *
	 * @param  mixed $values
	 */
	public function __construct( $values ) {
		$this->values = $values;
	}

	/**
	 * Catches all function calls except __construct().
	 *
	 * Be aware: Even if the function is called with just one string as an
	 * argument it will be sent as an array.
	 *
	 * @param  string $callback Function name
	 * @param  array $arguments
	 * @return mixed
	 * @throws InvalidArgumentException
	 */
	public function __call( $callback, $arguments ) {
		if ( is_callable( $callback ) ) {
			return call_user_func( $callback, $arguments, $this->values );
		}

		// Wrong function called.
		throw new InvalidArgumentException(
			sprintf( 'File: %1$s<br>Line %2$d<br>Not callable: %3$s',
				__FILE__, __LINE__, print_r( $callback, TRUE )
			)
		);
	}
}