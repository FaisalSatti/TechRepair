<?php
/**
 * Theme storage manipulations
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) {
	exit; }

// Get theme variable
if ( ! function_exists( 'helion_storage_get' ) ) {
	function helion_storage_get( $var_name, $default = '' ) {
		global $HELION_STORAGE;
		return isset( $HELION_STORAGE[ $var_name ] ) ? $HELION_STORAGE[ $var_name ] : $default;
	}
}

// Set theme variable
if ( ! function_exists( 'helion_storage_set' ) ) {
	function helion_storage_set( $var_name, $value ) {
		global $HELION_STORAGE;
		$HELION_STORAGE[ $var_name ] = $value;
	}
}

// Check if theme variable is empty
if ( ! function_exists( 'helion_storage_empty' ) ) {
	function helion_storage_empty( $var_name, $key = '', $key2 = '' ) {
		global $HELION_STORAGE;
		if ( ! empty( $key ) && ! empty( $key2 ) ) {
			return empty( $HELION_STORAGE[ $var_name ][ $key ][ $key2 ] );
		} elseif ( ! empty( $key ) ) {
			return empty( $HELION_STORAGE[ $var_name ][ $key ] );
		} else {
			return empty( $HELION_STORAGE[ $var_name ] );
		}
	}
}

// Check if theme variable is set
if ( ! function_exists( 'helion_storage_isset' ) ) {
	function helion_storage_isset( $var_name, $key = '', $key2 = '' ) {
		global $HELION_STORAGE;
		if ( ! empty( $key ) && ! empty( $key2 ) ) {
			return isset( $HELION_STORAGE[ $var_name ][ $key ][ $key2 ] );
		} elseif ( ! empty( $key ) ) {
			return isset( $HELION_STORAGE[ $var_name ][ $key ] );
		} else {
			return isset( $HELION_STORAGE[ $var_name ] );
		}
	}
}

// Inc/Dec theme variable with specified value
if ( ! function_exists( 'helion_storage_inc' ) ) {
	function helion_storage_inc( $var_name, $value = 1 ) {
		global $HELION_STORAGE;
		if ( empty( $HELION_STORAGE[ $var_name ] ) ) {
			$HELION_STORAGE[ $var_name ] = 0;
		}
		$HELION_STORAGE[ $var_name ] += $value;
	}
}

// Concatenate theme variable with specified value
if ( ! function_exists( 'helion_storage_concat' ) ) {
	function helion_storage_concat( $var_name, $value ) {
		global $HELION_STORAGE;
		if ( empty( $HELION_STORAGE[ $var_name ] ) ) {
			$HELION_STORAGE[ $var_name ] = '';
		}
		$HELION_STORAGE[ $var_name ] .= $value;
	}
}

// Get array (one or two dim) element
if ( ! function_exists( 'helion_storage_get_array' ) ) {
	function helion_storage_get_array( $var_name, $key, $key2 = '', $default = '' ) {
		global $HELION_STORAGE;
		if ( empty( $key2 ) ) {
			return ! empty( $var_name ) && ! empty( $key ) && isset( $HELION_STORAGE[ $var_name ][ $key ] ) ? $HELION_STORAGE[ $var_name ][ $key ] : $default;
		} else {
			return ! empty( $var_name ) && ! empty( $key ) && isset( $HELION_STORAGE[ $var_name ][ $key ][ $key2 ] ) ? $HELION_STORAGE[ $var_name ][ $key ][ $key2 ] : $default;
		}
	}
}

// Set array element
if ( ! function_exists( 'helion_storage_set_array' ) ) {
	function helion_storage_set_array( $var_name, $key, $value ) {
		global $HELION_STORAGE;
		if ( ! isset( $HELION_STORAGE[ $var_name ] ) ) {
			$HELION_STORAGE[ $var_name ] = array();
		}
		if ( '' === $key ) {
			$HELION_STORAGE[ $var_name ][] = $value;
		} else {
			$HELION_STORAGE[ $var_name ][ $key ] = $value;
		}
	}
}

// Set two-dim array element
if ( ! function_exists( 'helion_storage_set_array2' ) ) {
	function helion_storage_set_array2( $var_name, $key, $key2, $value ) {
		global $HELION_STORAGE;
		if ( ! isset( $HELION_STORAGE[ $var_name ] ) ) {
			$HELION_STORAGE[ $var_name ] = array();
		}
		if ( ! isset( $HELION_STORAGE[ $var_name ][ $key ] ) ) {
			$HELION_STORAGE[ $var_name ][ $key ] = array();
		}
		if ( '' === $key2 ) {
			$HELION_STORAGE[ $var_name ][ $key ][] = $value;
		} else {
			$HELION_STORAGE[ $var_name ][ $key ][ $key2 ] = $value;
		}
	}
}

// Merge array elements
if ( ! function_exists( 'helion_storage_merge_array' ) ) {
	function helion_storage_merge_array( $var_name, $key, $value ) {
		global $HELION_STORAGE;
		if ( ! isset( $HELION_STORAGE[ $var_name ] ) ) {
			$HELION_STORAGE[ $var_name ] = array();
		}
		if ( '' === $key ) {
			$HELION_STORAGE[ $var_name ] = array_merge( $HELION_STORAGE[ $var_name ], $value );
		} else {
			$HELION_STORAGE[ $var_name ][ $key ] = array_merge( $HELION_STORAGE[ $var_name ][ $key ], $value );
		}
	}
}

// Add array element after the key
if ( ! function_exists( 'helion_storage_set_array_after' ) ) {
	function helion_storage_set_array_after( $var_name, $after, $key, $value = '' ) {
		global $HELION_STORAGE;
		if ( ! isset( $HELION_STORAGE[ $var_name ] ) ) {
			$HELION_STORAGE[ $var_name ] = array();
		}
		if ( is_array( $key ) ) {
			helion_array_insert_after( $HELION_STORAGE[ $var_name ], $after, $key );
		} else {
			helion_array_insert_after( $HELION_STORAGE[ $var_name ], $after, array( $key => $value ) );
		}
	}
}

// Add array element before the key
if ( ! function_exists( 'helion_storage_set_array_before' ) ) {
	function helion_storage_set_array_before( $var_name, $before, $key, $value = '' ) {
		global $HELION_STORAGE;
		if ( ! isset( $HELION_STORAGE[ $var_name ] ) ) {
			$HELION_STORAGE[ $var_name ] = array();
		}
		if ( is_array( $key ) ) {
			helion_array_insert_before( $HELION_STORAGE[ $var_name ], $before, $key );
		} else {
			helion_array_insert_before( $HELION_STORAGE[ $var_name ], $before, array( $key => $value ) );
		}
	}
}

// Push element into array
if ( ! function_exists( 'helion_storage_push_array' ) ) {
	function helion_storage_push_array( $var_name, $key, $value ) {
		global $HELION_STORAGE;
		if ( ! isset( $HELION_STORAGE[ $var_name ] ) ) {
			$HELION_STORAGE[ $var_name ] = array();
		}
		if ( '' === $key ) {
			array_push( $HELION_STORAGE[ $var_name ], $value );
		} else {
			if ( ! isset( $HELION_STORAGE[ $var_name ][ $key ] ) ) {
				$HELION_STORAGE[ $var_name ][ $key ] = array();
			}
			array_push( $HELION_STORAGE[ $var_name ][ $key ], $value );
		}
	}
}

// Pop element from array
if ( ! function_exists( 'helion_storage_pop_array' ) ) {
	function helion_storage_pop_array( $var_name, $key = '', $defa = '' ) {
		global $HELION_STORAGE;
		$rez = $defa;
		if ( '' === $key ) {
			if ( isset( $HELION_STORAGE[ $var_name ] ) && is_array( $HELION_STORAGE[ $var_name ] ) && count( $HELION_STORAGE[ $var_name ] ) > 0 ) {
				$rez = array_pop( $HELION_STORAGE[ $var_name ] );
			}
		} else {
			if ( isset( $HELION_STORAGE[ $var_name ][ $key ] ) && is_array( $HELION_STORAGE[ $var_name ][ $key ] ) && count( $HELION_STORAGE[ $var_name ][ $key ] ) > 0 ) {
				$rez = array_pop( $HELION_STORAGE[ $var_name ][ $key ] );
			}
		}
		return $rez;
	}
}

// Inc/Dec array element with specified value
if ( ! function_exists( 'helion_storage_inc_array' ) ) {
	function helion_storage_inc_array( $var_name, $key, $value = 1 ) {
		global $HELION_STORAGE;
		if ( ! isset( $HELION_STORAGE[ $var_name ] ) ) {
			$HELION_STORAGE[ $var_name ] = array();
		}
		if ( empty( $HELION_STORAGE[ $var_name ][ $key ] ) ) {
			$HELION_STORAGE[ $var_name ][ $key ] = 0;
		}
		$HELION_STORAGE[ $var_name ][ $key ] += $value;
	}
}

// Concatenate array element with specified value
if ( ! function_exists( 'helion_storage_concat_array' ) ) {
	function helion_storage_concat_array( $var_name, $key, $value ) {
		global $HELION_STORAGE;
		if ( ! isset( $HELION_STORAGE[ $var_name ] ) ) {
			$HELION_STORAGE[ $var_name ] = array();
		}
		if ( empty( $HELION_STORAGE[ $var_name ][ $key ] ) ) {
			$HELION_STORAGE[ $var_name ][ $key ] = '';
		}
		$HELION_STORAGE[ $var_name ][ $key ] .= $value;
	}
}

// Call object's method
if ( ! function_exists( 'helion_storage_call_obj_method' ) ) {
	function helion_storage_call_obj_method( $var_name, $method, $param = null ) {
		global $HELION_STORAGE;
		if ( null === $param ) {
			return ! empty( $var_name ) && ! empty( $method ) && isset( $HELION_STORAGE[ $var_name ] ) ? $HELION_STORAGE[ $var_name ]->$method() : '';
		} else {
			return ! empty( $var_name ) && ! empty( $method ) && isset( $HELION_STORAGE[ $var_name ] ) ? $HELION_STORAGE[ $var_name ]->$method( $param ) : '';
		}
	}
}

// Get object's property
if ( ! function_exists( 'helion_storage_get_obj_property' ) ) {
	function helion_storage_get_obj_property( $var_name, $prop, $default = '' ) {
		global $HELION_STORAGE;
		return ! empty( $var_name ) && ! empty( $prop ) && isset( $HELION_STORAGE[ $var_name ]->$prop ) ? $HELION_STORAGE[ $var_name ]->$prop : $default;
	}
}
