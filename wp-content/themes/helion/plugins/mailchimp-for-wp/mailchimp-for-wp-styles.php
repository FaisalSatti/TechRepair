<?php
// Add plugin-specific colors and fonts to the custom CSS
if ( ! function_exists( 'helion_mailchimp_get_css' ) ) {
	add_filter( 'helion_filter_get_css', 'helion_mailchimp_get_css', 10, 2 );
	function helion_mailchimp_get_css( $css, $args ) {

		if ( isset( $css['fonts'] ) && isset( $args['fonts'] ) ) {
			$fonts         = $args['fonts'];
			$css['fonts'] .= <<<CSS
			
form.mc4wp-form .mc4wp-form-fields input[type="submit"] {
	{$fonts['button_font-family']}
	{$fonts['button_font-size']}
	{$fonts['button_font-weight']}
	{$fonts['button_font-style']}
	{$fonts['button_line-height']}
	{$fonts['button_text-decoration']}
	{$fonts['button_text-transform']}
	{$fonts['button_letter-spacing']}
}

form.mc4wp-form .mc4wp-form-fields input[type="email"] {
	{$fonts['h5_font-family']}
}

CSS;
		}

		if ( isset( $css['vars'] ) && isset( $args['vars'] ) ) {
			$vars = $args['vars'];

			$css['vars'] .= <<<CSS

form.mc4wp-form .mc4wp-alert,
form.mc4wp-form .mc4wp-form-fields input[type="submit"] {
	-webkit-border-radius: {$vars['rad']};
	    -ms-border-radius: {$vars['rad']};
			border-radius: {$vars['rad']};
}
form.mc4wp-form .mc4wp-form-fields  input[type="checkbox"] + label:before {
	-webkit-border-radius: {$vars['rad50']};
	    -ms-border-radius: {$vars['rad50']};
			border-radius: {$vars['rad50']};
}


CSS;
		}

		if ( isset( $css['colors'] ) && isset( $args['colors'] ) ) {
			$colors         = $args['colors'];
			$css['colors'] .= <<<CSS

form.mc4wp-form .mc4wp-alert {
	background-color: {$colors['bg_color']};
	border-color: {$colors['text_link']};
	color: {$colors['text']};
}
form.mc4wp-form .mc4wp-alert a {
	color: {$colors['text_dark']} !important;	
}
form.mc4wp-form .mc4wp-form-fields input[type="email"] {
    border-color: transparent;
}
form.mc4wp-form .mc4wp-form-fields input[type="email"]::placeholder {
    color: {$colors['input_dark']};	
}
form.mc4wp-form .mc4wp-form-fields button {
	color: {$colors['text_light']} !important;
    background-color: transparent !important;
}
form.mc4wp-form .mc4wp-form-fields button[disabled]:before,
form.mc4wp-form .mc4wp-form-fields button[disabled]:hover:before {
       color: {$colors['text_light']};	 
}
form.mc4wp-form .mc4wp-form-fields button:before {
       color: {$colors['text_dark']};
}
form.mc4wp-form .mc4wp-form-fields button:hover:before {
       color: {$colors['text_link']};
}

form.mc4wp-form input[type="checkbox"] + label,
form.mc4wp-form input[type="checkbox"]:checked + label {
	color: {$colors['text_light']}
}

form.mc4wp-form .mc4wp-form-fields input[type="checkbox"] + label a {
   	color: {$colors['text_dark']}; 
}
form.mc4wp-form input[type="checkbox"]:checked + label a {
	color: {$colors['text_dark']}; 
}

form.mc4wp-form .mc4wp-form-fields input[type="checkbox"] + label:before {
	border-color: {$colors['input_bd_color']};
    background-color: {$colors['input_bg_color']};
}
form.mc4wp-form .mc4wp-form-fields input[type="checkbox"]:checked + label:before {
    color: {$colors['input_dark']};	
	border-color: {$colors['input_bd_hover']};
    background-color: {$colors['input_bg_hover']};
}



CSS;
		}

		return $css;
	}
}

