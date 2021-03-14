<?php
/**
 * Setup options for the Front Page
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0.31
 */


// Theme init priorities:
// 1 - register filters, that add/remove lists items for the Theme Options
if ( ! function_exists( 'helion_front_page_setup1' ) ) {
	add_action( 'after_setup_theme', 'helion_front_page_setup1', 1 );
	function helion_front_page_setup1() {
		add_filter( 'helion_filter_list_sidebars', 'helion_front_page_sidebars' );
	}
}


// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'helion_front_page_setup3' ) ) {
	add_action( 'after_setup_theme', 'helion_front_page_setup3', 3 );
	function helion_front_page_setup3() {

		helion_storage_set_array_before(
			'options', 'blog', apply_filters(
				'helion_filter_front_page_options', array(

					// 'Front Page Sections'
					'front_page'              => array(
						'title'      => esc_html__( 'Front Page Builder', 'helion' ),
						'desc'       => wp_kses_data( __( 'More fine tuning component display Front Page (view and menu position, presence and position of the sidebar, header and footer, etc.) you can produce in the section "Page Options" when editing a page, selected as Front Page', 'helion' ) ),
						'priority'   => 65,
						'expand_url' => esc_url( home_url( '/' ) ),
						'icon'       => 'icon-editor-table',
						'type'       => 'panel',
					),

					// Front Page Sections - General
					'front_page_general'      => array(
						'title'    => esc_html__( 'General', 'helion' ),
						'desc'     => '',
						'priority' => 10,
						'type'     => 'section',
					),
					'front_page_general_info' => array(
						'title' => esc_html__( 'General settings', 'helion' ),
						'desc'  => '',
						'type'  => 'info',
					),
					'front_page_enabled'      => array(
						'title' => esc_html__( 'Enable Front Page builder', 'helion' ),
						'desc'  => wp_kses_data( __( 'If Front Page Builder is off - native page content will be shown', 'helion' ) ),
						'std'   => HELION_THEME_FREE ? 1 : 0,
						'type'  => 'switch',
					),
					'body_style_front'        => array(
						'title'   => esc_html__( 'Body style', 'helion' ),
						'desc'    => wp_kses_data( __( 'Select width of the body content of the front page', 'helion' ) ),
						'refresh' => false,
						'std'     => HELION_THEME_FREE ? 'fullscreen' : 'wide',
						'options' => helion_get_list_body_styles( true, true ),
						'type'    => 'select',
					),
					'remove_margins_front'    => array(
						'title'   => esc_html__( 'Remove margins', 'helion' ),
						'desc'    => wp_kses_data( __( 'Remove margins above and below the content area on the front page', 'helion' ) ),
						'refresh' => false,
						'std'     => 1,
						'type'    => 'switch',
					),
					'front_page_sections'     => array(
						'title'      => esc_html__( 'Sections order', 'helion' ),
						'desc'       => wp_kses( __( 'Drag and drop sections below to set up their order on the Front Page. You can also enable / disable any section.', 'helion' ),'helion_kses_content' ),
						'dependency' => array(
							'front_page_enabled' => array( 1 ),
						),
						'dir'        => 'vertical',
						'sortable'   => true,
						'std'        => '',
						'options'    => array(),
						'type'       => 'checklist',
					),
					'front_page_bg_image'     => array(
						'title'      => esc_html__( 'Background image', 'helion' ),
						'desc'       => wp_kses_data( __( 'Select or upload background image for whole Front page', 'helion' ) ),
						'refresh'    => false,
						'dependency' => array(
							'front_page_enabled' => array( 1 ),
						),
						'std'        => HELION_THEME_FREE ? helion_get_file_url( 'front-page/images/bg.jpg' ) : '',
						'type'       => 'image',
					),
				)
			)
		);

		helion_storage_set_array_before(
			'options', 'blog', array(
				'front_page_end' => array(
					'type' => 'panel_end',
				),
			)
		);

	}
}



// Add section 'Title' to the Front Page option
if ( ! function_exists( 'helion_front_page_options_title' ) ) {
	add_filter( 'helion_filter_front_page_options', 'helion_front_page_options_title' );
	function helion_front_page_options_title( $options ) {

		$options['front_page_sections']['std']    .= ( ! empty( $options['front_page_sections']['std'] ) ? '|' : '' ) . 'title=1';
		$options['front_page_sections']['options'] = array_merge(
			$options['front_page_sections']['options'],
			array(
				'title' => esc_html__( 'Big title', 'helion' ),
			)
		);
		$options                                   = array_merge(
			$options, array(

				// Front Page Sections - Title
				'front_page_title'                 => array(
					'title'    => esc_html__( 'Title', 'helion' ),
					'desc'     => '',
					'priority' => 20,
					'type'     => 'section',
				),
				'front_page_title_slider_info'     => array(
					'title' => esc_html__( 'Slider', 'helion' ),
					'desc'  => '',
					'type'  => 'info',
				),
				'front_page_title_shortcode'       => array(
					'title'     => esc_html__( 'Slider Shortcode', 'helion' ),
					'desc'      => wp_kses_data( __( 'Paste a shortcode generated by any slider plugin. The slider will be used instead of the section title, description and buttons.', 'helion' ) ),
					'translate' => true,
					'sanitize'  => 'wp_kses_post',
					'std'       => '',
					'type'      => 'text',
				),
				'front_page_title_layout_info'     => array(
					'title'      => esc_html__( 'Layout', 'helion' ),
					'desc'       => '',
					'dependency' => array(
						'front_page_title_shortcode' => array( 'is_empty' ),
					),
					'type'       => 'info',
				),
				'front_page_title_fullheight'      => array(
					'title'      => esc_html__( 'Full height', 'helion' ),
					'desc'       => wp_kses_data( __( 'Stretch this section to the window height', 'helion' ) ),
					'std'        => 1,
					'refresh'    => false,
					'dependency' => array(
						'front_page_title_shortcode' => array( 'is_empty' ),
					),
					'type'       => 'switch',
				),
				'front_page_title_stack'           => array(
					'title'      => esc_html__( 'Stack this section', 'helion' ),
					'desc'       => wp_kses_data( __( 'Add the behavior of "a stack" for this section to fix it when you scroll to the top of the screen.', 'helion' ) ),
					'std'        => 0,
					'refresh'    => false,
					'dependency' => array(
						'front_page_title_fullheight' => array( 1 ),
					),
					'type'       => 'switch',
				),
				'front_page_title_paddings'        => array(
					'title'      => esc_html__( 'Paddings', 'helion' ),
					'desc'       => wp_kses_data( __( 'Select paddings inside this section', 'helion' ) ),
					'std'        => 'large',
					'options'    => helion_get_list_paddings(),
					'refresh'    => false,
					'dependency' => array(
						'front_page_title_shortcode' => array( 'is_empty' ),
					),
					'type'       => 'radio',
				),
				'front_page_title_heading_info'    => array(
					'title'      => esc_html__( 'Title', 'helion' ),
					'desc'       => '',
					'dependency' => array(
						'front_page_title_shortcode' => array( 'is_empty' ),
					),
					'type'       => 'info',
				),
				'front_page_title_caption'         => array(
					'title'      => esc_html__( 'Section title', 'helion' ),
					'desc'       => '',
					'translate'  => true,
					'refresh'    => false,
					'std'        => wp_kses_data( __( 'Section with Big title', 'helion' ) ),
					'sanitize'   => 'wp_kses_post',
					'dependency' => array(
						'front_page_title_shortcode' => array( 'is_empty' ),
					),
					'type'       => 'text',
				),
				'front_page_title_description'     => array(
					'title'      => esc_html__( 'Description', 'helion' ),
					'desc'       => wp_kses_data( __( "Short description after the section's title", 'helion' ) ),
					'translate'  => true,
					'refresh'    => false,
					'std'        => wp_kses_data( __( 'This text can be changed in the section "Title"', 'helion' ) ),
					'sanitize'   => 'wp_kses_post',
					'dependency' => array(
						'front_page_title_shortcode' => array( 'is_empty' ),
					),
					'type'       => 'textarea',
				),
				'front_page_title_buttons_info'    => array(
					'title'      => esc_html__( 'Buttons', 'helion' ),
					'desc'       => '',
					'dependency' => array(
						'front_page_title_shortcode' => array( 'is_empty' ),
					),
					'type'       => 'info',
				),
				'front_page_title_button1_link'    => array(
					'title'           => esc_html__( 'Button1 link', 'helion' ),
					'desc'            => '',
					'refresh'         => '.front_page_section_title .front_page_section_title_button1',
					'refresh_wrapper' => true,
					'std'             => '#',
					'dependency'      => array(
						'front_page_title_shortcode' => array( 'is_empty' ),
					),
					'type'            => 'text',
				),
				'front_page_title_button1_caption' => array(
					'title'      => esc_html__( 'Button1 caption', 'helion' ),
					'desc'       => '',
					'translate'  => true,
					'dependency' => array(
						'front_page_title_button1_link' => array( 'not_empty' ),
						'front_page_title_shortcode'    => array( 'is_empty' ),
					),
					'refresh'    => false,
					'std'        => wp_kses_data( __( 'Customize Button 1', 'helion' ) ),
					'type'       => 'text',
				),
				'front_page_title_button2_link'    => array(
					'title'           => esc_html__( 'Button2 link', 'helion' ),
					'desc'            => '',
					'refresh'         => '.front_page_section_title .front_page_section_title_button2',
					'refresh_wrapper' => true,
					'std'             => '#',
					'dependency'      => array(
						'front_page_title_shortcode' => array( 'is_empty' ),
					),
					'type'            => 'text',
				),
				'front_page_title_button2_caption' => array(
					'title'      => esc_html__( 'Button2 caption', 'helion' ),
					'desc'       => '',
					'translate'  => true,
					'dependency' => array(
						'front_page_title_button2_link' => array( 'not_empty' ),
						'front_page_title_shortcode'    => array( 'is_empty' ),
					),
					'refresh'    => false,
					'std'        => wp_kses_data( __( 'Customize Button 2', 'helion' ) ),
					'type'       => 'text',
				),
				'front_page_title_color_info'      => array(
					'title' => esc_html__( 'Colors and images', 'helion' ),
					'desc'  => '',
					'type'  => 'info',
				),
				'front_page_title_scheme'          => array(
					'title'   => esc_html__( 'Color scheme', 'helion' ),
					'desc'    => wp_kses_data( __( 'Color scheme for this section', 'helion' ) ),
					'std'     => HELION_THEME_FREE ? 'dark' : 'inherit',
					'options' => array(),
					'refresh' => false,
					'type'    => 'radio',
				),
				'front_page_title_bg_image'        => array(
					'title'           => esc_html__( 'Background image', 'helion' ),
					'desc'            => wp_kses_data( __( 'Select or upload background image for this section', 'helion' ) ),
					'refresh'         => '.front_page_section_title',
					'refresh_wrapper' => true,
					'std'             => HELION_THEME_FREE ? helion_get_file_url( 'front-page/images/bg-title.jpg' ) : '',
					'type'            => 'image',
				),
				'front_page_title_bg_color_type'   => array(
					'title'   => esc_html__( 'Background color', 'helion' ),
					'desc'    => wp_kses_data( __( 'Background color for this section', 'helion' ) ),
					'std'     => HELION_THEME_FREE ? 'custom' : 'none',
					'refresh' => false,
					'options' => array(
						'none'            => esc_html__( 'None', 'helion' ),
						'scheme_bg_color' => esc_html__( 'Scheme bg color', 'helion' ),
						'custom'          => esc_html__( 'Custom', 'helion' ),
					),
					'type'    => 'radio',
				),
				'front_page_title_bg_color'        => array(
					'title'      => esc_html__( 'Custom color', 'helion' ),
					'desc'       => wp_kses_data( __( 'Custom background color for this section', 'helion' ) ),
					'std'        => HELION_THEME_FREE ? '#000' : '',
					'refresh'    => false,
					'dependency' => array(
						'front_page_title_bg_color_type' => array( 'custom' ),
					),
					'type'       => 'color',
				),
				'front_page_title_bg_mask'         => array(
					'title'   => esc_html__( 'Background mask', 'helion' ),
					'desc'    => wp_kses_data( __( 'Use Background color as section mask with specified opacity. If 0 - mask is not being used', 'helion' ) ),
					'max'     => 1,
					'step'    => 0.1,
					'std'     => HELION_THEME_FREE ? 0.5 : 1,
					'refresh' => false,
					'type'    => 'slider',
				),
				'front_page_title_anchor_info'     => array(
					'title' => esc_html__( 'Anchor', 'helion' ),
					'desc'  => wp_kses_data( __( 'You can select icon and/or specify a text to create anchor for this section and show it in the side menu (if selected in the section "Header - Menu").', 'helion' ) )
								. '<br>'
								. wp_kses_data( __( 'Attention! Anchors available only if plugin "ThemeREX Addons is installed and activated!', 'helion' ) ),
					'type'  => 'info',
				),
				'front_page_title_anchor_icon'     => array(
					'title' => esc_html__( 'Anchor icon', 'helion' ),
					'desc'  => '',
					'std'   => '',
					'type'  => 'icon',
				),
				'front_page_title_anchor_text'     => array(
					'title'     => esc_html__( 'Anchor text', 'helion' ),
					'desc'      => '',
					'translate' => true,
					'std'       => '',
					'type'      => 'text',
				),
			)
		);
		return $options;
	}
}



