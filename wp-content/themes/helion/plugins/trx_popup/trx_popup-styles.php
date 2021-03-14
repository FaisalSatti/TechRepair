<?php
// Add plugin-specific colors and fonts to the custom CSS
if ( ! function_exists( 'helion_trx_popup_get_css' ) ) {
    add_filter( 'helion_filter_get_css', 'helion_trx_popup_get_css', 10, 2 );
    function helion_trx_popup_get_css( $css, $args ) {

        if ( isset( $css['fonts'] ) && isset( $args['fonts'] ) ) {
            $fonts         = $args['fonts'];
            $css['fonts'] .= <<<CSS

CSS;
        }

        if ( isset( $css['colors'] ) && isset( $args['colors'] ) ) {
            $colors         = $args['colors'];
            $css['colors'] .= <<<CSS

.trx_popup_close {
}

.trx_popup_close::after,
.trx_popup_close::before {
	background: {$colors['inverse_link']};
}

.trx_popup_container {
}

.trx_popup_subtitle {
	color: {$colors['inverse_link']};
}

.trx_popup_title {
	color: {$colors['inverse_link']};
}

.trx_popup_button {
}

.trx_popup_button:hover {
}

CSS;
        }

        return $css;
    }
}