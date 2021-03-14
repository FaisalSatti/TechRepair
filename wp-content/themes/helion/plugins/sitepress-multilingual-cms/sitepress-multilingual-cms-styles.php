<?php
// Add plugin-specific colors and fonts to the custom CSS
if ( ! function_exists( 'helion_wpml_get_css' ) ) {
	add_filter( 'helion_filter_get_css', 'helion_wpml_get_css', 10, 2 );
	function helion_wpml_get_css( $css, $args ) {
		return $css;
	}
}