// Add section 'Features' to the Front Page option
if ( ! function_exists( 'helion_front_page_options_features' ) ) {
	add_filter( 'helion_filter_front_page_options', 'helion_front_page_options_features' );
	function helion_front_page_options_features( $options ) {
		$options['front_page_sections']['std']    .= ( ! empty( $options['front_page_sections']['std'] ) ? '|' : '' ) . 'features=1';
		$options['front_page_sections']['options'] = array_merge(
			$options['front_page_sections']['options'],
			array(
				'features' => esc_html__( 'Features', 'helion' ),
			)
		);
		$options                                   = array_merge(
			$options, array(

				// Front Page Sections - Features
				'sidebar-widgets-front_page_features_widgets' => array(
					'title'    => esc_html__( 'Features', 'helion' ),
					'desc'     => '',
					'priority' => 30,
					'type'     => 'section',
				),
				'front_page_features_layout_info'  => array(
					'title'    => esc_html__( 'Layout', 'helion' ),
					'desc'     => '',
					'priority' => -120,
					'type'     => 'info',
				),
				'front_page_features_fullheight'   => array(
					'title'    => esc_html__( 'Full height', 'helion' ),
					'desc'     => wp_kses_data( __( 'Stretch this section to the window height', 'helion' ) ),
					'std'      => 0,
					'refresh'  => false,
					'priority' => -110,
					'type'     => 'switch',
				),
				'front_page_features_stack'        => array(
					'title'      => esc_html__( 'Stack this section', 'helion' ),
					'desc'       => wp_kses_data( __( 'Add the behavior of "a stack" for this section to fix it when you scroll to the top of the screen.', 'helion' ) ),
					'std'        => 0,
					'refresh'    => false,
					'dependency' => array(
						'front_page_features_fullheight' => array( 1 ),
					),
					'type'       => 'switch',
				),
				'front_page_features_paddings'     => array(
					'title'    => esc_html__( 'Paddings', 'helion' ),
					'desc'     => wp_kses_data( __( 'Select paddings inside this section', 'helion' ) ),
					'std'      => 'medium',
					'options'  => helion_get_list_paddings(),
					'refresh'  => false,
					'priority' => -100,
					'type'     => 'radio',
				),
				'front_page_features_heading_info' => array(
					'title'    => esc_html__( 'Title', 'helion' ),
					'desc'     => '',
					'priority' => -90,
					'type'     => 'info',
				),
				'front_page_features_caption'      => array(
					'title'     => esc_html__( 'Section title', 'helion' ),
					'desc'      => '',
					'translate' => true,
					'refresh'   => false,
					'std'       => wp_kses_data( __( 'Why our service is the best', 'helion' ) ),
					'priority'  => -80,
					'type'      => 'text',
				),
				'front_page_features_description'  => array(
					'title'     => esc_html__( 'Description', 'helion' ),
					'desc'      => wp_kses_data( __( "Short description after the section's title", 'helion' ) ),
					'translate' => true,
					'refresh'   => false,
					'std'       => wp_kses_data( __( 'This text can be changed in the section "Features"', 'helion' ) ),
					'priority'  => -70,
					'type'      => 'textarea',
				),
				'front_page_features_widgets_info' => array(
					'title'    => esc_html__( 'Widgets', 'helion' ),
					'desc'     => wp_kses_data( __( 'You can setup widgets in this section in the menu "Appearance - Customize" or "Appearance - Widgets"', 'helion' ) )
								. '<br>'
								. wp_kses_data( __( 'Insert your preferred widget to display your services here. You can also select any other widget, changing thus the purpose of this section', 'helion' ) ),
					'priority' => -60,
					'type'     => 'info',
				),
				'front_page_features_color_info'   => array(
					'title'    => esc_html__( 'Colors and images', 'helion' ),
					'desc'     => '',
					'priority' => 100,
					'type'     => 'info',
				),
				'front_page_features_scheme'       => array(
					'title'   => esc_html__( 'Color scheme', 'helion' ),
					'desc'    => wp_kses_data( __( 'Color scheme for this section', 'helion' ) ),
					'std'     => 'inherit',
					'options' => array(),
					'refresh' => false,
					'type'    => 'radio',
				),
				'front_page_features_bg_image'     => array(
					'title'           => esc_html__( 'Background image', 'helion' ),
					'desc'            => wp_kses_data( __( 'Select or upload background image for this section', 'helion' ) ),
					'refresh'         => '.front_page_section_features',
					'refresh_wrapper' => true,
					'std'             => '',
					'type'            => 'image',
				),
				'front_page_features_bg_color_type' => array(
					'title'   => esc_html__( 'Background color', 'helion' ),
					'desc'    => wp_kses_data( __( 'Background color for this section', 'helion' ) ),
					'std'     => 'scheme_bg_color',
					'refresh' => false,
					'options' => array(
						'none'            => esc_html__( 'None', 'helion' ),
						'scheme_bg_color' => esc_html__( 'Scheme bg color', 'helion' ),
						'custom'          => esc_html__( 'Custom', 'helion' ),
					),
					'type'    => 'radio',
				),
				'front_page_features_bg_color'     => array(
					'title'      => esc_html__( 'Custom color', 'helion' ),
					'desc'       => wp_kses_data( __( 'Custom background color for this section', 'helion' ) ),
					'std'        => '',
					'refresh'    => false,
					'dependency' => array(
						'front_page_features_bg_color_type' => array( 'custom' ),
					),
					'type'       => 'color',
				),
				'front_page_features_bg_mask'      => array(
					'title'   => esc_html__( 'Background mask', 'helion' ),
					'desc'    => wp_kses_data( __( 'Use Background color as section mask with specified opacity. If 0 - mask is not being used', 'helion' ) ),
					'max'     => 1,
					'step'    => 0.1,
					'std'     => 1,
					'refresh' => false,
					'type'    => 'slider',
				),
				'front_page_features_anchor_info'  => array(
					'title' => esc_html__( 'Anchor', 'helion' ),
					'desc'  => wp_kses_data( __( 'You can select icon and/or specify a text to create anchor for this section and show it in the side menu (if selected in the section "Header - Menu").', 'helion' ) )
								. '<br>'
								. wp_kses_data( __( 'Attention! Anchors available only if plugin "ThemeREX Addons is installed and activated!', 'helion' ) ),
					'type'  => 'info',
				),
				'front_page_features_anchor_icon'  => array(
					'title' => esc_html__( 'Anchor icon', 'helion' ),
					'desc'  => '',
					'std'   => '',
					'type'  => 'icon',
				),
				'front_page_features_anchor_text'  => array(
					'title'     => esc_html__( 'Anchor text', 'helion' ),
					'translate' => true,
					'desc'      => '',
					'std'       => '',
					'type'      => 'text',
				),
			)
		);
		return $options;
	}
}



