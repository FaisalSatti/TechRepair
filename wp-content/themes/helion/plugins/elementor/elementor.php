<?php
/* Elementor Builder support functions
------------------------------------------------------------------------------- */

if ( ! defined( 'HELION_ELEMENTOR_PADDINGS' ) ) {
	define( 'HELION_ELEMENTOR_PADDINGS', 15 );
}

// Theme init priorities:
// 1 - register filters to add/remove lists items in the Theme Options
if ( ! function_exists( 'helion_elm_theme_setup1' ) ) {
	add_action( 'after_setup_theme', 'helion_elm_theme_setup1', 1 );
	function helion_elm_theme_setup1() {
		if ( helion_exists_elementor() ) {
			add_filter( 'helion_filter_update_post_options', 'helion_elm_update_post_options', 10, 2 );
			add_action( 'helion_action_just_save_options', 'helion_elm_just_save_options', 10, 1 );
		}
	}
}

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'helion_elm_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'helion_elm_theme_setup9', 9 );
	function helion_elm_theme_setup9() {

		add_filter( 'trx_addons_filter_force_load_elementor_styles', 'helion_elm_force_load_elementor_styles' );

		if ( helion_exists_elementor() ) {
			add_action( 'wp_enqueue_scripts', 'helion_elm_frontend_scripts', 1100 );
			add_action( 'wp_enqueue_scripts', 'helion_elm_responsive_styles', 2000 );
			add_filter( 'helion_filter_merge_styles', 'helion_elm_merge_styles' );
			add_filter( 'helion_filter_merge_styles_responsive', 'helion_elm_merge_styles_responsive' );

			add_action( 'init', 'helion_elm_init_once', 3 );
			add_action( 'elementor/editor/before_enqueue_scripts', 'helion_elm_editor_scripts' );

			// before Elementor 2.0.0
			add_filter( 'elementor/page/settings/success_response_data', 'helion_elm_page_options_save', 10, 3 );
			add_filter( 'elementor/general/settings/success_response_data', 'helion_elm_general_options_save', 10, 3 );
			// after Elementor 2.0.0
			add_filter( 'elementor/settings/page/success_response_data', 'helion_elm_page_options_save', 10, 3 );
			add_filter( 'elementor/settings/post/success_response_data', 'helion_elm_page_options_save', 10, 3 );
			add_filter( 'elementor/settings/general/success_response_data', 'helion_elm_general_options_save', 10, 3 );
			add_filter( 'elementor/documents/ajax_save/return_data', 'helion_elm_page_options_save_document', 10, 2 );

			add_filter( 'helion_filter_post_edit_link', 'helion_elm_post_edit_link', 10, 2 );
			
			add_action( 'elementor/element/before_section_end', 'helion_elm_add_color_scheme_control', 10, 3 );

			add_filter( 'trx_addons_sc_param_group_params', 'helion_trx_addons_sc_param_group_params', 10, 2 );

			// Add theme-specific page options.
			// Remove check is_admin() if have any problem with page-specific options!!!
			if ( is_admin() ) {
				add_action( 'elementor/element/after_section_end', 'helion_elm_add_page_options', 10, 3 );
			}
		}
		if ( is_admin() ) {
			add_filter( 'helion_filter_tgmpa_required_plugins', 'helion_elm_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'helion_elm_tgmpa_required_plugins' ) ) {
	
	function helion_elm_tgmpa_required_plugins( $list = array() ) {
		if ( helion_storage_isset( 'required_plugins', 'elementor' ) && helion_storage_get_array( 'required_plugins', 'elementor', 'install' ) !== false ) {
			$list[] = array(
				'name'     => helion_storage_get_array( 'required_plugins', 'elementor', 'title' ),
				'slug'     => 'elementor',
				'required' => false,
			);
		}
		return $list;
	}
}

// Check if Elementor is installed and activated
if ( ! function_exists( 'helion_exists_elementor' ) ) {
	function helion_exists_elementor() {
		return class_exists( 'Elementor\Plugin' );
	}
}

// Return true if Elementor exists and current mode is preview
if ( ! function_exists( 'helion_elementor_is_preview' ) ) {
	function helion_elementor_is_preview() {
		return helion_exists_elementor()
				&& ( \Elementor\Plugin::instance()->preview->is_preview_mode()
					|| ( helion_get_value_gp( 'post' ) > 0
						&& helion_get_value_gp( 'action' ) == 'elementor'
						)
					);
	}
}

// Enqueue styles for frontend
if ( ! function_exists( 'helion_elm_frontend_scripts' ) ) {
	
	function helion_elm_frontend_scripts() {
		if ( helion_is_on( helion_get_theme_option( 'debug_mode' ) ) ) {
			$helion_url = helion_get_file_url( 'plugins/elementor/elementor.css' );
			if ( '' != $helion_url ) {
				wp_enqueue_style( 'helion-elementor', $helion_url, array(), null );
			}
		}
	}
}

// Enqueue responsive styles for frontend
if ( ! function_exists( 'helion_elm_responsive_styles' ) ) {
	
	function helion_elm_responsive_styles() {
		if ( helion_is_on( helion_get_theme_option( 'debug_mode' ) ) ) {
			$helion_url = helion_get_file_url( 'plugins/elementor/elementor-responsive.css' );
			if ( '' != $helion_url ) {
				wp_enqueue_style( 'helion-elementor-responsive', $helion_url, array(), null );
			}
		}
	}
}

// Merge custom styles
if ( ! function_exists( 'helion_elm_merge_styles' ) ) {
	
	function helion_elm_merge_styles( $list ) {
		$list[] = 'plugins/elementor/elementor.css';
		return $list;
	}
}

// Merge responsive styles
if ( ! function_exists( 'helion_elm_merge_styles_responsive' ) ) {
	
	function helion_elm_merge_styles_responsive( $list ) {
		$list[] = 'plugins/elementor/elementor-responsive.css';
		return $list;
	}
}


// Load required styles and scripts for Elementor Editor mode
if ( ! function_exists( 'helion_elm_editor_scripts' ) ) {
	
	function helion_elm_editor_scripts() {
		// Load font icons
		wp_enqueue_style( 'fontello-icons', helion_get_file_url( 'css/font-icons/css/fontello.css' ), array(), null );
		wp_enqueue_script( 'helion-elementor-editor', helion_get_file_url( 'plugins/elementor/elementor-editor.js' ), array( 'jquery' ), null, true );
		helion_admin_scripts();
		helion_admin_localize_scripts();
	}
}


// Return true if current page use header or footer from Elementor and need to load Elementor styles
if ( ! function_exists( 'helion_elm_force_load_elementor_styles' ) ) {
	
	function helion_elm_force_load_elementor_styles( $need ) {
		if ( ! $need ) {
			$need = ! is_singular() || ! \Elementor\Plugin::instance()->db->is_built_with_elementor( get_the_ID() );
		}
		if ( $need ) {
			$need = false;
			$header_id = helion_get_custom_header_id();
			$need = 0 < $header_id && \Elementor\Plugin::instance()->db->is_built_with_elementor( $header_id );
			if ( ! $need ) {
				$footer_id = helion_get_custom_footer_id();
				$need = 0 < $footer_id && \Elementor\Plugin::instance()->db->is_built_with_elementor( $footer_id );
			}
		}
		return $need;
	}
}


// Set Elementor's options at once
if ( ! function_exists( 'helion_elm_init_once' ) ) {
	
	function helion_elm_init_once() {
		if ( helion_exists_elementor() && ! get_option( 'helion_setup_elementor_options', false ) ) {
			// Set theme-specific values to the Elementor's options
			update_option( 'elementor_disable_color_schemes', 'yes' );
			update_option( 'elementor_disable_typography_schemes', 'yes' );
			update_option( 'elementor_container_width', helion_get_theme_option( 'page_width' ) + 2 * HELION_ELEMENTOR_PADDINGS );    // Theme-specific width + paddings of the columns
			update_option( 'elementor_space_between_widgets', 0 );
			update_option( 'elementor_stretched_section_container', '.page_wrap' );
			update_option( 'elementor_page_title_selector', '.sc_layouts_title_caption' );
			// Set flag to prevent change Elementor's options again
			update_option( 'helion_setup_elementor_options', 1 );
		}
	}
}


// Modify Elementor's options after the Theme Options saved
if ( ! function_exists( 'helion_elm_just_save_options' ) ) {
	
	function helion_elm_just_save_options( $values ) {
		$w = ! empty( $values['page_width'] )
			? $values['page_width']
			: helion_get_theme_option_std( 'page_width', helion_storage_get_array( 'options', 'page_width', 'std' ) );
		if ( ! empty( $w ) ) {
			// Theme-specific width + 2 * paddings of the columns
			update_option( 'elementor_container_width', $w + 2 * HELION_ELEMENTOR_PADDINGS );
		}
	}
}


// Save General Options via AJAX from Elementor Editor
// (called when any option is changed)
if ( ! function_exists( 'helion_elm_general_options_save' ) ) {
	
	
	function helion_elm_general_options_save( $response_data, $post_id, $data ) {
		if ( ! empty( $data['elementor_container_width'] ) && helion_get_theme_option( 'page_width' ) + 2 * HELION_ELEMENTOR_PADDINGS != $data['elementor_container_width'] ) {
			set_theme_mod( 'page_width', $data['elementor_container_width'] - 2 * HELION_ELEMENTOR_PADDINGS );   // Elementor width - paddings of the columns
		}
		return $response_data;
	}
}


// Add theme-specific controls to sections and columns
if ( ! function_exists( 'helion_elm_add_color_scheme_control' ) ) {
	
	function helion_elm_add_color_scheme_control( $element, $section_id, $args ) {
		if ( is_object( $element ) ) {
			$el_name = $element->get_name();
			// Add color scheme selector
			if ( apply_filters(
				'helion_filter_add_scheme_in_elements',
				( in_array( $el_name, array( 'section', 'column' ) ) && 'section_advanced' === $section_id )
							|| ( 'common' === $el_name && '_section_style' === $section_id ),
				$element, $section_id, $args
			) ) {
				$element->add_control(
					'scheme_heading',
					array(
						'label' => esc_html__( 'Theme-specific params', 'helion' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					)
				);
				$element->add_control(
					'scheme', array(
						'type'         => \Elementor\Controls_Manager::SELECT,
						'label'        => esc_html__( 'Color scheme', 'helion' ),
						'label_block'  => false,
						'options'      => helion_array_merge( array( '' => esc_html__( 'Inherit', 'helion' ) ), helion_get_list_schemes() ),
						'render_type'  => 'template',	// ( none | ui | template ) - reload template after parameter is changed
						'default'      => '',
						'prefix_class' => 'scheme_',
					)
				);
			}
			// Add 'Override section width'
			if ( 'section' == $el_name && 'section_advanced' === $section_id ) {
				$element->add_control(
					'justify_columns', array(
						'type'         => \Elementor\Controls_Manager::SWITCHER,
						'label'        => esc_html__( 'Justify columns', 'helion' ),
						'label_block'  => false,
						'description'  => wp_kses_data( __( 'Stretch columns to align the left and right edges to the site content area', 'helion' ) ),
						'label_off'    => esc_html__( 'Off', 'helion' ),
						'label_on'     => esc_html__( 'On', 'helion' ),
						'return_value' => 'justified',
						'prefix_class' => 'elementor-section-',
					)
				);
			}

            // Add Param'Always fixed column ' to the columns
            if ('column' == $el_name && $section_id == 'section_trx_layout') {
                $element->add_control(
                    'fix_column_always', array(
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'label' => esc_html__('Always fixed column', 'helion'),
                        'description' => wp_kses_data( __ ("Always fix this column when page scrolling, the column has 100vH", 'helion')),
                        'label_on' => esc_html__('Fix', 'helion'),
                        'label_off' => esc_html__('No', 'helion'),
                        'return_value' => 'fixed_always',
                        'prefix_class' => 'sc_column_',
                ));
            }

            if ('trx_sc_services' == $el_name && $section_id == 'section_sc_services_details') {
                $element->add_control(
                    'icon_link', array(
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'label' => esc_html__('Iconic Link', 'helion'),
                        'label_off' => esc_html__('Off', 'helion'),
                        'label_on' => esc_html__('On', 'helion'),
                        'return_value' => '1',
                        'condition' => [
                            'type' => ['modern'],
                            'no_links' => ''
                        ],
                    )
                );
            }

            if ('trx_sc_services' == $el_name && $section_id == 'section_sc_services') {
                $element->update_control(
                    'featured', array(
                        'condition' => [
                            'type' => ['default', 'modern', 'callouts', 'hover', 'light', 'list', 'iconed', 'tabs', 'tabs_simple', 'timeline']
                        ],
                    )
                );
            }
            if ('trx_sc_services' == $el_name && $section_id == 'section_sc_services') {
                $element->update_control(
                    'columns', array(
                        'condition' => [
                            'type' => ['default', 'modern', 'callouts','light','list','iconed','hover','chess','timeline']
                        ],
                    )
                );
            }
            if ('trx_sc_services' == $el_name && $section_id == 'section_slider_params') {
                $element->update_control(
                    'slider', array(
                        'condition' => [
                            'type' => ['default', 'modern','callouts','light','list','iconed','hover','chess','timeline']
                        ],
                    )
                );
            }


			// Add 'Color style'
			if ( in_array($el_name, array(
										'trx_sc_action',
										'trx_sc_blogger',
										'trx_sc_cars',
										'trx_sc_courses',
										'trx_sc_content',
										'trx_sc_countdown',
										'trx_sc_dishes',
										'trx_sc_events',
										'trx_sc_form',
										'trx_sc_icons',
										'trx_sc_googlemap',
										'trx_sc_yandexmap',
										'trx_sc_osmap',
										'trx_sc_portfolio',
										'trx_sc_price',
										'trx_sc_promo',
										'trx_sc_properties',
										'trx_sc_services',
										'trx_sc_skills',
										'trx_sc_socials',
										'trx_sc_table',
										'trx_sc_team',
										'trx_sc_testimonials',
										'trx_sc_title',
										'trx_widget_audio',
										'trx_widget_twitter'))
				&& in_array( $section_id, array( 'section_sc_button', 'section_sc_promo', 'section_sc_title', 'section_title_params' ) )
			) {
				$element->add_control(
					'color_style', array(
						'type'         => \Elementor\Controls_Manager::SELECT,
						'label'        => esc_html__( 'Color style', 'helion' ),
						'label_block'  => false,
						'options'      => helion_get_list_sc_color_styles(),
						'default'      => 'default',
					)
				);
			}
			// Set default gap between columns to 'Extended'
			if ( 'section' == $el_name && 'section_layout' === $section_id ) {
				$element->update_control(
					'gap', array(
						'default' => 'extended',
					)
				);
			}
			// Add one more classname to the selector for paddings of columns
			// to override theme-specific rules
			if ( 'column' == $el_name && 'section_advanced' == $section_id ) {
				$element->update_responsive_control( 'padding', array(
											'selectors' => array(
												'{{WRAPPER}} > .elementor-element-populated.elementor-column-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
											)
										) );
			}

		}
	}
}

// Add theme-specific controls after section start
if ( ! function_exists( 'helion_skin_elm_add_param_control_after' ) ) {
    add_action('elementor/element/after_section_start', 'helion_skin_elm_add_param_control_after', 10, 3);
    function helion_skin_elm_add_param_control_after($element, $section_id, $args)
    {
        if (is_object($element)) {
            $el_name = $element->get_name();

            if ('trx_sc_services' == $el_name && $section_id == 'section_sc_services_details') {
                $element->add_control(
                    'show_subtitle', array(
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'label' => esc_html__('Subtitle', 'helion'),
                        'label_off' => esc_html__('Show', 'helion'),
                        'label_on' => esc_html__('Hide', 'helion'),
                        'return_value' => '1',
                        'condition' => [
                            'type' => ['modern']
                        ],
                    )
                );

            }
        }
    }
}


// Add param 'color_style' to the shortcode 'Button' in the Elementor
if ( ! function_exists( 'helion_trx_addons_sc_param_group_params' ) ) {
	
	function helion_trx_addons_sc_param_group_params( $params, $sc ) {
		// Param 'color_style'
		if ( in_array( $sc, array( 'trx_sc_button' ) ) ) {
			// If it's Elementor's params
			if ( isset( $params[0]['name'] ) && isset( $params[0]['label'] ) ) {
				array_splice($params, 1, 0, array( array(
					'name'        => 'color_style',
					'type'        => \Elementor\Controls_Manager::SELECT,
					'label'       => esc_html__( 'Color style', 'helion' ),
					'label_block' => false,
					'options'     => helion_get_list_sc_color_styles(),
					'default'     => 'default',
				) ) );
			}
		}
		return $params;
	}
}
//Hide Params in Elementor
if ( !function_exists('helion_elm_hide_params_dependencies_in_section' ) ) {
    add_action('elementor/element/before_section_end', 'helion_elm_hide_params_dependencies_in_section', 10, 3);
    function helion_elm_hide_params_dependencies_in_section($element, $section_id, $args)
    {

        //Slider Params dependencies
        if ('trx_sc_layouts_search' == $element->get_name() && 'section_sc_layouts_search' == $section_id) {

            $element->update_control(
                'style',
                [
                    'condition' => [
                        'type' => ['default'],
                    ]
                ]
            );
            $element->update_control(
                'ajax',
                [
                    'condition' => [
                        'type' => ['default'],
                    ]
                ]
            );
            $element->update_control(
                'post_types',
                [
                    'condition' => [
                        'type' => ['default'],
                    ]
                ]
            );
        }

    }
}


// Return url with post edit link
if ( ! function_exists( 'helion_elm_post_edit_link' ) ) {
	
	function helion_elm_post_edit_link( $link, $post_id ) {
		if ( \Elementor\Plugin::instance()->db->is_built_with_elementor( $post_id ) ) {
			$link = str_replace( 'action=edit', 'action=elementor', $link );
		}
		return $link;
	}
}



// Add tab with theme-specific Page Options to the Page Settings
//---------------------------------------------------------------
if ( ! function_exists( 'helion_elm_add_page_options' ) ) {
	
	function helion_elm_add_page_options( $element, $section_id, $args ) {
		if ( is_object( $element ) ) {
			$el_name = $element->get_name();
			if ( in_array( $el_name, array( 'page-settings', 'post', 'wp-post', 'wp-page' ) ) && 'section_page_style' == $section_id ) {
				$post_id   = get_the_ID();
				$post_type = get_post_type( $post_id );
				if ( $post_id > 0 && helion_options_allow_override( $post_type ) ) {
					// Load saved options
					$meta     = get_post_meta( $post_id, 'helion_options', true );
					$sections = array();
					global $HELION_STORAGE;
					// Refresh linked data if this field is controller for the another (linked) field
					// Do this before show fields to refresh data in the $HELION_STORAGE
					foreach ( $HELION_STORAGE['options'] as $k => $v ) {
						if ( ! isset( $v['override'] ) || strpos( $v['override']['mode'], $post_type ) === false ) {
							continue;
						}
						if ( ! empty( $v['linked'] ) ) {
							$v['val'] = isset( $meta[ $k ] ) ? $meta[ $k ] : 'inherit';
							if ( ! empty( $v['val'] ) && ! helion_is_inherit( $v['val'] ) ) {
								helion_refresh_linked_data( $v['val'], $v['linked'] );
							}
						}
					}
					// Collect fields to the tabs
					foreach ( $HELION_STORAGE['options'] as $k => $v ) {
						if ( ! isset( $v['override'] ) || strpos( $v['override']['mode'], $post_type ) === false || 'hidden' == $v['type'] ) {
							continue;
						}
						$sec = empty( $v['override']['section'] ) ? esc_html__( 'General', 'helion' ) : $v['override']['section'];
						if ( ! isset( $sections[ $sec ] ) ) {
							$sections[ $sec ] = array();
						}
						$v['val']               = isset( $meta[ $k ] ) ? $meta[ $k ] : 'inherit';
						$sections[ $sec ][ $k ] = $v;
					}
					if ( count( $sections ) > 0 ) {
						$cnt = 0;
						foreach ( $sections as $sec => $v ) {
							$cnt++;
							$element->start_controls_section(
								"section_theme_options_{$cnt}",
								array(
									'label' => $sec,
									'tab'   => \Elementor\Controls_Manager::TAB_ADVANCED,
								)
							);
							foreach ( $v as $field_id => $params ) {
								helion_elm_add_page_options_field( $element, $field_id, $params );
							}
							$element->end_controls_section();
						}
					}
				}
			}
		}
	}
}


// Add control for the specified field
if ( ! function_exists( 'helion_elm_add_page_options_field' ) ) {
	function helion_elm_add_page_options_field( $element, $id, $field ) {
		$id_field    = "helion_options_field_{$id}";
		$id_override = "helion_options_override_{$id}";
		// If fields is inherit
		$inherit_state = isset( $field['val'] ) && helion_is_inherit( $field['val'] );
		// Condition
		$condition = array();
		if ( ! empty( $field['dependency'] ) ) {
			foreach ( $field['dependency'] as $k => $v ) {
				$key = substr( $k, 0, 1 ) == '#'
							? str_replace( array( '#page_template', '#' ), array( 'template', '' ), $k )
							: "helion_options_field_{$k}";
				if ( is_array( $v ) ) {
					if ( is_string($v[0]) && '^' == $v[0][0] ) {
						$v[0] = substr( $v[0], 1);
						$key .= '!';
					}					
				} else {
					if ( is_string($v) && '^' == $v[0] ) {
						$v = substr( $v, 1);
						$key .= '!';
					}
				}
				$condition[ $key ] = $v;
			}
		}
		// Inherit param
		$element->add_control(
			$id_override, array(
				'label'        => $field['title'],
				'label_block'  => in_array( $field['type'], array( 'media' ) ),
				'description'  => ! empty( $field['override']['desc'] ) ? $field['override']['desc'] : '', //( ! empty( $field['desc'] ) ? $field['desc'] : '' ),
				'separator'    => 'before',
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_off'    => esc_html__( 'Inherit', 'helion' ),
				'label_on'     => esc_html__( 'Override', 'helion' ),
				'return_value' => '1',
				'condition'    => $condition,
			)
		);

		// Field params
		$params = array(
			'label'       => esc_html__( 'New value', 'helion' ),
			'label_block' => in_array( $field['type'], array( 'media', 'info' ) ),
			'description' => ! empty( $field['desc'] ) ? $field['desc'] : '',
		);

		// Add dependency to params
		$condition[ $id_override ] = '1';
		$params['condition']       = $condition;

        // Type 'checkbox' or 'switch'
        if ( 'checkbox' == $field['type'] || 'switch' == $field['type'] ) {
            $params = array_merge(
                $params, array(
                    'type'         => \Elementor\Controls_Manager::SELECT,
                    'options'      => array(
                        '0' => esc_html__( 'Off', 'helion' ),
                        '1' => esc_html__( 'On', 'helion' ),
                    ),
                )
            );
            $element->add_control( $id_field, $params );
			// Type 'radio' (2+ choises) or 'select'
		} elseif ( in_array( $field['type'], array( 'radio', 'select' ) ) ) {
			$field['options'] = apply_filters( 'helion_filter_options_get_list_choises', $field['options'], $id );
			$params           = array_merge(
				$params, array(
					'type'    => \Elementor\Controls_Manager::SELECT,
					'options' => $field['options'],
				)
			);
			$element->add_control( $id_field, $params );

			// Type 'checklist', 'select2' and 'icon'
		} elseif ( in_array( $field['type'], array( 'checklist', 'select2', 'icon' ) ) ) {
			$field['options'] = apply_filters( 'helion_filter_options_get_list_choises', $field['options'], $id );
			$params           = array_merge(
				$params, array(
					'type'     => \Elementor\Controls_Manager::SELECT2,
					'options'  => $field['options'],
					'multiple' => 'checklist' == $field['type'] || ! empty( $field['multiple'] ),
				)
			);
			$element->add_control( $id_field, $params );

			// Type 'text' or 'time'
		} elseif ( in_array( $field['type'], array( 'text', 'time' ) ) ) {
			$params = array_merge(
				$params, array(
					'type' => \Elementor\Controls_Manager::TEXT,
				)
			);
			$element->add_control( $id_field, $params );

			// Type 'date'
		} elseif ( 'date' == $field['type'] ) {
			$params = array_merge(
				$params, array(
					'type' => \Elementor\Controls_Manager::DATE_TIME,
				)
			);
			$element->add_control( $id_field, $params );

			// Type 'textarea'
		} elseif ( 'textarea' == $field['type'] ) {
			$params = array_merge(
				$params, array(
					'type' => \Elementor\Controls_Manager::TEXTAREA,
					'rows' => ! empty( $field['rows'] ) ? max( 1, $field['rows'] ) : 5,
				)
			);
			$element->add_control( $id_field, $params );

			// Type 'text_editor'
		} elseif ( 'text_editor' == $field['type'] ) {
			$params = array_merge(
				$params, array(
					'type' => \Elementor\Controls_Manager::WYSIWYG,
				)
			);
			$element->add_control( $id_field, $params );

			// Type 'media'
		} elseif ( in_array( $field['type'], array( 'image', 'media', 'video', 'audio' ) ) ) {
			$params = array_merge(
				$params, array(
					'type'    => \Elementor\Controls_Manager::MEDIA,
					'default' => array(
						'id'  => ! empty( $field['val'] ) && ! helion_is_inherit( $field['val'] ) ? attachment_url_to_postid( helion_clear_thumb_size( $field['val'] ) ) : 0,
						'url' => ! empty( $field['val'] ) && ! helion_is_inherit( $field['val'] ) ? $field['val'] : '',
					),
				)
			);
			$element->add_control( $id_field, $params );

			// Type 'color'
		} elseif ( 'color' == $field['type'] ) {
			$params = array_merge(
				$params, array(
					'type'   => \Elementor\Controls_Manager::COLOR,
					'scheme' => array(
						'type'  => \Elementor\Scheme_Color::get_type(),
						'value' => \Elementor\Scheme_Color::COLOR_1,
					),
				)
			);
			$element->add_control( $id_field, $params );

			// Type 'slider' or 'range'
		} elseif ( in_array( $field['type'], array( 'slider', 'range' ) ) ) {
			$params = array_merge(
				$params, array(
					'type'    => \Elementor\Controls_Manager::SLIDER,
					'default' => array(
						'size' => ! empty( $field['val'] ) && ! helion_is_inherit( $field['val'] ) ? $field['val'] : '',
						'unit' => 'px',
					),
					'range'   => array(
						'px' => array(
							'min' => ! empty( $field['min'] ) ? $field['min'] : 0,
							'max' => ! empty( $field['max'] ) ? $field['max'] : 1000,
						),
					),
				)
			);
			$element->add_control( $id_field, $params );

		}
	}
}


// Save Page Options via AJAX from Elementor Editor
// (called when any option is changed)
if ( ! function_exists( 'helion_elm_page_options_save' ) ) {
	
	
	function helion_elm_page_options_save( $response_data, $post_id, $data ) {
		if ( $post_id > 0 && is_array( $data ) ) {
			$options = helion_storage_get( 'options' );
			$meta    = get_post_meta( $post_id, 'helion_options', true );
			if ( empty( $meta ) ) {
				$meta = array();
			}
			foreach ( $options as $k => $v ) {
				$id_field    = "helion_options_field_{$k}";
				$id_override = "helion_options_override_{$k}";
				if ( isset( $data[ $id_override ] ) ) {
					$meta[ $k ] = isset( $data[ $id_field ] )
									? ( is_array( $data[ $id_field ] ) && isset( $data[ $id_field ]['url'] )
											? $data[ $id_field ]['url']
											: $data[ $id_field ]
											)
									: ( ! empty( $meta[ $k ] ) && ! helion_is_inherit( $meta[ $k ] )
											? $meta[ $k ]
											: $v['std']
											);
				} elseif ( isset( $meta[ $k ] ) ) {
					unset( $meta[ $k ] );
				}
			}
			update_post_meta( $post_id, 'helion_options', apply_filters( 'helion_filter_update_post_options', $meta, $post_id ) );

			// Save separate meta options to search template pages
			if ( 'page' == get_post_type( $post_id ) && ! empty( $data['template'] ) && 'blog.php' == $data['template'] ) {
				update_post_meta( $post_id, 'helion_options_post_type', isset( $meta['post_type'] ) ? $meta['post_type'] : 'post' );
				update_post_meta( $post_id, 'helion_options_parent_cat', isset( $meta['parent_cat'] ) ? $meta['parent_cat'] : 0 );
			}
		}
		return $response_data;
	}
}


// Save Page Options via AJAX from Elementor Editor
// (called when any option is changed)
if ( ! function_exists( 'helion_elm_page_options_save_document' ) ) {
	
	function helion_elm_page_options_save_document( $response_data, $document ) {
		$post_id = $document->get_main_id();
		if ( $post_id > 0 ) {
			$actions = json_decode( helion_get_value_gp( 'actions' ), true );
			if ( is_array( $actions ) && isset( $actions['save_builder']['data']['settings'] ) && is_array( $actions['save_builder']['data']['settings'] ) ) {
				$settings = $actions['save_builder']['data']['settings'];
				if ( is_array( $settings ) ) {
					helion_elm_page_options_save( '', $post_id, $actions['save_builder']['data']['settings'] );
				}
			}
		}
		return $response_data;
	}
}


// Save Page Options when page is updated (saved) from WordPress Editor
if ( ! function_exists( 'helion_elm_update_post_options' ) ) {
	
	function helion_elm_update_post_options( $meta, $post_id ) {
		if ( doing_filter( 'save_post' ) ) {
			$elm_meta = get_post_meta( $post_id, '_elementor_page_settings', true );
			if ( is_array( $elm_meta ) ) {
				foreach ( $elm_meta as $k => $v ) {
					if ( strpos( $k, 'helion_options_' ) !== false ) {
						unset( $elm_meta[ $k ] );
					}
				}
			} else {
				$elm_meta = array();
			}
			$options = helion_storage_get( 'options' );
			foreach ( $meta as $k => $v ) {
				$elm_meta[ "helion_options_field_{$k}" ]    = in_array( $options[ $k ]['type'], array( 'image', 'video', 'audio', 'media' ) )
																? array(
																	'id' => attachment_url_to_postid( helion_clear_thumb_size( $v ) ),
																	'url' => $v,
																)
																: $v;
				$elm_meta[ "helion_options_override_{$k}" ] = '1';
			}
			update_post_meta( $post_id, '_elementor_page_settings', apply_filters( 'helion_filter_elementor_update_page_settings', $elm_meta, $post_id ) );
		}
		return $meta;
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if ( helion_exists_elementor() ) {
	require_once HELION_THEME_DIR . 'plugins/elementor/elementor-styles.php';
}
