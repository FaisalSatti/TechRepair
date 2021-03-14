<?php
// Add plugin-specific colors and fonts to the custom CSS
if ( ! function_exists( 'helion_essential_addons_for_elementor_get_css' ) ) {
	add_filter( 'helion_filter_get_css', 'helion_essential_addons_for_elementor_get_css', 10, 2 );
	function helion_essential_addons_for_elementor_get_css( $css, $args ) {

		if ( isset( $css['fonts'] ) && isset( $args['fonts'] ) ) {
			$fonts         = $args['fonts'];
			$css['fonts'] .= <<<CSS
			
		.eael-advance-tabs .eael-tabs-nav > ul li {
            {$fonts['h5_font-family']}
        }

CSS;
		}

		if ( isset( $css['vars'] ) && isset( $args['vars'] ) ) {
			$vars = $args['vars'];

			$css['vars'] .= <<<CSS


CSS;
		}

		if ( isset( $css['colors'] ) && isset( $args['colors'] ) ) {
			$colors         = $args['colors'];
			$css['colors'] .= <<<CSS



CSS;
		}

		return $css;
	}
}