// Add section 'About Us' to the Front Page option
if ( ! function_exists( 'helion_front_page_options_about' ) ) {
	add_filter( 'helion_filter_front_page_options', 'helion_front_page_options_about' );
	function helion_front_page_options_about( $options ) {
		$options['front_page_sections']['std']    .= ( ! empty( $options['front_page_sections']['std'] ) ? '|' : '' ) . 'about=1';
		$options['front_page_sections']['options'] = array_merge(
			$options['front_page_sections']['options'],
			array(
				'about' => esc_html__( 'About Us', 'helion' ),
			)
		);
		$options                                   = array_merge(
			$options, array(

				// Front Page Sections - About
				'front_page_about'              => array(
					'title'    => esc_html__( 'About Us', 'helion' ),
					'desc'     => '',
					'priority' => 40,
					'type'     => 'section',
				),
				'front_page_about_layout_info'  => array(
					'title' => esc_html__( 'Layout', 'helion' ),
					'desc'  => '',
					'type'  => 'info',
				),
				'front_page_about_fullheight'   => array(
					'title'   => esc_html__( 'Full height', 'helion' ),
					'desc'    => wp_kses_data( __( 'Stretch this section to the window height', 'helion' ) ),
					'std'     => 0,
					'refresh' => false,
					'type'    => 'switch',
				),
				'front_page_about_stack'        => array(
					'title'      => esc_html__( 'Stack this section', 'helion' ),
					'desc'       => wp_kses_data( __( 'Add the behavior of "a stack" for this section to fix it when you scroll to the top of the screen.', 'helion' ) ),
					'std'        => 0,
					'refresh'    => false,
					'dependency' => array(
						'front_page_about_fullheight' => array( 1 ),
					),
					'type'       => 'switch',
				),
				'front_page_about_paddings'     => array(
					'title'   => esc_html__( 'Paddings', 'helion' ),
					'desc'    => wp_kses_data( __( 'Select paddings inside this section', 'helion' ) ),
					'std'     => 'medium',
					'options' => helion_get_list_paddings(),
					'refresh' => false,
					'type'    => 'radio',
				),
				'front_page_about_heading_info' => array(
					'title' => esc_html__( 'Title', 'helion' ),
					'desc'  => '',
					'type'  => 'info',
				),
				'front_page_about_caption'      => array(
					'title'     => esc_html__( 'Section title', 'helion' ),
					'desc'      => '',
					'translate' => true,
					'refresh'   => false,
					'std'       => wp_kses_data( __( 'About Us', 'helion' ) ),
					'type'      => 'text',
				),
				'front_page_about_description'  => array(
					'title'     => esc_html__( 'Description', 'helion' ),
					'desc'      => wp_kses_data( __( "Short description after the section's title", 'helion' ) ),
					'translate' => true,
					'refresh'   => false,
					'std'       => wp_kses_data( __( 'This text can be changed in the section "About"', 'helion' ) ),
					'type'      => 'textarea',
				),
				'front_page_about_content_info' => array(
					'title' => esc_html__( 'Content', 'helion' ),
					'desc'  => '',
					'type'  => 'info',
				),
				'front_page_about_content'      => array(
					'title'     => esc_html__( 'Content', 'helion' ),
					'desc'      => wp_kses_data( __( 'The arbitrary content of the current section.', 'helion' ) )
								. '<br>'
								. wp_kses_data(
									__( 'Attention! You can use %%CONTENT%% to insert instead the content of the page, selected as the Front Page in the menu "Settings - Reading" or in the "Customize - Static Front Page"', 'helion' )
								),
					'translate' => true,
					'refresh'   => false,
					'std'       => '',
					'teeny'     => false,
					'rows'      => 20,
					'type'      => 'text_editor',
				),
				'front_page_about_color_info'   => array(
					'title' => esc_html__( 'Colors and images', 'helion' ),
					'desc'  => '',
					'type'  => 'info',
				),
				'front_page_about_scheme'       => array(
					'title'   => esc_html__( 'Color scheme', 'helion' ),
					'desc'    => wp_kses_data( __( 'Color scheme for this section', 'helion' ) ),
					'std'     => HELION_THEME_FREE ? 'dark' : 'inherit',
					'options' => array(),
					'refresh' => false,
					'type'    => 'radio',
				),
				'front_page_about_bg_image'     => array(
					'title'           => esc_html__( 'Background image', 'helion' ),
					'desc'            => wp_kses_data( __( 'Select or upload background image for this section', 'helion' ) ),
					'refresh'         => '.front_page_section_about',
					'refresh_wrapper' => true,
					'std'             => '',
					'type'            => 'image',
				),
				'front_page_about_bg_color_type'   => array(
					'title'   => esc_html__( 'Background color', 'helion' ),
					'desc'    => wp_kses_data( __( 'Background color for this section', 'helion' ) ),
					'std'     => HELION_THEME_FREE ? 'custom' : 'none',
					'refresh' => false,
					'options' => array(
						'none'            => esc_html__( 'None', 'helion' ),
						'scheme_bg_color' => esc_html__( 'Scheme bg color', 'helion' ),
						'custom'          => esc_html__( 'Custom', 'helion' ),
					),
					'type'    => 'radio',
				),
				'front_page_about_bg_color'        => array(
					'title'      => esc_html__( 'Custom color', 'helion' ),
					'desc'       => wp_kses_data( __( 'Custom background color for this section', 'helion' ) ),
					'std'        => HELION_THEME_FREE ? '#000' : '',
					'refresh'    => false,
					'dependency' => array(
						'front_page_about_bg_color_type' => array( 'custom' ),
					),
					'type'       => 'color',
				),
				'front_page_about_bg_mask'      => array(
					'title'   => esc_html__( 'Background mask', 'helion' ),
					'desc'    => wp_kses_data( __( 'Use Background color as section mask with specified opacity. If 0 - mask is not being used', 'helion' ) ),
					'max'     => 1,
					'step'    => 0.1,
					'std'     => HELION_THEME_FREE ? 0.5 : 1,
					'refresh' => false,
					'type'    => 'slider',
				),
				'front_page_about_anchor_info'  => array(
					'title' => esc_html__( 'Anchor', 'helion' ),
					'desc'  => wp_kses_data( __( 'You can select icon and/or specify a text to create anchor for this section and show it in the side menu (if selected in the section "Header - Menu").', 'helion' ) )
								. '<br>'
								. wp_kses_data( __( 'Attention! Anchors available only if plugin "ThemeREX Addons is installed and activated!', 'helion' ) ),
					'type'  => 'info',
				),
				'front_page_about_anchor_icon'  => array(
					'title' => esc_html__( 'Anchor icon', 'helion' ),
					'desc'  => '',
					'std'   => '',
					'type'  => 'icon',
				),
				'front_page_about_anchor_text'  => array(
					'title'     => esc_html__( 'Anchor text', 'helion' ),
					'desc'      => '',
					'translate' => true,
					'std'       => '',
					'type'      => 'text',
				),
			)
		);
		return $options;
	}
}



// Add section 'Team' to the Front Page option
if ( ! function_exists( 'helion_front_page_options_team' ) ) {
	add_filter( 'helion_filter_front_page_options', 'helion_front_page_options_team' );
	function helion_front_page_options_team( $options ) {
		$options['front_page_sections']['std']    .= ( ! empty( $options['front_page_sections']['std'] ) ? '|' : '' ) . 'team=1';
		$options['front_page_sections']['options'] = array_merge(
			$options['front_page_sections']['options'],
			array(
				'team' => esc_html__( 'Our Team', 'helion' ),
			)
		);
		$options                                   = array_merge(
			$options, array(

				// Front Page Sections - Team
				'sidebar-widgets-front_page_team_widgets' => array(
					'title'    => esc_html__( 'Team members', 'helion' ),
					'desc'     => '',
					'priority' => 50,
					'type'     => 'section',
				),
				'front_page_team_layout_info'             => array(
					'title'    => esc_html__( 'Layout', 'helion' ),
					'desc'     => '',
					'priority' => -120,
					'type'     => 'info',
				),
				'front_page_team_fullheight'              => array(
					'title'    => esc_html__( 'Full height', 'helion' ),
					'desc'     => wp_kses_data( __( 'Stretch this section to the window height', 'helion' ) ),
					'std'      => 0,
					'refresh'  => false,
					'priority' => -110,
					'type'     => 'switch',
				),
				'front_page_team_stack'                   => array(
					'title'      => esc_html__( 'Stack this section', 'helion' ),
					'desc'       => wp_kses_data( __( 'Add the behavior of "a stack" for this section to fix it when you scroll to the top of the screen.', 'helion' ) ),
					'std'        => 0,
					'refresh'    => false,
					'dependency' => array(
						'front_page_team_fullheight' => array( 1 ),
					),
					'type'       => 'switch',
				),
				'front_page_team_paddings'                => array(
					'title'    => esc_html__( 'Paddings', 'helion' ),
					'desc'     => wp_kses_data( __( 'Select paddings inside this section', 'helion' ) ),
					'std'      => 'medium',
					'options'  => helion_get_list_paddings(),
					'refresh'  => false,
					'priority' => -100,
					'type'     => 'radio',
				),
				'front_page_team_heading_info'            => array(
					'title'    => esc_html__( 'Title', 'helion' ),
					'desc'     => '',
					'priority' => -90,
					'type'     => 'info',
				),
				'front_page_team_caption'                 => array(
					'title'     => esc_html__( 'Section title', 'helion' ),
					'desc'      => '',
					'translate' => true,
					'refresh'   => false,
					'std'       => wp_kses_data( __( 'Meet our team', 'helion' ) ),
					'priority'  => -80,
					'type'      => 'text',
				),
				'front_page_team_description'             => array(
					'title'     => esc_html__( 'Description', 'helion' ),
					'desc'      => wp_kses_data( __( "Short description after the section's title", 'helion' ) ),
					'translate' => true,
					'refresh'   => false,
					'std'       => wp_kses_data( __( 'This text can be changed in the section "Team members"', 'helion' ) ),
					'priority'  => -70,
					'type'      => 'textarea',
				),
				'front_page_team_widgets_info'            => array(
					'title'    => esc_html__( 'Widgets', 'helion' ),
					'desc'     => wp_kses_data( __( 'You can setup widgets in this section in the menu "Appearance - Customize" or "Appearance - Widgets"', 'helion' ) )
								. '<br>'
								. wp_kses_data( __( 'Insert your preferred widget to display your team members here. You can also select any other widget, changing thus the purpose of this section', 'helion' ) ),
					'priority' => -60,
					'type'     => 'info',
				),
				'front_page_team_color_info'              => array(
					'title'    => esc_html__( 'Colors and images', 'helion' ),
					'desc'     => '',
					'priority' => 100,
					'type'     => 'info',
				),
				'front_page_team_scheme'                  => array(
					'title'   => esc_html__( 'Color scheme', 'helion' ),
					'desc'    => wp_kses_data( __( 'Color scheme for this section', 'helion' ) ),
					'std'     => HELION_THEME_FREE ? 'dark' : 'inherit',
					'options' => array(),
					'refresh' => false,
					'type'    => 'radio',
				),
				'front_page_team_bg_image'                => array(
					'title'           => esc_html__( 'Background image', 'helion' ),
					'desc'            => wp_kses_data( __( 'Select or upload background image for this section', 'helion' ) ),
					'refresh'         => '.front_page_section_team',
					'refresh_wrapper' => true,
					'std'             => HELION_THEME_FREE ? helion_get_file_url( 'front-page/images/bg-team.jpg' ) : '',
					'type'            => 'image',
				),
				'front_page_team_bg_color_type'           => array(
					'title'   => esc_html__( 'Background color', 'helion' ),
					'desc'    => wp_kses_data( __( 'Background color for this section', 'helion' ) ),
					'std'     => HELION_THEME_FREE ? 'custom' : 'none',
					'refresh' => false,
					'options' => array(
						'none'            => esc_html__( 'None', 'helion' ),
						'scheme_bg_color' => esc_html__( 'Scheme bg color', 'helion' ),
						'custom'          => esc_html__( 'Custom', 'helion' ),
					),
					'type'    => 'radio',
				),
				'front_page_team_bg_color'                => array(
					'title'      => esc_html__( 'Custom color', 'helion' ),
					'desc'       => wp_kses_data( __( 'Custom background color for this section', 'helion' ) ),
					'std'        => HELION_THEME_FREE ? '#000' : '',
					'refresh'    => false,
					'dependency' => array(
						'front_page_team_bg_color_type' => array( 'custom' ),
					),
					'type'       => 'color',
				),
				'front_page_team_bg_mask'                 => array(
					'title'   => esc_html__( 'Background mask', 'helion' ),
					'desc'    => wp_kses_data( __( 'Use Background color as section mask with specified opacity. If 0 - mask is not being used', 'helion' ) ),
					'max'     => 1,
					'step'    => 0.1,
					'std'     => HELION_THEME_FREE ? 0.5 : 1,
					'refresh' => false,
					'type'    => 'slider',
				),
				'front_page_team_anchor_info'             => array(
					'title' => esc_html__( 'Anchor', 'helion' ),
					'desc'  => wp_kses_data( __( 'You can select icon and/or specify a text to create anchor for this section and show it in the side menu (if selected in the section "Header - Menu").', 'helion' ) )
								. '<br>'
								. wp_kses_data( __( 'Attention! Anchors available only if plugin "ThemeREX Addons is installed and activated!', 'helion' ) ),
					'type'  => 'info',
				),
				'front_page_team_anchor_icon'             => array(
					'title' => esc_html__( 'Anchor icon', 'helion' ),
					'desc'  => '',
					'std'   => '',
					'type'  => 'icon',
				),
				'front_page_team_anchor_text'             => array(
					'title'     => esc_html__( 'Anchor text', 'helion' ),
					'desc'      => '',
					'translate' => true,
					'std'       => '',
					'type'      => 'text',
				),
			)
		);
		return $options;
	}
}



// Add section 'Testimonials' to the Front Page option
if ( ! function_exists( 'helion_front_page_options_testimonials' ) ) {
	add_filter( 'helion_filter_front_page_options', 'helion_front_page_options_testimonials' );
	function helion_front_page_options_testimonials( $options ) {
		$options['front_page_sections']['std']    .= ( ! empty( $options['front_page_sections']['std'] ) ? '|' : '' ) . 'testimonials=1';
		$options['front_page_sections']['options'] = array_merge(
			$options['front_page_sections']['options'],
			array(
				'testimonials' => esc_html__( 'Testimonials', 'helion' ),
			)
		);
		$options                                   = array_merge(
			$options, array(

				// Front Page Sections - Testimonials
				'sidebar-widgets-front_page_testimonials_widgets' => array(
					'title'    => esc_html__( 'Testimonials', 'helion' ),
					'desc'     => '',
					'priority' => 60,
					'type'     => 'section',
				),
				'front_page_testimonials_layout_info'  => array(
					'title'    => esc_html__( 'Layout', 'helion' ),
					'desc'     => '',
					'priority' => -120,
					'type'     => 'info',
				),
				'front_page_testimonials_fullheight'   => array(
					'title'    => esc_html__( 'Full height', 'helion' ),
					'desc'     => wp_kses_data( __( 'Stretch this section to the window height', 'helion' ) ),
					'std'      => 0,
					'refresh'  => false,
					'priority' => -110,
					'type'     => 'switch',
				),
				'front_page_testimonials_stack'           => array(
					'title'      => esc_html__( 'Stack this section', 'helion' ),
					'desc'       => wp_kses_data( __( 'Add the behavior of "a stack" for this section to fix it when you scroll to the top of the screen.', 'helion' ) ),
					'std'        => 0,
					'refresh'    => false,
					'dependency' => array(
						'front_page_testimonials_fullheight' => array( 1 ),
					),
					'type'       => 'switch',
				),
				'front_page_testimonials_paddings'     => array(
					'title'    => esc_html__( 'Paddings', 'helion' ),
					'desc'     => wp_kses_data( __( 'Select paddings inside this section', 'helion' ) ),
					'std'      => 'medium',
					'options'  => helion_get_list_paddings(),
					'refresh'  => false,
					'priority' => -100,
					'type'     => 'radio',
				),
				'front_page_testimonials_heading_info' => array(
					'title'    => esc_html__( 'Title', 'helion' ),
					'desc'     => '',
					'priority' => -90,
					'type'     => 'info',
				),
				'front_page_testimonials_caption'      => array(
					'title'     => esc_html__( 'Section title', 'helion' ),
					'desc'      => '',
					'translate' => true,
					'refresh'   => false,
					'std'       => wp_kses_data( __( 'What our clients say', 'helion' ) ),
					'priority'  => -80,
					'type'      => 'text',
				),
				'front_page_testimonials_description'  => array(
					'title'     => esc_html__( 'Description', 'helion' ),
					'desc'      => wp_kses_data( __( "Short description after the section's title", 'helion' ) ),
					'translate' => true,
					'refresh'   => false,
					'std'       => wp_kses_data( __( 'This text can be changed in the section "Testimonials"', 'helion' ) ),
					'priority'  => -70,
					'type'      => 'textarea',
				),
				'front_page_testimonials_widgets_info' => array(
					'title'    => esc_html__( 'Widgets', 'helion' ),
					'desc'     => wp_kses_data( __( 'You can setup widgets in this section in the menu "Appearance - Customize" or "Appearance - Widgets"', 'helion' ) )
								. '<br>'
								. wp_kses_data( __( 'Insert your preferred widget to display testimonials here. You can also select any other widget, changing thus the purpose of this section', 'helion' ) ),
					'priority' => -60,
					'type'     => 'info',
				),
				'front_page_testimonials_color_info'   => array(
					'title'    => esc_html__( 'Colors and images', 'helion' ),
					'desc'     => '',
					'priority' => 100,
					'type'     => 'info',
				),
				'front_page_testimonials_scheme'       => array(
					'title'   => esc_html__( 'Color scheme', 'helion' ),
					'desc'    => wp_kses_data( __( 'Color scheme for this section', 'helion' ) ),
					'std'     => 'inherit',
					'options' => array(),
					'refresh' => false,
					'type'    => 'radio',
				),
				'front_page_testimonials_bg_image'     => array(
					'title'           => esc_html__( 'Background image', 'helion' ),
					'desc'            => wp_kses_data( __( 'Select or upload background image for this section', 'helion' ) ),
					'refresh'         => '.front_page_section_testimonials',
					'refresh_wrapper' => true,
					'std'             => '',
					'type'            => 'image',
				),
				'front_page_testimonials_bg_color_type' => array(
					'title'   => esc_html__( 'Background color', 'helion' ),
					'desc'    => wp_kses_data( __( 'Background color for this section', 'helion' ) ),
					'std'     => 'scheme_bg_color',
					'refresh' => false,
					'options' => array(
						'none'            => esc_html__( 'None', 'helion' ),
						'scheme_bg_color' => esc_html__( 'Scheme bg color', 'helion' ),
						'custom'          => esc_html__( 'Custom', 'helion' ),
					),
					'type'    => 'radio',
				),
				'front_page_testimonials_bg_color'     => array(
					'title'      => esc_html__( 'Custom color', 'helion' ),
					'desc'       => wp_kses_data( __( 'Custom background color for this section', 'helion' ) ),
					'std'        => '',
					'refresh'    => false,
					'dependency' => array(
						'front_page_testimonials_bg_color_type' => array( 'custom' ),
					),
					'type'       => 'color',
				),
				'front_page_testimonials_bg_mask'      => array(
					'title'   => esc_html__( 'Background mask', 'helion' ),
					'desc'    => wp_kses_data( __( 'Use Background color as section mask with specified opacity. If 0 - mask is not being used', 'helion' ) ),
					'max'     => 1,
					'step'    => 0.1,
					'std'     => 1,
					'refresh' => false,
					'type'    => 'slider',
				),
				'front_page_testimonials_anchor_info'  => array(
					'title' => esc_html__( 'Anchor', 'helion' ),
					'desc'  => wp_kses_data( __( 'You can select icon and/or specify a text to create anchor for this section and show it in the side menu (if selected in the section "Header - Menu").', 'helion' ) )
								. '<br>'
								. wp_kses_data( __( 'Attention! Anchors available only if plugin "ThemeREX Addons is installed and activated!', 'helion' ) ),
					'type'  => 'info',
				),
				'front_page_testimonials_anchor_icon'  => array(
					'title' => esc_html__( 'Anchor icon', 'helion' ),
					'desc'  => '',
					'std'   => '',
					'type'  => 'icon',
				),
				'front_page_testimonials_anchor_text'  => array(
					'title'     => esc_html__( 'Anchor text', 'helion' ),
					'desc'      => '',
					'translate' => true,
					'std'       => '',
					'type'      => 'text',
				),
			)
		);
		return $options;
	}
}



// Add section 'Latest posts' to the Front Page option
if ( ! function_exists( 'helion_front_page_options_blog' ) ) {
	add_filter( 'helion_filter_front_page_options', 'helion_front_page_options_blog' );
	function helion_front_page_options_blog( $options ) {
		$options['front_page_sections']['std']    .= ( ! empty( $options['front_page_sections']['std'] ) ? '|' : '' ) . 'blog=1';
		$options['front_page_sections']['options'] = array_merge(
			$options['front_page_sections']['options'],
			array(
				'blog' => esc_html__( 'Latest posts', 'helion' ),
			)
		);
		$options                                   = array_merge(
			$options, array(

				// Front Page Sections - Blog (Latest posts)
				'sidebar-widgets-front_page_blog_widgets' => array(
					'title'    => esc_html__( 'Latest posts', 'helion' ),
					'desc'     => '',
					'priority' => 70,
					'type'     => 'section',
				),
				'front_page_blog_layout_info'             => array(
					'title'    => esc_html__( 'Layout', 'helion' ),
					'desc'     => '',
					'priority' => -120,
					'type'     => 'info',
				),
				'front_page_blog_fullheight'              => array(
					'title'    => esc_html__( 'Full height', 'helion' ),
					'desc'     => wp_kses_data( __( 'Stretch this section to the window height', 'helion' ) ),
					'std'      => 0,
					'refresh'  => false,
					'priority' => -110,
					'type'     => 'switch',
				),
				'front_page_blog_stack'                   => array(
					'title'      => esc_html__( 'Stack this section', 'helion' ),
					'desc'       => wp_kses_data( __( 'Add the behavior of "a stack" for this section to fix it when you scroll to the top of the screen.', 'helion' ) ),
					'std'        => 0,
					'refresh'    => false,
					'dependency' => array(
						'front_page_blog_fullheight' => array( 1 ),
					),
					'type'       => 'switch',
				),
				'front_page_blog_paddings'                => array(
					'title'    => esc_html__( 'Paddings', 'helion' ),
					'desc'     => wp_kses_data( __( 'Select paddings inside this section', 'helion' ) ),
					'std'      => 'medium',
					'options'  => helion_get_list_paddings(),
					'refresh'  => false,
					'priority' => -100,
					'type'     => 'radio',
				),
				'front_page_blog_heading_info'            => array(
					'title'    => esc_html__( 'Title', 'helion' ),
					'desc'     => '',
					'priority' => -90,
					'type'     => 'info',
				),
				'front_page_blog_caption'                 => array(
					'title'     => esc_html__( 'Section title', 'helion' ),
					'desc'      => '',
					'translate' => true,
					'refresh'   => false,
					'std'       => wp_kses_data( __( 'Latest posts', 'helion' ) ),
					'priority'  => -80,
					'type'      => 'text',
				),
				'front_page_blog_description'             => array(
					'title'     => esc_html__( 'Description', 'helion' ),
					'desc'      => wp_kses_data( __( "Short description after the section's title", 'helion' ) ),
					'translate' => true,
					'refresh'   => false,
					'std'       => wp_kses_data( __( 'This text can be changed in the section "Latest posts"', 'helion' ) ),
					'priority'  => -70,
					'type'      => 'textarea',
				),
				'front_page_blog_widgets_info'            => array(
					'title'    => esc_html__( 'Widgets', 'helion' ),
					'desc'     => wp_kses_data( __( 'You can setup widgets in this section in the menu "Appearance - Customize" or "Appearance - Widgets"', 'helion' ) )
								. '<br>'
								. wp_kses_data( __( 'Insert your preferred widget to display latest posts here. You can also select any other widget, changing thus the purpose of this section', 'helion' ) ),
					'priority' => -60,
					'type'     => 'info',
				),
				'front_page_blog_color_info'              => array(
					'title'    => esc_html__( 'Colors and images', 'helion' ),
					'desc'     => '',
					'priority' => 100,
					'type'     => 'info',
				),
				'front_page_blog_scheme'                  => array(
					'title'   => esc_html__( 'Color scheme', 'helion' ),
					'desc'    => wp_kses_data( __( 'Color scheme for this section', 'helion' ) ),
					'std'     => HELION_THEME_FREE ? 'dark' : 'inherit',
					'options' => array(),
					'refresh' => false,
					'type'    => 'radio',
				),
				'front_page_blog_bg_image'                => array(
					'title'           => esc_html__( 'Background image', 'helion' ),
					'desc'            => wp_kses_data( __( 'Select or upload background image for this section', 'helion' ) ),
					'refresh'         => '.front_page_section_blog',
					'refresh_wrapper' => true,
					'std'             => '',
					'type'            => 'image',
				),
				'front_page_blog_bg_color_type'           => array(
					'title'   => esc_html__( 'Background color', 'helion' ),
					'desc'    => wp_kses_data( __( 'Background color for this section', 'helion' ) ),
					'std'     => HELION_THEME_FREE ? 'custom' : 'none',
					'refresh' => false,
					'options' => array(
						'none'            => esc_html__( 'None', 'helion' ),
						'scheme_bg_color' => esc_html__( 'Scheme bg color', 'helion' ),
						'custom'          => esc_html__( 'Custom', 'helion' ),
					),
					'type'    => 'radio',
				),
				'front_page_blog_bg_color'                => array(
					'title'      => esc_html__( 'Custom color', 'helion' ),
					'desc'       => wp_kses_data( __( 'Custom background color for this section', 'helion' ) ),
					'std'        => HELION_THEME_FREE ? '#000' : '',
					'refresh'    => false,
					'dependency' => array(
						'front_page_blog_bg_color_type' => array( 'custom' ),
					),
					'type'       => 'color',
				),
				'front_page_blog_bg_mask'                 => array(
					'title'   => esc_html__( 'Background mask', 'helion' ),
					'desc'    => wp_kses_data( __( 'Use Background color as section mask with specified opacity. If 0 - mask is not being used', 'helion' ) ),
					'max'     => 1,
					'step'    => 0.1,
					'std'     => HELION_THEME_FREE ? 0.5 : 1,
					'refresh' => false,
					'type'    => 'slider',
				),
				'front_page_blog_anchor_info'             => array(
					'title' => esc_html__( 'Anchor', 'helion' ),
					'desc'  => wp_kses_data( __( 'You can select icon and/or specify a text to create anchor for this section and show it in the side menu (if selected in the section "Header - Menu").', 'helion' ) )
								. '<br>'
								. wp_kses_data( __( 'Attention! Anchors available only if plugin "ThemeREX Addons is installed and activated!', 'helion' ) ),
					'type'  => 'info',
				),
				'front_page_blog_anchor_icon'             => array(
					'title' => esc_html__( 'Anchor icon', 'helion' ),
					'desc'  => '',
					'std'   => '',
					'type'  => 'icon',
				),
				'front_page_blog_anchor_text'             => array(
					'title'     => esc_html__( 'Anchor text', 'helion' ),
					'desc'      => '',
					'translate' => true,
					'std'       => '',
					'type'      => 'text',
				),
			)
		);
		return $options;
	}
}



// Add section 'Subscribe' to the Front Page option
if ( ! function_exists( 'helion_front_page_options_subscribe' ) ) {
	add_filter( 'helion_filter_front_page_options', 'helion_front_page_options_subscribe' );
	function helion_front_page_options_subscribe( $options ) {
		$options['front_page_sections']['std']    .= ( ! empty( $options['front_page_sections']['std'] ) ? '|' : '' ) . 'subscribe=1';
		$options['front_page_sections']['options'] = array_merge(
			$options['front_page_sections']['options'],
			array(
				'subscribe' => esc_html__( 'Subscribe', 'helion' ),
			)
		);
		$options                                   = array_merge(
			$options, array(

				// Front Page Sections - Subscribe
				'front_page_subscribe'                => array(
					'title'    => esc_html__( 'Subscribe', 'helion' ),
					'desc'     => '',
					'priority' => 80,
					'type'     => 'section',
				),
				'front_page_subscribe_layout_info'    => array(
					'title' => esc_html__( 'Layout', 'helion' ),
					'desc'  => '',
					'type'  => 'info',
				),
				'front_page_subscribe_fullheight'     => array(
					'title'   => esc_html__( 'Full height', 'helion' ),
					'desc'    => wp_kses_data( __( 'Stretch this section to the window height', 'helion' ) ),
					'std'     => 0,
					'refresh' => false,
					'type'    => 'switch',
				),
				'front_page_subscribe_stack'          => array(
					'title'      => esc_html__( 'Stack this section', 'helion' ),
					'desc'       => wp_kses_data( __( 'Add the behavior of "a stack" for this section to fix it when you scroll to the top of the screen.', 'helion' ) ),
					'std'        => 0,
					'refresh'    => false,
					'dependency' => array(
						'front_page_subscribe_fullheight' => array( 1 ),
					),
					'type'       => 'switch',
				),
				'front_page_subscribe_paddings'       => array(
					'title'   => esc_html__( 'Paddings', 'helion' ),
					'desc'    => wp_kses_data( __( 'Select paddings inside this section', 'helion' ) ),
					'std'     => 'medium',
					'options' => helion_get_list_paddings(),
					'refresh' => false,
					'type'    => 'radio',
				),
				'front_page_subscribe_heading_info'   => array(
					'title' => esc_html__( 'Title', 'helion' ),
					'desc'  => '',
					'type'  => 'info',
				),
				'front_page_subscribe_caption'        => array(
					'title'     => esc_html__( 'Section title', 'helion' ),
					'desc'      => '',
					'translate' => true,
					'refresh'   => false,
					'std'       => wp_kses_data( __( 'Subscribe to our Newsletter', 'helion' ) ),
					'type'      => 'text',
				),
				'front_page_subscribe_description'    => array(
					'title'     => esc_html__( 'Description', 'helion' ),
					'desc'      => wp_kses_data( __( "Short description after the section's title", 'helion' ) ),
					'translate' => true,
					'refresh'   => false,
					'std'       => wp_kses_data( __( 'This text can be changed in the section "Subscribe"', 'helion' ) ),
					'type'      => 'textarea',
				),
				'front_page_subscribe_shortcode_info' => array(
					'title' => esc_html__( 'Shortcode', 'helion' ),
					'desc'  => '',
					'type'  => 'info',
				),
				'front_page_subscribe_shortcode'      => array(
					'title'     => esc_html__( 'Shortcode to insert Subscribe form', 'helion' ),
					'desc'      => wp_kses_data( __( 'Paste shortcode, generated with any subscribe plugin (for example, MailChimp)', 'helion' ) ),
					'translate' => true,
					'refresh'   => '.front_page_section_subscribe .front_page_section_subscribe_output',
					'std'       => '',
					'type'      => 'text',
				),
				'front_page_subscribe_color_info'     => array(
					'title' => esc_html__( 'Colors and images', 'helion' ),
					'desc'  => '',
					'type'  => 'info',
				),
				'front_page_subscribe_scheme'         => array(
					'title'   => esc_html__( 'Color scheme', 'helion' ),
					'desc'    => wp_kses_data( __( 'Color scheme for this section', 'helion' ) ),
					'std'     => HELION_THEME_FREE ? 'dark' : 'inherit',
					'options' => array(),
					'refresh' => false,
					'type'    => 'radio',
				),
				'front_page_subscribe_bg_image'       => array(
					'title'           => esc_html__( 'Background image', 'helion' ),
					'desc'            => wp_kses_data( __( 'Select or upload background image for this section', 'helion' ) ),
					'refresh'         => '.front_page_section_subscribe',
					'refresh_wrapper' => true,
					'std'             => HELION_THEME_FREE ? helion_get_file_url( 'front-page/images/bg-subscribe.jpg' ) : '',
					'type'            => 'image',
				),
				'front_page_subscribe_bg_color_type'  => array(
					'title'   => esc_html__( 'Background color', 'helion' ),
					'desc'    => wp_kses_data( __( 'Background color for this section', 'helion' ) ),
					'std'     => HELION_THEME_FREE ? 'custom' : 'none',
					'refresh' => false,
					'options' => array(
						'none'            => esc_html__( 'None', 'helion' ),
						'scheme_bg_color' => esc_html__( 'Scheme bg color', 'helion' ),
						'custom'          => esc_html__( 'Custom', 'helion' ),
					),
					'type'    => 'radio',
				),
				'front_page_subscribe_bg_color'       => array(
					'title'      => esc_html__( 'Custom color', 'helion' ),
					'desc'       => wp_kses_data( __( 'Custom background color for this section', 'helion' ) ),
					'std'        => HELION_THEME_FREE ? '#000' : '',
					'refresh'    => false,
					'dependency' => array(
						'front_page_subscribe_bg_color_type' => array( 'custom' ),
					),
					'type'       => 'color',
				),
				'front_page_subscribe_bg_mask'        => array(
					'title'   => esc_html__( 'Background mask', 'helion' ),
					'desc'    => wp_kses_data( __( 'Use Background color as section mask with specified opacity. If 0 - mask is not being used', 'helion' ) ),
					'max'     => 1,
					'step'    => 0.1,
					'std'     => HELION_THEME_FREE ? 0.5 : 1,
					'refresh' => false,
					'type'    => 'slider',
				),
				'front_page_subscribe_anchor_info'    => array(
					'title' => esc_html__( 'Anchor', 'helion' ),
					'desc'  => wp_kses_data( __( 'You can select icon and/or specify a text to create anchor for this section and show it in the side menu (if selected in the section "Header - Menu").', 'helion' ) )
								. '<br>'
								. wp_kses_data( __( 'Attention! Anchors available only if plugin "ThemeREX Addons is installed and activated!', 'helion' ) ),
					'type'  => 'info',
				),
				'front_page_subscribe_anchor_icon'    => array(
					'title' => esc_html__( 'Anchor icon', 'helion' ),
					'desc'  => '',
					'std'   => '',
					'type'  => 'icon',
				),
				'front_page_subscribe_anchor_text'    => array(
					'title'     => esc_html__( 'Anchor text', 'helion' ),
					'desc'      => '',
					'translate' => true,
					'std'       => '',
					'type'      => 'text',
				),
			)
		);
		return $options;
	}
}



// Add section 'Google map' to the Front Page option
if ( ! function_exists( 'helion_front_page_options_googlemap' ) ) {
	if ( ! HELION_THEME_FREE ) {
		add_filter( 'helion_filter_front_page_options', 'helion_front_page_options_googlemap' );
	}
	function helion_front_page_options_googlemap( $options ) {
		$options['front_page_sections']['std']    .= ( ! empty( $options['front_page_sections']['std'] ) ? '|' : '' ) . 'googlemap=1';
		$options['front_page_sections']['options'] = array_merge(
			$options['front_page_sections']['options'],
			array(
				'googlemap' => esc_html__( 'Google map', 'helion' ),
			)
		);
		$options                                   = array_merge(
			$options, array(

				// Front Page Sections - Google map
				'sidebar-widgets-front_page_googlemap_widgets' => array(
					'title'    => esc_html__( 'Google map', 'helion' ),
					'desc'     => '',
					'priority' => 90,
					'type'     => 'section',
				),
				'front_page_googlemap_layout_info'  => array(
					'title'    => esc_html__( 'Layout', 'helion' ),
					'desc'     => '',
					'priority' => -120,
					'type'     => 'info',
				),
				'front_page_googlemap_fullheight'   => array(
					'title'    => esc_html__( 'Full height', 'helion' ),
					'desc'     => wp_kses_data( __( 'Stretch this section to the window height', 'helion' ) ),
					'std'      => 0,
					'refresh'  => false,
					'priority' => -110,
					'type'     => 'switch',
				),
				'front_page_googlemap_stack'        => array(
					'title'      => esc_html__( 'Stack this section', 'helion' ),
					'desc'       => wp_kses_data( __( 'Add the behavior of "a stack" for this section to fix it when you scroll to the top of the screen.', 'helion' ) ),
					'std'        => 0,
					'refresh'    => false,
					'dependency' => array(
						'front_page_googlemap_fullheight' => array( 1 ),
					),
					'type'       => 'switch',
				),
				'front_page_googlemap_paddings'     => array(
					'title'    => esc_html__( 'Paddings', 'helion' ),
					'desc'     => wp_kses_data( __( 'Select paddings inside this section', 'helion' ) ),
					'std'      => 'medium',
					'options'  => helion_get_list_paddings(),
					'refresh'  => false,
					'priority' => -100,
					'type'     => 'radio',
				),
				'front_page_googlemap_layout'       => array(
					'title'           => esc_html__( 'Layout', 'helion' ),
					'desc'            => wp_kses_data( __( 'Select layout of this section', 'helion' ) ),
					'std'             => 'fullwidth',
					'options'         => array(
						'fullwidth' => esc_html__( 'Fullwidth', 'helion' ),
						'boxed'     => esc_html__( 'Boxed', 'helion' ),
						'columns'   => esc_html__( '2 columns', 'helion' ),
					),
					'refresh'         => '.front_page_section_googlemap',
					'refresh_wrapper' => true,
					'priority'        => -95,
					'type'            => 'radio',
				),
				'front_page_googlemap_heading_info' => array(
					'title'    => esc_html__( 'Title', 'helion' ),
					'desc'     => '',
					'priority' => -90,
					'type'     => 'info',
				),
				'front_page_googlemap_caption'      => array(
					'title'     => esc_html__( 'Section title', 'helion' ),
					'desc'      => '',
					'translate' => true,
					'refresh'   => false,
					'std'       => wp_kses_data( __( 'Google map', 'helion' ) ),
					'priority'  => -80,
					'type'      => 'text',
				),
				'front_page_googlemap_description'  => array(
					'title'     => esc_html__( 'Description', 'helion' ),
					'desc'      => wp_kses_data( __( "Short description after the section's title", 'helion' ) ),
					'translate' => true,
					'refresh'   => false,
					'std'       => wp_kses_data( __( 'This text can be changed in the section "Google map"', 'helion' ) ),
					'priority'  => -70,
					'type'      => 'textarea',
				),
				'front_page_googlemap_content'      => array(
					'title'     => esc_html__( 'Content', 'helion' ),
					'desc'      => wp_kses_data( __( 'Any text at the left side of the map', 'helion' ) ),
					'translate' => true,
					'refresh'   => false,
					'std'       => wp_kses_data( __( 'This text can be changed in the section "Google map"', 'helion' ) ),
					'priority'  => -65,
					'type'      => 'text_editor',
				),
				'front_page_googlemap_widgets_info' => array(
					'title'    => esc_html__( 'Widgets', 'helion' ),
					'desc'     => wp_kses_data( __( 'You can setup widgets in this section in the menu "Appearance - Customize" or "Appearance - Widgets"', 'helion' ) )
								. '<br>'
								. wp_kses_data( __( 'Insert your preferred widget to display the map with the location of your choice here. You can also select any other widget, changing thus the purpose of this section', 'helion' ) ),
					'priority' => -60,
					'type'     => 'info',
				),
				'front_page_googlemap_color_info'   => array(
					'title'    => esc_html__( 'Colors and images', 'helion' ),
					'desc'     => '',
					'priority' => 100,
					'type'     => 'info',
				),
				'front_page_googlemap_scheme'       => array(
					'title'   => esc_html__( 'Color scheme', 'helion' ),
					'desc'    => wp_kses_data( __( 'Color scheme for this section', 'helion' ) ),
					'std'     => HELION_THEME_FREE ? 'dark' : 'inherit',
					'options' => array(),
					'refresh' => false,
					'type'    => 'radio',
				),
				'front_page_googlemap_bg_image'     => array(
					'title'           => esc_html__( 'Background image', 'helion' ),
					'desc'            => wp_kses_data( __( 'Select or upload background image for this section', 'helion' ) ),
					'refresh'         => '.front_page_section_googlemap',
					'refresh_wrapper' => true,
					'std'             => HELION_THEME_FREE ? helion_get_file_url( 'front-page/images/bg-googlemap.jpg' ) : '',
					'type'            => 'image',
				),
				'front_page_googlemap_bg_color_type' => array(
					'title'   => esc_html__( 'Background color', 'helion' ),
					'desc'    => wp_kses_data( __( 'Background color for this section', 'helion' ) ),
					'std'     => HELION_THEME_FREE ? 'custom' : 'none',
					'refresh' => false,
					'options' => array(
						'none'            => esc_html__( 'None', 'helion' ),
						'scheme_bg_color' => esc_html__( 'Scheme bg color', 'helion' ),
						'custom'          => esc_html__( 'Custom', 'helion' ),
					),
					'type'    => 'radio',
				),
				'front_page_googlemap_bg_color'     => array(
					'title'      => esc_html__( 'Custom color', 'helion' ),
					'desc'       => wp_kses_data( __( 'Custom background color for this section', 'helion' ) ),
					'std'        => HELION_THEME_FREE ? '#000' : '',
					'refresh'    => false,
					'dependency' => array(
						'front_page_googlemap_bg_color_type' => array( 'custom' ),
					),
					'type'       => 'color',
				),
				'front_page_googlemap_bg_mask'      => array(
					'title'   => esc_html__( 'Background mask', 'helion' ),
					'desc'    => wp_kses_data( __( 'Use Background color as section mask with specified opacity. If 0 - mask is not being used', 'helion' ) ),
					'max'     => 1,
					'step'    => 0.1,
					'std'     => HELION_THEME_FREE ? 0.5 : 1,
					'refresh' => false,
					'type'    => 'slider',
				),
				'front_page_googlemap_anchor_info'  => array(
					'title' => esc_html__( 'Anchor', 'helion' ),
					'desc'  => wp_kses_data( __( 'You can select icon and/or specify a text to create anchor for this section and show it in the side menu (if selected in the section "Header - Menu").', 'helion' ) )
								. '<br>'
								. wp_kses_data( __( 'Attention! Anchors available only if plugin "ThemeREX Addons is installed and activated!', 'helion' ) ),
					'type'  => 'info',
				),
				'front_page_googlemap_anchor_icon'  => array(
					'title' => esc_html__( 'Anchor icon', 'helion' ),
					'desc'  => '',
					'std'   => '',
					'type'  => 'icon',
				),
				'front_page_googlemap_anchor_text'  => array(
					'title'     => esc_html__( 'Anchor text', 'helion' ),
					'desc'      => '',
					'translate' => true,
					'std'       => '',
					'type'      => 'text',
				),
			)
		);
		return $options;
	}
}



// Add section 'Contact Us' to the Front Page option
if ( ! function_exists( 'helion_front_page_options_contacts' ) ) {
	add_filter( 'helion_filter_front_page_options', 'helion_front_page_options_contacts' );
	function helion_front_page_options_contacts( $options ) {
		$options['front_page_sections']['std']    .= ( ! empty( $options['front_page_sections']['std'] ) ? '|' : '' ) . 'contacts=1';
		$options['front_page_sections']['options'] = array_merge(
			$options['front_page_sections']['options'],
			array(
				'contacts' => esc_html__( 'Contact Us', 'helion' ),
			)
		);
		$options                                   = array_merge(
			$options, array(

				// Front Page Sections - Contact Us
				'sidebar-widgets-front_page_contacts_widgets' => array(
					'title'    => esc_html__( 'Contact Us', 'helion' ),
					'desc'     => '',
					'priority' => 100,
					'type'     => 'section',
				),
				'front_page_contacts_layout_info'    => array(
					'title' => esc_html__( 'Layout', 'helion' ),
					'desc'  => '',
					'type'  => 'info',
				),
				'front_page_contacts_fullheight'     => array(
					'title'   => esc_html__( 'Full height', 'helion' ),
					'desc'    => wp_kses_data( __( 'Stretch this section to the window height', 'helion' ) ),
					'std'     => 0,
					'refresh' => false,
					'type'    => 'switch',
				),
				'front_page_contacts_stack'          => array(
					'title'      => esc_html__( 'Stack this section', 'helion' ),
					'desc'       => wp_kses_data( __( 'Add the behavior of "a stack" for this section to fix it when you scroll to the top of the screen.', 'helion' ) ),
					'std'        => 0,
					'refresh'    => false,
					'dependency' => array(
						'front_page_contacts_fullheight' => array( 1 ),
					),
					'type'       => 'switch',
				),
				'front_page_contacts_paddings'       => array(
					'title'   => esc_html__( 'Paddings', 'helion' ),
					'desc'    => wp_kses_data( __( 'Select paddings inside this section', 'helion' ) ),
					'std'     => 'medium',
					'options' => helion_get_list_paddings(),
					'refresh' => false,
					'type'    => 'radio',
				),
				'front_page_contacts_layout'         => array(
					'title'           => esc_html__( 'Layout', 'helion' ),
					'desc'            => wp_kses_data( __( 'Select layout of this section', 'helion' ) ),
					'std'             => 'columns',
					'options'         => array(
						'boxed'   => esc_html__( 'Boxed', 'helion' ),
						'columns' => esc_html__( '2 columns', 'helion' ),
					),
					'refresh'         => '.front_page_section_contacts',
					'refresh_wrapper' => true,
					'type'            => 'radio',
				),
				'front_page_contacts_heading_info'   => array(
					'title' => esc_html__( 'Title', 'helion' ),
					'desc'  => '',
					'type'  => 'info',
				),
				'front_page_contacts_caption'        => array(
					'title'     => esc_html__( 'Section title', 'helion' ),
					'desc'      => '',
					'translate' => true,
					'refresh'   => false,
					'std'       => wp_kses_data( __( 'Contact Us', 'helion' ) ),
					'type'      => 'text',
				),
				'front_page_contacts_description'    => array(
					'title'     => esc_html__( 'Description', 'helion' ),
					'desc'      => wp_kses_data( __( "Short description after the section's title", 'helion' ) ),
					'translate' => true,
					'refresh'   => false,
					'std'       => wp_kses_data( __( 'This text can be changed in the section "Contact Us"', 'helion' ) ),
					'type'      => 'textarea',
				),
				'front_page_contacts_content'        => array(
					'title'   => esc_html__( 'Content', 'helion' ),
					'desc'    => wp_kses_data( __( 'Any text at the left side of the form', 'helion' ) ),
					'refresh' => false,
					'std'     => wp_kses( __( '<h5><span class="icon-home-2"> </span>Find us at the office:</h5><p>500, Lorem Street,<br />Chicago, IL, 55030<br />Mon - Fri, 09:00 - 18:00</p><h5> <span class="icon-mobile-light"> </span>Give us a call:</h5><p>Michael Jordan<br />+40 (123) 456-78-90<br />Mon - Fri, 08:00 - 22:00</p>', 'helion' ),'helion_kses_content' ),
					'type'    => 'text_editor',
				),
				'front_page_contacts_shortcode_info' => array(
					'title' => esc_html__( 'Shortcode', 'helion' ),
					'desc'  => '',
					'type'  => 'info',
				),
				'front_page_contacts_shortcode'      => array(
					'title'     => esc_html__( 'Shortcode with contact form', 'helion' ),
					'desc'      => wp_kses_data( __( 'Paste shortcode, generated with any form plugin (for example, Contacts Form 7). You can also paste any other shortcodes, changing thus the purpose of this section', 'helion' ) ),
					'translate' => true,
					'refresh'   => '.front_page_section_contacts .front_page_section_contacts_output',
					'std'       => '',
					'type'      => 'text',
				),
				'front_page_contacts_color_info'     => array(
					'title'    => esc_html__( 'Colors and images', 'helion' ),
					'desc'     => '',
					'priority' => 100,
					'type'     => 'info',
				),
				'front_page_contacts_scheme'         => array(
					'title'   => esc_html__( 'Color scheme', 'helion' ),
					'desc'    => wp_kses_data( __( 'Color scheme for this section', 'helion' ) ),
					'std'     => HELION_THEME_FREE ? 'dark' : 'inherit',
					'options' => array(),
					'refresh' => false,
					'type'    => 'radio',
				),
				'front_page_contacts_bg_image'       => array(
					'title'           => esc_html__( 'Background image', 'helion' ),
					'desc'            => wp_kses_data( __( 'Select or upload background image for this section', 'helion' ) ),
					'refresh'         => '.front_page_section_contacts',
					'refresh_wrapper' => true,
					'std'             => '',
					'type'            => 'image',
				),
				'front_page_contacts_bg_color_type'  => array(
					'title'   => esc_html__( 'Background color', 'helion' ),
					'desc'    => wp_kses_data( __( 'Background color for this section', 'helion' ) ),
					'std'     => HELION_THEME_FREE ? 'custom' : 'none',
					'refresh' => false,
					'options' => array(
						'none'            => esc_html__( 'None', 'helion' ),
						'scheme_bg_color' => esc_html__( 'Scheme bg color', 'helion' ),
						'custom'          => esc_html__( 'Custom', 'helion' ),
					),
					'type'    => 'radio',
				),
				'front_page_contacts_bg_color'       => array(
					'title'      => esc_html__( 'Custom color', 'helion' ),
					'desc'       => wp_kses_data( __( 'Custom background color for this section', 'helion' ) ),
					'std'        => HELION_THEME_FREE ? '#000' : '',
					'refresh'    => false,
					'dependency' => array(
						'front_page_contacts_bg_color_type' => array( 'custom' ),
					),
					'type'       => 'color',
				),
				'front_page_contacts_bg_mask'        => array(
					'title'   => esc_html__( 'Background mask', 'helion' ),
					'desc'    => wp_kses_data( __( 'Use Background color as section mask with specified opacity. If 0 - mask is not being used', 'helion' ) ),
					'max'     => 1,
					'step'    => 0.1,
					'std'     => HELION_THEME_FREE ? 0.5 : 1,
					'refresh' => false,
					'type'    => 'slider',
				),
				'front_page_contacts_anchor_info'    => array(
					'title' => esc_html__( 'Anchor', 'helion' ),
					'desc'  => wp_kses_data( __( 'You can select icon and/or specify a text to create anchor for this section and show it in the side menu (if selected in the section "Header - Menu").', 'helion' ) )
								. '<br>'
								. wp_kses_data( __( 'Attention! Anchors available only if plugin "ThemeREX Addons is installed and activated!', 'helion' ) ),
					'type'  => 'info',
				),
				'front_page_contacts_anchor_icon'    => array(
					'title' => esc_html__( 'Anchor icon', 'helion' ),
					'desc'  => '',
					'std'   => '',
					'type'  => 'icon',
				),
				'front_page_contacts_anchor_text'    => array(
					'title'     => esc_html__( 'Anchor text', 'helion' ),
					'desc'      => '',
					'translate' => true,
					'std'       => '',
					'type'      => 'text',
				),
			)
		);
		return $options;
	}
}

// Add 'active_callback' to all Front Page options
if ( ! function_exists( 'helion_front_page_options_add_active_callback' ) ) {
	add_filter( 'helion_filter_front_page_options', 'helion_front_page_options_add_active_callback', 1000 );
	function helion_front_page_options_add_active_callback( $options ) {
		foreach ( $options as $k => $v ) {
			if ( substr( $k, 0, 11 ) == 'front_page_' ) {
				$options[ $k ]['active_callback'] = 'helion_front_page_check';
			}
		}
		return $options;
	}
}

// Callback to show/hide Front Page sections in the WP Customizer
if ( ! function_exists( 'helion_front_page_check' ) ) {
	function helion_front_page_check( $control = null ) {
		return true;
	}
}

// Add Front Page specific items to the list of sidebars
//------------------------------------------------------------------------
if ( ! function_exists( 'helion_front_page_sidebars' ) ) {
	
	function helion_front_page_sidebars( $list = array() ) {
		$list['front_page_features_widgets']     = array(
			'name'               => esc_html__( 'Front Page section "Features"', 'helion' ),
			'description'        => esc_html__( 'Widgets to be shown only in the section "Features" on the front page', 'helion' ),
			'front_page_section' => true,
		);
		$list['front_page_team_widgets']         = array(
			'name'               => esc_html__( 'Front Page section "Team members"', 'helion' ),
			'description'        => esc_html__( 'Widgets to be shown only in the section "Team members" on the front page', 'helion' ),
			'front_page_section' => true,
		);
		$list['front_page_testimonials_widgets'] = array(
			'name'               => esc_html__( 'Front Page section "Testimonials"', 'helion' ),
			'description'        => esc_html__( 'Widgets to be shown only in the section "Testimonials" on the front page', 'helion' ),
			'front_page_section' => true,
		);
		$list['front_page_blog_widgets']         = array(
			'name'               => esc_html__( 'Front Page section "Latest Posts"', 'helion' ),
			'description'        => esc_html__( 'Widgets to be shown only in the section "Latest Posts" on the front page', 'helion' ),
			'front_page_section' => true,
		);
		if ( ! HELION_THEME_FREE ) {
			$list['front_page_googlemap_widgets'] = array(
				'name'               => esc_html__( 'Front Page section "Google map"', 'helion' ),
				'description'        => esc_html__( 'Widgets to be shown only in the section "Google map" on the front page', 'helion' ),
				'front_page_section' => true,
			);
		}
		return $list;
	}
}




//====================================================================
//== Refresh partials on the Front Page
//====================================================================


// Partial refresh whole section
if ( ! function_exists( 'helion_customizer_partial_refresh_section' ) ) {
	function helion_customizer_partial_refresh_section( $section ) {
		ob_start();
		get_template_part( apply_filters( 'helion_filter_get_template_part', "front-page/section-{$section}" ) );
		$output = ob_get_contents();
		ob_end_clean();
		return helion_customizer_partial_refresh_add_init_script( $output, $section );
	}
}


// Add init script to the section's html output
if ( ! function_exists( 'helion_customizer_partial_refresh_add_init_script' ) ) {
	function helion_customizer_partial_refresh_add_init_script( $output, $section ) {
		return sprintf(
			"%1$s<%2$s>
						setTimeout(function() {
							jQuery(document).trigger('action.init_hidden_elements', [jQuery('.front_page_section_{$section}')]);
						}, 500);
					</%2$s>", $output, 'script'
		);
	}
}


// Section 'Front Page - Title'
//--------------------------------------------------------------------



// Button1 link
if ( ! function_exists( 'helion_customizer_partial_refresh_front_page_title_button1_link' ) ) {
	function helion_customizer_partial_refresh_front_page_title_button1_link() {
		return helion_get_theme_option( 'front_page_title_button1_link' ) != ''
				? '<a href="' . esc_url( helion_get_theme_option( 'front_page_title_button1_link' ) ) . '" class="theme_button front_page_section_button front_page_section_title_button1">'
					. esc_html( helion_get_theme_option( 'front_page_title_button1_caption' ) )
					. '</a>'
				: '';
	}
}

// Button2 link
if ( ! function_exists( 'helion_customizer_partial_refresh_front_page_title_button2_link' ) ) {
	function helion_customizer_partial_refresh_front_page_title_button2_link() {
		return helion_get_theme_option( 'front_page_title_button2_link' ) != ''
				? '<a href="' . esc_url( helion_get_theme_option( 'front_page_title_button2_link' ) ) . '" class="theme_button color_style_link2 front_page_section_button front_page_section_title_button2">'
					. esc_html( helion_get_theme_option( 'front_page_title_button2_caption' ) )
					. '</a>'
				: '';
	}
}

// Background image
if ( ! function_exists( 'helion_customizer_partial_refresh_front_page_title_bg_image' ) ) {
	function helion_customizer_partial_refresh_front_page_title_bg_image() {
		return helion_customizer_partial_refresh_section( 'title' );
	}
}


// Section 'Front Page - About'
//--------------------------------------------------------------------

// Background image
if ( ! function_exists( 'helion_customizer_partial_refresh_front_page_about_bg_image' ) ) {
	function helion_customizer_partial_refresh_front_page_about_bg_image() {
		return helion_customizer_partial_refresh_section( 'about' );
	}
}


// Section 'Front Page - Features'
//--------------------------------------------------------------------

// Background image
if ( ! function_exists( 'helion_customizer_partial_refresh_front_page_features_bg_image' ) ) {
	function helion_customizer_partial_refresh_front_page_features_bg_image() {
		return helion_customizer_partial_refresh_section( 'features' );
	}
}


// Section 'Front Page - Team'
//--------------------------------------------------------------------

// Background image
if ( ! function_exists( 'helion_customizer_partial_refresh_front_page_team_bg_image' ) ) {
	function helion_customizer_partial_refresh_front_page_team_bg_image() {
		return helion_customizer_partial_refresh_section( 'team' );
	}
}


// Section 'Front Page - Testimonials'
//--------------------------------------------------------------------

// Background image
if ( ! function_exists( 'helion_customizer_partial_refresh_front_page_testimonials_bg_image' ) ) {
	function helion_customizer_partial_refresh_front_page_testimonials_bg_image() {
		return helion_customizer_partial_refresh_section( 'testimonials' );
	}
}


// Section 'Front Page - Latest posts'
//--------------------------------------------------------------------

// Background image
if ( ! function_exists( 'helion_customizer_partial_refresh_front_page_blog_bg_image' ) ) {
	function helion_customizer_partial_refresh_front_page_blog_bg_image() {
		return helion_customizer_partial_refresh_section( 'blog' );
	}
}


// Section 'Front Page - Subscribe'
//--------------------------------------------------------------------

// Shortcode changed
if ( ! function_exists( 'helion_customizer_partial_refresh_front_page_subscribe_shortcode' ) ) {
	function helion_customizer_partial_refresh_front_page_subscribe_shortcode() {
		$helion_sc = helion_get_theme_option( 'front_page_subscribe_shortcode' );
		return ! empty( $helion_sc ) ? do_shortcode( $helion_sc ) : '';
	}
}

// Background image
if ( ! function_exists( 'helion_customizer_partial_refresh_front_page_subscribe_bg_image' ) ) {
	function helion_customizer_partial_refresh_front_page_subscribe_bg_image() {
		return helion_customizer_partial_refresh_section( 'subscribe' );
	}
}


// Section 'Front Page - Google map'
//--------------------------------------------------------------------

// Layout
if ( ! function_exists( 'helion_customizer_partial_refresh_front_page_googlemap_layout' ) ) {
	function helion_customizer_partial_refresh_front_page_googlemap_layout() {
		return helion_customizer_partial_refresh_section( 'googlemap' );
	}
}

// Background image
if ( ! function_exists( 'helion_customizer_partial_refresh_front_page_googlemap_bg_image' ) ) {
	function helion_customizer_partial_refresh_front_page_googlemap_bg_image() {
		return helion_customizer_partial_refresh_section( 'googlemap' );
	}
}


// Section 'Front Page - Contact Us'
//--------------------------------------------------------------------

// Layout
if ( ! function_exists( 'helion_customizer_partial_refresh_front_page_contacts_layout' ) ) {
	function helion_customizer_partial_refresh_front_page_contacts_layout() {
		return helion_customizer_partial_refresh_section( 'contacts' );
	}
}

// Shortcode changed
if ( ! function_exists( 'helion_customizer_partial_refresh_front_page_contacts_shortcode' ) ) {
	function helion_customizer_partial_refresh_front_page_contacts_shortcode() {
		$helion_sc = helion_get_theme_option( 'front_page_contacts_shortcode' );
		return ! empty( $helion_sc ) ? do_shortcode( $helion_sc ) : '';
	}
}

// Background image
if ( ! function_exists( 'helion_customizer_partial_refresh_front_page_contacts_bg_image' ) ) {
	function helion_customizer_partial_refresh_front_page_contacts_bg_image() {
		return helion_customizer_partial_refresh_section( 'contacts' );
	}
}


// Section 'Front Page - WooCommerce'
//--------------------------------------------------------------------

// Background image
if ( ! function_exists( 'helion_customizer_partial_refresh_front_page_woocommerce_bg_image' ) ) {
	function helion_customizer_partial_refresh_front_page_woocommerce_bg_image() {
		return helion_customizer_partial_refresh_section( 'woocommerce' );
	}
}


// Front Page styles
//--------------------------------------------------------------------

// Enqueue styles for frontend
if ( !function_exists( 'helion_front_page_frontend_scripts' ) ) {
	add_action( 'wp_enqueue_scripts', 'helion_front_page_frontend_scripts', 1100 );
	function helion_front_page_frontend_scripts() {
		if ( is_front_page() && !is_home() ) {
			if ( helion_is_on( helion_get_theme_option( 'debug_mode' ) ) ) {
				$helion_url = helion_get_file_url( 'front-page/front-page.css' );
				if ( '' != $helion_url ) {
					wp_enqueue_style( 'helion-front-page',  $helion_url, array(), null );
				}
			}
		}
	}
}

// Enqueue responsive styles for frontend
if ( !function_exists( 'helion_front_page_responsive_scripts' ) ) {
	add_action( 'wp_enqueue_scripts', 'helion_front_page_responsive_scripts', 2000 );
	function helion_front_page_responsive_scripts() {
		if ( is_front_page() && !is_home() ) {
			if ( helion_is_on( helion_get_theme_option( 'debug_mode' ) ) ) {
				$helion_url = helion_get_file_url( 'front-page/front-page-responsive.css' );
				if ( '' != $helion_url ) {
					wp_enqueue_style( 'helion-front-page-responsive',  $helion_url, array(), null );
				}
			}
		}
	}
}

// Merge styles
if ( ! function_exists( 'helion_front_page_merge_styles' ) ) {
	add_filter( 'helion_filter_merge_styles', 'helion_front_page_merge_styles', 9, 1 );
	function helion_front_page_merge_styles( $list ) {
		$list[] = 'front-page/front-page.css';
		return $list;
	}
}

// Merge responsive styles
if ( ! function_exists( 'helion_front_page_merge_styles_responsive' ) ) {
	add_filter( 'helion_filter_merge_styles_responsive', 'helion_front_page_merge_styles_responsive', 9, 1 );
	function helion_front_page_merge_styles_responsive( $list ) {
		$list[] = 'front-page/front-page-responsive.css';
		return $list;
	}
}
