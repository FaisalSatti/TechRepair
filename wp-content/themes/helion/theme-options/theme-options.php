<?php
/**
 * Theme Options, Color Schemes and Fonts utilities
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0
 */

// -----------------------------------------------------------------
// -- Create and manage Theme Options
// -----------------------------------------------------------------

// Theme init priorities:
// 2 - create Theme Options
if ( ! function_exists( 'helion_options_theme_setup2' ) ) {
	add_action( 'after_setup_theme', 'helion_options_theme_setup2', 2 );
	function helion_options_theme_setup2() {
		helion_create_theme_options();
	}
}

// Step 1: Load default settings and previously saved mods
if ( ! function_exists( 'helion_options_theme_setup5' ) ) {
	add_action( 'after_setup_theme', 'helion_options_theme_setup5', 5 );
	function helion_options_theme_setup5() {
		helion_storage_set( 'options_reloaded', false );
		helion_load_theme_options();
	}
}

// Step 2: Load current theme customization mods
if ( is_customize_preview() ) {
	if ( ! function_exists( 'helion_load_custom_options' ) ) {
		add_action( 'wp_loaded', 'helion_load_custom_options' );
		function helion_load_custom_options() {
			if ( ! helion_storage_get( 'options_reloaded' ) ) {
				helion_storage_set( 'options_reloaded', true );
				helion_load_theme_options();
			}
		}
	}
}



// Load current values for each customizable option
if ( ! function_exists( 'helion_load_theme_options' ) ) {
	function helion_load_theme_options() {
		$options = helion_storage_get( 'options' );
		$reset   = (int) get_theme_mod( 'reset_options', 0 );
		foreach ( $options as $k => $v ) {
			if ( isset( $v['std'] ) ) {
				$value = helion_get_theme_option_std( $k, $v['std'] );
				if ( ! $reset ) {
					if ( isset( $_GET[ $k ] ) ) {
						$value = wp_kses_data( wp_unslash( $_GET[ $k ] ) );
					} else {
						$default_value = -987654321;
						$tmp           = get_theme_mod( $k, $default_value );
						if ( $tmp != $default_value ) {
							$value = $tmp;
						}
					}
				}
				helion_storage_set_array2( 'options', $k, 'val', $value );
				if ( $reset ) {
					remove_theme_mod( $k );
				}
			}
		}
		if ( $reset ) {
			// Unset reset flag
			set_theme_mod( 'reset_options', 0 );
			// Regenerate CSS with default colors and fonts
			helion_customizer_save_css();
		} else {
			do_action( 'helion_action_load_options' );
		}
	}
}

// Override options with stored page/post meta
if ( ! function_exists( 'helion_override_theme_options' ) ) {
	add_action( 'wp', 'helion_override_theme_options', 1 );
	function helion_override_theme_options( $query = null ) {
		if ( is_page_template( 'blog.php' ) ) {
			helion_storage_set( 'blog_archive', true );
			helion_storage_set( 'blog_template', get_the_ID() );
		}
		helion_storage_set( 'blog_mode', helion_detect_blog_mode() );
		if ( is_singular() ) {
			helion_storage_set( 'options_meta', get_post_meta( get_the_ID(), 'helion_options', true ) );
		}
		do_action( 'helion_action_override_theme_options' );
	}
}

// Override options with stored page meta on 'Blog posts' pages
if ( ! function_exists( 'helion_blog_override_theme_options' ) ) {
	add_action( 'helion_action_override_theme_options', 'helion_blog_override_theme_options' );
	function helion_blog_override_theme_options() {
		global $wp_query;
		if ( is_home() && ! is_front_page() && ! empty( $wp_query->is_posts_page ) ) {
			$id = get_option( 'page_for_posts' );
			if ( $id > 0 ) {
				helion_storage_set( 'options_meta', get_post_meta( $id, 'helion_options', true ) );
			}
		}
	}
}


// Return 'std' value of the option, processed by special function (if specified)
if ( ! function_exists( 'helion_get_theme_option_std' ) ) {
	function helion_get_theme_option_std( $opt_name, $opt_std ) {
		if ( ! is_array( $opt_std ) && strpos( $opt_std, '$helion_' ) !== false ) {
			$func = substr( $opt_std, 1 );
			if ( function_exists( $func ) ) {
				$opt_std = $func( $opt_name );
			}
		}
		return $opt_std;
	}
}


// Return customizable option value
if ( ! function_exists( 'helion_get_theme_option' ) ) {
	function helion_get_theme_option( $name, $defa = '', $strict_mode = false, $post_id = 0 ) {
		$rez            = $defa;
		$from_post_meta = false;


		if ( $post_id > 0 ) {
			if ( ! helion_storage_isset( 'post_options_meta', $post_id ) ) {
				helion_storage_set_array( 'post_options_meta', $post_id, get_post_meta( $post_id, 'helion_options', true ) );
			}
			if ( helion_storage_isset( 'post_options_meta', $post_id, $name ) ) {
				$tmp = helion_storage_get_array( 'post_options_meta', $post_id, $name );
				if ( ! helion_is_inherit( $tmp ) ) {
					$rez            = $tmp;
					$from_post_meta = true;
				}
			}
		}

		if ( ! $from_post_meta && helion_storage_isset( 'options' ) ) {

			$blog_mode   = helion_storage_get( 'blog_mode' );
			$mobile_mode = wp_is_mobile() ? 'mobile' : '';

			if ( ! helion_storage_isset( 'options', $name ) && ( empty( $blog_mode ) || ! helion_storage_isset( 'options', $name . '_' . $blog_mode ) ) ) {

				$rez = '_not_exists_';
				$tmp = $rez;
				if ( function_exists( 'trx_addons_get_option' ) ) {
					$rez = trx_addons_get_option( $name, $tmp, false );
				}
				if ( $rez === $tmp ) {
					if ( $strict_mode ) {
						$s = '';
						if ( function_exists( 'ddo' ) ) {
							$s = debug_backtrace();
							array_shift($s);
							$s = ddo($s, 0, 3);
						}
						wp_die(
							// Translators: Add option's name to the message
							esc_html( sprintf( __( 'Undefined option "%s"', 'helion' ), $name ) )
							. ( ! empty( $s )
									? ' ' . esc_html( __( 'called from:', 'helion' ) ) . "<pre>" . wp_kses_data( $s ) . '</pre>'
									: ''
									)
						);
					} else {
						$rez = $defa;
					}
				}

			} else {

				// Single option name: 'expand_content' -> 'expand_content_single'
				$single_name = $name . ( is_single() && substr( $name, -7) != '_single' ? '_single' : '' );

				// Parent mode: 'team_single' -> 'team'
				$blog_mode_parent = apply_filters( 
										'helion_filter_blog_mode_parent',
										in_array( $blog_mode, array( 'post', 'home' ) )
											? 'blog'
											: str_replace( '_single', '', $blog_mode )
									);

				// Parent option name for posts: 'expand_content_single' -> 'expand_content_blog'
				$blog_name = 'post' == $blog_mode && substr( $name, -7) == '_single'
								? str_replace( '_single', '_blog', $name )
								: ( 'home' == $blog_mode && substr( $name, -5) != '_blog'
									? $name . '_blog'
									: ''
									);

				// Parent option name for CPT: 'expand_content_single_team' -> 'expand_content_team'
				$parent_name = strpos( $name, '_single') !== false ? str_replace( '_single', '', $name ) : '';

				// Get 'xxx_single' instead 'xxx_post'
				if ('post' == $blog_mode) {
					$blog_mode = 'single';
				}

				// Override option from GET or POST for current blog mode
				
				if ( ! empty( $blog_mode ) && isset( $_REQUEST[ $name . '_' . $blog_mode ] ) ) {
					$rez = wp_kses_data( wp_unslash( $_REQUEST[ $name . '_' . $blog_mode ] ) );

					// Override option from GET or POST
					
				} elseif ( isset( $_REQUEST[ $name ] ) ) {
					$rez = wp_kses_data( wp_unslash( $_REQUEST[ $name ] ) );

					// Override option from current page settings (if exists) with mobile mode
					
				} elseif ( ! empty( $mobile_mode ) && helion_storage_isset( 'options_meta', $name . '_' . $mobile_mode ) && ! helion_is_inherit( helion_storage_get_array( 'options_meta', $name . '_' . $mobile_mode ) ) ) {
					$rez = helion_storage_get_array( 'options_meta', $name . '_' . $mobile_mode );

					// Override option from current page settings (if exists)
					
				} elseif ( helion_storage_isset( 'options_meta', $name ) && ! helion_is_inherit( helion_storage_get_array( 'options_meta', $name ) ) ) {
					$rez = helion_storage_get_array( 'options_meta', $name );

					// Override option from current page settings (if exists)
					
				} elseif ( $single_name != $name && helion_storage_isset( 'options_meta', $single_name ) && ! helion_is_inherit( helion_storage_get_array( 'options_meta', $single_name ) ) ) {
					$rez = helion_storage_get_array( 'options_meta', $single_name );

					// Override single option with mobile mode
					
				} elseif ( ! empty( $mobile_mode ) && $single_name != $name && helion_storage_isset( 'options', $single_name . '_' . $mobile_mode, 'val' ) && ! helion_is_inherit( helion_storage_get_array( 'options', $single_name . '_' . $mobile_mode, 'val' ) ) ) {
					$rez = helion_storage_get_array( 'options', $single_name . '_' . $mobile_mode, 'val' );

					// Override option with mobile mode
					
				} elseif ( ! empty( $mobile_mode ) && helion_storage_isset( 'options', $name . '_' . $mobile_mode, 'val' ) && ! helion_is_inherit( helion_storage_get_array( 'options', $name . '_' . $mobile_mode, 'val' ) ) ) {
					$rez = helion_storage_get_array( 'options', $name . '_' . $mobile_mode, 'val' );

					// Override option from current blog mode settings: 'front', 'search', 'page', 'post', 'blog', etc. (if exists)
					
				} elseif ( ! empty( $blog_mode ) && $single_name != $name && helion_storage_isset( 'options', $single_name . '_' . $blog_mode, 'val' ) && ! helion_is_inherit( helion_storage_get_array( 'options', $single_name . '_' . $blog_mode, 'val' ) ) ) {
					$rez = helion_storage_get_array( 'options', $single_name . '_' . $blog_mode, 'val' );

					// Override option from current blog mode settings: 'front', 'search', 'page', 'post', 'blog', etc. (if exists)
					
				} elseif ( ! empty( $blog_mode ) && helion_storage_isset( 'options', $name . '_' . $blog_mode, 'val' ) && ! helion_is_inherit( helion_storage_get_array( 'options', $name . '_' . $blog_mode, 'val' ) ) ) {
					$rez = helion_storage_get_array( 'options', $name . '_' . $blog_mode, 'val' );

					// Override option from parent blog mode
					
				} elseif ( ! empty( $blog_mode ) && ! empty( $parent_name ) && $parent_name != $name && helion_storage_isset( 'options', $parent_name . '_' . $blog_mode, 'val' ) && ! helion_is_inherit( helion_storage_get_array( 'options', $parent_name . '_' . $blog_mode, 'val' ) ) ) {
					$rez = helion_storage_get_array( 'options', $parent_name . '_' . $blog_mode, 'val' );

					// Override option for 'post' from 'blog' settings (if exists)
					// Also used for override 'xxx_single' on the 'xxx'
					// (for example, instead 'sidebar_courses_single' return option for 'sidebar_courses')
					
				} elseif ( ! empty( $blog_mode_parent ) && $blog_mode != $blog_mode_parent && $single_name != $name && helion_storage_isset( 'options', $single_name . '_' . $blog_mode_parent, 'val' ) && ! helion_is_inherit( helion_storage_get_array( 'options', $single_name . '_' . $blog_mode_parent, 'val' ) ) ) {
					$rez = helion_storage_get_array( 'options', $single_name . '_' . $blog_mode_parent, 'val' );

				} elseif ( ! empty( $blog_mode_parent ) && $blog_mode != $blog_mode_parent && helion_storage_isset( 'options', $name . '_' . $blog_mode_parent, 'val' ) && ! helion_is_inherit( helion_storage_get_array( 'options', $name . '_' . $blog_mode_parent, 'val' ) ) ) {
					$rez = helion_storage_get_array( 'options', $name . '_' . $blog_mode_parent, 'val' );

				} elseif ( ! empty( $blog_mode_parent ) && $blog_mode != $blog_mode_parent && $parent_name != $name && helion_storage_isset( 'options', $parent_name . '_' . $blog_mode_parent, 'val' ) && ! helion_is_inherit( helion_storage_get_array( 'options', $parent_name . '_' . $blog_mode_parent, 'val' ) ) ) {
					$rez = helion_storage_get_array( 'options', $parent_name . '_' . $blog_mode_parent, 'val' );

					// Get saved option value for single post
					
				} elseif ( helion_storage_isset( 'options', $single_name, 'val' ) && ! helion_is_inherit( helion_storage_get_array( 'options', $single_name, 'val' ) ) ) {
					$rez = helion_storage_get_array( 'options', $single_name, 'val' );

					// Get saved option value
					
				} elseif ( helion_storage_isset( 'options', $name, 'val' ) && $single_name != $name && ! helion_is_inherit( helion_storage_get_array( 'options', $name, 'val' ) ) ) {
					$rez = helion_storage_get_array( 'options', $name, 'val' );

					// Override option for '_single' from '_blog' settings (if exists)
					
				} elseif ( ! empty( $blog_name ) && helion_storage_isset( 'options', $blog_name, 'val' ) && ! helion_is_inherit( helion_storage_get_array( 'options', $blog_name, 'val' ) ) ) {
					$rez = helion_storage_get_array( 'options', $blog_name, 'val' );

					// Override option for '_single' from parent settings (if exists)
					
				} elseif ( ! empty( $parent_name ) && $parent_name != $name && helion_storage_isset( 'options', $parent_name, 'val' ) && ! helion_is_inherit( helion_storage_get_array( 'options', $parent_name, 'val' ) ) ) {
					$rez = helion_storage_get_array( 'options', $parent_name, 'val' );

					// Get saved option value if nobody override it
					
				} elseif ( helion_storage_isset( 'options', $name, 'val' ) ) {
					$rez = helion_storage_get_array( 'options', $name, 'val' );

					// Get ThemeREX Addons option value
				} elseif ( function_exists( 'trx_addons_get_option' ) ) {
					$rez = trx_addons_get_option( $name, $defa, false );

				}
			}
		}
		return $rez;
	}
}


// Check if customizable option exists
if ( ! function_exists( 'helion_check_theme_option' ) ) {
	function helion_check_theme_option( $name ) {
		return helion_storage_isset( 'options', $name );
	}
}


// Return customizable option value, stored in the posts meta
if ( ! function_exists( 'helion_get_theme_option_from_meta' ) ) {
	function helion_get_theme_option_from_meta( $name, $defa = '' ) {
		$rez = $defa;
		if ( helion_storage_isset( 'options_meta' ) ) {
			if ( helion_storage_isset( 'options_meta', $name ) ) {
				$rez = helion_storage_get_array( 'options_meta', $name );
			} else {
				$rez = 'inherit';
			}
		}
		return $rez;
	}
}


// Get dependencies list from the Theme Options
if ( ! function_exists( 'helion_get_theme_dependencies' ) ) {
	function helion_get_theme_dependencies() {
		$options = helion_storage_get( 'options' );
		$depends = array();
		foreach ( $options as $k => $v ) {
			if ( isset( $v['dependency'] ) ) {
				$depends[ $k ] = $v['dependency'];
			}
		}
		return $depends;
	}
}



//------------------------------------------------
// Save options
//------------------------------------------------
if ( ! function_exists( 'helion_options_save' ) ) {
	add_action( 'after_setup_theme', 'helion_options_save', 4 );
	function helion_options_save() {

		if ( ! isset( $_REQUEST['page'] ) || 'theme_options' != $_REQUEST['page'] || '' == helion_get_value_gp( 'helion_nonce' ) ) {
			return;
		}

		// verify nonce
		if ( ! wp_verify_nonce( helion_get_value_gp( 'helion_nonce' ), admin_url() ) ) {
			helion_add_admin_message( esc_html__( 'Bad security code! Options are not saved!', 'helion' ), 'error', true );
			return;
		}

		// Check permissions
		if ( ! current_user_can( 'manage_options' ) ) {
			helion_add_admin_message( esc_html__( 'Manage options is denied for the current user! Options are not saved!', 'helion' ), 'error', true );
			return;
		}

		// Save options
		helion_options_update( null, 'helion_options_field_' );

		// Return result
		helion_add_admin_message( esc_html__( 'Options are saved', 'helion' ) );
		wp_redirect( get_admin_url( null, 'admin.php?page=theme_options' ) );
		exit();
	}
}


// Update theme options from specified source
// (_POST or any other options storage)
if ( ! function_exists( 'helion_options_update' ) ) {
	function helion_options_update( $from = null, $from_prefix = '' ) {
		$options           = helion_storage_get( 'options' );
		$external_storages = array();
		$values            = null === $from ? get_theme_mods() : $from;
		foreach ( $options as $k => $v ) {
			// Skip non-data options - sections, info, etc.
			if ( ! isset( $v['std'] ) ) {
				continue;
			}
			// Get new value
			$value = null;
			if ( null === $from ) {
				$from_name = "{$from_prefix}{$k}";
				if ( isset( $_POST[ $from_name ] ) ) {
					$value = helion_get_value_gp( $from_name );
					// Individual options processing
					if ( 'custom_logo' == $k ) {
						if ( ! empty( $value ) && 0 == (int) $value ) {
							$protocol = explode('//', $value);
							$value = helion_clear_thumb_size( $value );
							if ( strpos($value, ':') === false && !empty($protocol[0]) && substr($protocol[0], -1) == ':' ) {
								$value = $protocol[0] . $value;
							}
							$value = attachment_url_to_postid( $value );
							if ( empty( $value ) ) {
								$value = null === $from ? get_theme_mod( $k ) : $values[$k];
							}
						}
					}
					// Save to the result array
					if ( ! empty( $v['type'] ) && 'hidden' != $v['type']
						&& ( empty( $v['hidden'] ) || ! $v['hidden'] )
						&& ( ! empty( $v['options_storage'] ) || helion_get_theme_option_std( $k, $v['std'] ) != $value )
					) {
						// If value is not hidden and not equal to 'std' - store it
						$values[ $k ] = $value;
					} elseif ( isset( $values[ $k ] ) ) {
						// Otherwise - remove this key from options
						unset( $values[ $k ] );
						$value = null;
					}
				}
			} else {
				$value = isset( $values[ $k ] )
								? $values[ $k ]
								: helion_get_theme_option_std( $k, $v['std'] );
			}
			// External plugin's options
			if ( $value !== null && ! empty( $v['options_storage'] ) ) {
				if ( ! isset( $external_storages[ $v['options_storage'] ] ) ) {
					$external_storages[ $v['options_storage'] ] = array();
				}
				$external_storages[ $v['options_storage'] ][ $k ] = $value;
			}
		}

		// Update options in the external storages
		foreach ( $external_storages as $storage_name => $storage_values ) {
			$storage = get_option( $storage_name, false );
			if ( is_array( $storage ) ) {
				foreach ( $storage_values as $k => $v ) {
					if ( ! empty( $options[$k]['type'] )
						&& 'hidden' != $options[$k]['type']
						&& ( empty( $options[$k]['hidden'] ) || ! $options[$k]['hidden'] )
						&& helion_get_theme_option_std( $k, $options[$k]['std'] ) != $v
					) {
						// If value is not hidden and not equal to 'std' - store it
						$storage[ $k ] = $v;
					} else {
						// Otherwise - remove this key from the external storage and from the theme options
						unset( $storage[ $k ] );
						unset( $values[ $k ] );
					}
				}
				update_option( $storage_name, apply_filters( 'helion_filter_options_save', $storage, $storage_name ) );
			}
		}

		// Update Theme Mods (internal Theme Options)
		$stylesheet_slug = get_option( 'stylesheet' );
		$values          = apply_filters( 'helion_filter_options_save', $values, 'theme_mods' );

		update_option( "theme_mods_{$stylesheet_slug}", $values );

		do_action( 'helion_action_just_save_options', $values );

		// Store new schemes colors
		if ( ! empty( $values['scheme_storage'] ) ) {
			$schemes = helion_unserialize( $values['scheme_storage'] );
			if ( is_array( $schemes ) && count( $schemes ) > 0 ) {
				helion_storage_set( 'schemes', $schemes );
			}
		}

		// Store new fonts parameters
		$fonts = helion_get_theme_fonts();
		foreach ( $fonts as $tag => $v ) {
			foreach ( $v as $css_prop => $css_value ) {
				if ( in_array( $css_prop, array( 'title', 'description' ) ) ) {
					continue;
				}
				if ( isset( $values[ "{$tag}_{$css_prop}" ] ) ) {
					$fonts[ $tag ][ $css_prop ] = $values[ "{$tag}_{$css_prop}" ];
				}
			}
		}
		helion_storage_set( 'theme_fonts', $fonts );

		// Update ThemeOptions save timestamp
		$stylesheet_time = time();
		update_option( "helion_options_timestamp_{$stylesheet_slug}", $stylesheet_time );

		// Sinchronize theme options between child and parent themes
		if ( helion_get_theme_setting( 'duplicate_options' ) == 'both' ) {
			$theme_slug = get_option( 'template' );
			if ( $theme_slug != $stylesheet_slug ) {
				helion_customizer_duplicate_theme_options( $stylesheet_slug, $theme_slug, $stylesheet_time );
			}
		}

		// Apply action - moved to the delayed state (see below) to load all enabled modules and apply changes after
		// Attention! Don't remove comment the line below!
		// Not need here: do_action('helion_action_save_options');
		update_option( 'helion_action', 'helion_action_save_options' );
	}
}


//-------------------------------------------------------
//-- Delayed action from previous session
//-- (after save options)
//-- to save new CSS, etc.
//-------------------------------------------------------
if ( ! function_exists( 'helion_do_delayed_action' ) ) {
	add_action( 'after_setup_theme', 'helion_do_delayed_action' );
	function helion_do_delayed_action() {
		$action = get_option( 'helion_action' );
		if ( '' != $action ) {
			do_action( $action );
			update_option( 'helion_action', '' );
		}
	}
}



// -----------------------------------------------------------------
// -- Theme Settings utilities
// -----------------------------------------------------------------

// Return internal theme setting value
if ( ! function_exists( 'helion_get_theme_setting' ) ) {
	function helion_get_theme_setting( $name ) {
		if ( ! helion_storage_isset( 'settings', $name ) ) {
			$s = '';
			if ( function_exists( 'ddo' ) ) {
				$s = debug_backtrace();
				array_shift($s);
				$s = ddo($s, 0, 3);
			}
			wp_die(
				// Translators: Add option's name to the message
				esc_html( sprintf( __( 'Undefined setting "%s"', 'helion' ), $name ) )
				. ( ! empty( $s )
						? ' ' . esc_html( __( 'called from:', 'helion' ) ) . "<pre>" . wp_kses_data( $s ) . '</pre>'
						: ''
						)
			);
		} else {
			return helion_storage_get_array( 'settings', $name );
		}
	}
}

// Set theme setting
if ( ! function_exists( 'helion_set_theme_setting' ) ) {
	function helion_set_theme_setting( $option_name, $value ) {
		if ( helion_storage_isset( 'settings', $option_name ) ) {
			helion_storage_set_array( 'settings', $option_name, $value );
		}
	}
}



// -----------------------------------------------------------------
// -- Color Schemes utilities
// -----------------------------------------------------------------

// Load saved values to the color schemes
if ( ! function_exists( 'helion_load_schemes' ) ) {
	add_action( 'helion_action_load_options', 'helion_load_schemes' );
	function helion_load_schemes() {
		$schemes = helion_storage_get( 'schemes' );
		$storage = helion_unserialize( helion_get_theme_option( 'scheme_storage' ) );
		if ( is_array( $storage ) && count( $storage ) > 0 ) {
			
			// New way - use all color schemes (built-in and created by user)
			helion_storage_set( 'schemes', $storage );
		}
	}
}

// Return specified color from current (or specified) color scheme
if ( ! function_exists( 'helion_get_scheme_color' ) ) {
	function helion_get_scheme_color( $color_name, $scheme = '' ) {
		if ( empty( $scheme ) ) {
			$scheme = helion_get_theme_option( 'color_scheme' );
		}
		if ( empty( $scheme ) || helion_storage_empty( 'schemes', $scheme ) ) {
			$scheme = 'default';
		}
		$colors = helion_storage_get_array( 'schemes', $scheme, 'colors' );
		return $colors[ $color_name ];
	}
}

// Return colors from current color scheme
if ( ! function_exists( 'helion_get_scheme_colors' ) ) {
	function helion_get_scheme_colors( $scheme = '' ) {
		if ( empty( $scheme ) ) {
			$scheme = helion_get_theme_option( 'color_scheme' );
		}
		if ( empty( $scheme ) || helion_storage_empty( 'schemes', $scheme ) ) {
			$scheme = 'default';
		}
		return helion_storage_get_array( 'schemes', $scheme, 'colors' );
	}
}

// Return colors from all schemes
if ( ! function_exists( 'helion_get_scheme_storage' ) ) {
	function helion_get_scheme_storage( $scheme = '' ) {
		return serialize( helion_storage_get( 'schemes' ) );
	}
}

// Return theme fonts parameter's default value
if ( ! function_exists( 'helion_get_scheme_color_option' ) ) {
	function helion_get_scheme_color_option( $option_name ) {
		$parts = explode( '_', $option_name, 2 );
		return helion_get_scheme_color( $parts[1] );
	}
}

// Return schemes list
if ( ! function_exists( 'helion_get_list_schemes' ) ) {
	function helion_get_list_schemes( $prepend_inherit = false ) {
		$list    = array();
		$schemes = helion_storage_get( 'schemes' );
		if ( is_array( $schemes ) && count( $schemes ) > 0 ) {
			foreach ( $schemes as $slug => $scheme ) {
				$list[ $slug ] = $scheme['title'];
			}
		}
		return $prepend_inherit ? helion_array_merge( array( 'inherit' => esc_html__( 'Inherit', 'helion' ) ), $list ) : $list;
	}
}

// Return all schemes, sorted by usage in the parameters 'xxx_scheme' on the current page
if ( ! function_exists( 'helion_get_sorted_schemes' ) ) {
	function helion_get_sorted_schemes() {
		$params  = helion_storage_get( 'schemes_sorted' );
		$schemes = helion_storage_get( 'schemes' );
		$rez     = array();
		if ( is_array( $schemes ) ) {
			foreach ( $params as $p ) {
				if ( ! helion_check_theme_option( $p ) ) {
					continue;
				}
				$s = helion_get_theme_option( $p );
				if ( ! empty( $s ) && ! helion_is_inherit( $s ) && isset( $schemes[ $s ] ) ) {
					$rez[ $s ] = $schemes[ $s ];
					unset( $schemes[ $s ] );
				}
			}
			if ( count( $schemes ) > 0 ) {
				$rez = array_merge( $rez, $schemes );
			}
		}
		return $rez;
	}
}


// -----------------------------------------------------------------
// -- Theme Fonts utilities
// -----------------------------------------------------------------

// Load saved values into fonts list
if ( ! function_exists( 'helion_load_fonts' ) ) {
	add_action( 'helion_action_load_options', 'helion_load_fonts' );
	function helion_load_fonts() {
		// Fonts to load when theme starts
		$load_fonts = array();
		for ( $i = 1; $i <= helion_get_theme_setting( 'max_load_fonts' ); $i++ ) {
			$name = helion_get_theme_option( "load_fonts-{$i}-name" );
			if ( '' != $name ) {
				$load_fonts[] = array(
					'name'   => $name,
					'family' => helion_get_theme_option( "load_fonts-{$i}-family" ),
					'styles' => helion_get_theme_option( "load_fonts-{$i}-styles" ),
				);
			}
		}
		helion_storage_set( 'load_fonts', $load_fonts );
		helion_storage_set( 'load_fonts_subset', helion_get_theme_option( 'load_fonts_subset' ) );

		// Font parameters of the main theme's elements
		$fonts = helion_get_theme_fonts();
		foreach ( $fonts as $tag => $v ) {
			foreach ( $v as $css_prop => $css_value ) {
				if ( in_array( $css_prop, array( 'title', 'description' ) ) ) {
					continue;
				}
				$fonts[ $tag ][ $css_prop ] = helion_get_theme_option( "{$tag}_{$css_prop}" );
			}
		}
		helion_storage_set( 'theme_fonts', $fonts );
	}
}

// Return slug of the loaded font
if ( ! function_exists( 'helion_get_load_fonts_slug' ) ) {
	function helion_get_load_fonts_slug( $name ) {
		return str_replace( ' ', '-', $name );
	}
}

// Return load fonts parameter's default value
if ( ! function_exists( 'helion_get_load_fonts_option' ) ) {
	function helion_get_load_fonts_option( $option_name ) {
		$rez        = '';
		$parts      = explode( '-', $option_name );
		$load_fonts = helion_storage_get( 'load_fonts' );
		if ( 'load_fonts' == $parts[0] && count( $load_fonts ) > $parts[1] - 1 && isset( $load_fonts[ $parts[1] - 1 ][ $parts[2] ] ) ) {
			$rez = $load_fonts[ $parts[1] - 1 ][ $parts[2] ];
		}
		return $rez;
	}
}

// Return load fonts subset's default value
if ( ! function_exists( 'helion_get_load_fonts_subset' ) ) {
	function helion_get_load_fonts_subset( $option_name ) {
		return helion_storage_get( 'load_fonts_subset' );
	}
}

// Return load fonts list
if ( ! function_exists( 'helion_get_list_load_fonts' ) ) {
	function helion_get_list_load_fonts( $prepend_inherit = false ) {
		$list       = array();
		$load_fonts = helion_storage_get( 'load_fonts' );
		if ( is_array( $load_fonts ) && count( $load_fonts ) > 0 ) {
			foreach ( $load_fonts as $font ) {
				$list[ '"' . trim( $font['name'] ) . '"' . ( ! empty( $font['family'] ) ? ',' . trim( $font['family'] ) : '' ) ] = $font['name'];
			}
		}
		return $prepend_inherit ? helion_array_merge( array( 'inherit' => esc_html__( 'Inherit', 'helion' ) ), $list ) : $list;
	}
}

// Return font settings of the theme specific elements
if ( ! function_exists( 'helion_get_theme_fonts' ) ) {
	function helion_get_theme_fonts() {
		return helion_storage_get( 'theme_fonts' );
	}
}

// Return theme fonts parameter's default value
if ( ! function_exists( 'helion_get_theme_fonts_option' ) ) {
	function helion_get_theme_fonts_option( $option_name ) {
		$rez         = '';
		$parts       = explode( '_', $option_name );
		$theme_fonts = helion_storage_get( 'theme_fonts' );
		if ( ! empty( $theme_fonts[ $parts[0] ][ $parts[1] ] ) ) {
			$rez = $theme_fonts[ $parts[0] ][ $parts[1] ];
		}
		return $rez;
	}
}

// Update loaded fonts list in the each tag's parameter (p, h1..h6,...) after the 'load_fonts' options are loaded
if ( ! function_exists( 'helion_update_list_load_fonts' ) ) {
	add_action( 'helion_action_load_options', 'helion_update_list_load_fonts', 11 );
	function helion_update_list_load_fonts() {
		$theme_fonts = helion_get_theme_fonts();
		$load_fonts  = helion_get_list_load_fonts( true );
		foreach ( $theme_fonts as $tag => $v ) {
			helion_storage_set_array2( 'options', $tag . '_font-family', 'options', $load_fonts );
		}
	}
}



// -----------------------------------------------------------------
// -- Other options utilities
// -----------------------------------------------------------------

// Return all vars from Theme Options with option 'customizer'
if ( ! function_exists( 'helion_get_theme_vars' ) ) {
	function helion_get_theme_vars() {
		$options = helion_storage_get( 'options' );
		$vars    = array();
		foreach ( $options as $k => $v ) {
			if ( ! empty( $v['customizer'] ) ) {
				$vars[ $v['customizer'] ] = helion_get_theme_option( $k );
			}
		}
		return $vars;
	}
}

// Return current theme-specific border radius for form's fields and buttons
if ( ! function_exists( 'helion_get_border_radius' ) ) {
	function helion_get_border_radius() {
		$rad = str_replace( ' ', '', helion_get_theme_option( 'border_radius' ) );
		if ( empty( $rad ) ) {
			$rad = 0;
		}
		return helion_prepare_css_value( $rad );
	}
}




// -----------------------------------------------------------------
// -- Theme Options page
// -----------------------------------------------------------------

if ( ! function_exists( 'helion_options_init_page_builder' ) ) {
	add_action( 'after_setup_theme', 'helion_options_init_page_builder' );
	function helion_options_init_page_builder() {
		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', 'helion_options_add_scripts' );
		}
	}
}

// Load required styles and scripts for admin mode
if ( ! function_exists( 'helion_options_add_scripts' ) ) {
	
	function helion_options_add_scripts() {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : false;
		if ( ! empty( $screen->id ) && false !== strpos($screen->id, '_page_theme_options') ) {
			wp_enqueue_style( 'fontello-icons', helion_get_file_url( 'css/font-icons/css/fontello.css' ), array(), null );
			wp_enqueue_style( 'wp-color-picker', false, array(), null );
			wp_enqueue_script( 'wp-color-picker', false, array( 'jquery' ), null, true );
			wp_enqueue_script( 'jquery-ui-tabs', false, array( 'jquery', 'jquery-ui-core' ), null, true );
			wp_enqueue_script( 'jquery-ui-accordion', false, array( 'jquery', 'jquery-ui-core' ), null, true );
			wp_enqueue_script( 'helion-options', helion_get_file_url( 'theme-options/theme-options.js' ), array( 'jquery' ), null, true );
			wp_enqueue_style(  'jquery-colorpicker', helion_get_file_url( 'js/colorpicker/spectrum/spectrum.css' ), array(), null );
			wp_enqueue_script( 'jquery-colorpicker', helion_get_file_url( 'js/colorpicker/spectrum/spectrum.js' ), array( 'jquery' ), null, true );
			wp_localize_script( 'helion-options', 'helion_dependencies', helion_get_theme_dependencies() );
			wp_localize_script( 'helion-options', 'helion_color_schemes', helion_storage_get( 'schemes' ) );
			wp_localize_script( 'helion-options', 'helion_simple_schemes', helion_storage_get( 'schemes_simple' ) );
			wp_localize_script( 'helion-options', 'helion_sorted_schemes', helion_storage_get( 'schemes_sorted' ) );
			wp_localize_script( 'helion-options', 'helion_theme_fonts', helion_storage_get( 'theme_fonts' ) );
			wp_localize_script( 'helion-options', 'helion_theme_vars', helion_get_theme_vars() );
			wp_localize_script(
				'helion-options', 'helion_options_vars', apply_filters(
					'helion_filter_options_vars', array(
						'max_load_fonts' => helion_get_theme_setting( 'max_load_fonts' ),
					)
				)
			);
		}
	}
}

// Add Theme Options item in the Appearance menu
if ( ! function_exists( 'helion_options_add_theme_panel_page' ) ) {
	add_action( 'trx_addons_filter_add_theme_panel_pages', 'helion_options_add_theme_panel_page' );
	function helion_options_add_theme_panel_page($list) {
		if ( ! HELION_THEME_FREE ) {
			$list[] = array(
				esc_html__( 'Theme Options', 'helion' ),
				esc_html__( 'Theme Options', 'helion' ),
				'manage_options',
				'theme_options',
				'helion_options_page_builder',
				'dashicons-admin-generic'
			);
		}
		return $list;
	}
}


// Build options page
if ( ! function_exists( 'helion_options_page_builder' ) ) {
	function helion_options_page_builder() {
		?>
		<div class="helion_options">
			<h2 class="helion_options_title"><?php esc_html_e( 'Theme Options', 'helion' ); ?></h2>
			<?php helion_show_admin_messages(); ?>
			<div class="helion_options_info notice notice-info notice-large">
				<p><b>
					<?php esc_html_e( 'Attention!', 'helion' ); ?>
				</b></p>
				<p>
					<?php echo esc_html__( 'Some of these options can be overridden in the following sections (Blog, Plugins settings, etc.) or in the settings of individual pages.', 'helion' )
						. '<br>'
						. esc_html__( 'If you changed such parameter and nothing happened on the page, this option may be overridden in the corresponding section or in the Page Options of this page.', 'helion' );
					?>
				</p>
				<p><span class="helion_options_asterisk"></span>
					<i>
						<?php esc_html_e( 'These options are marked with an asterisk (*) in the title.', 'helion' ); ?>
					</i>
				</p>
			</div>
			<form id="helion_options_form" action="" method="post" enctype="multipart/form-data">
				<input type="hidden" name="helion_nonce" value="<?php echo esc_attr( wp_create_nonce( admin_url() ) ); ?>" />
				<?php helion_options_show_fields(); ?>
				<div class="helion_options_buttons">
					<input type="button" class="helion_options_button_submit" value="<?php  esc_attr_e( 'Save Options', 'helion' ); ?>">
				</div>
			</form>
		</div>
		<?php
	}
}


// Display all option's fields
if ( ! function_exists( 'helion_options_show_fields' ) ) {
	function helion_options_show_fields( $options = false ) {
		if ( empty( $options ) ) {
			$options = helion_storage_get( 'options' );
		}
		$tabs_titles      = array();
		$tabs_content     = array();
		$last_panel_super = '';
		$last_panel       = '';
		$last_section     = '';
		$last_group       = '';
		$allow_subtabs    = helion_get_theme_setting( 'options_tabs_position' ) == 'vertical' && helion_get_theme_setting( 'allow_subtabs' );
		foreach ( $options as $k => $v ) {
			if ( 'panel' == $v['type'] || ( 'section' == $v['type'] && ( empty( $last_panel ) || $allow_subtabs ) ) ) {
				// New tab
				if ( ! isset( $tabs_titles[ $k ] ) ) {
					$tabs_titles[ $k ]  = $v;
					$tabs_content[ $k ] = '';
				}
				if ( ! empty( $last_group ) ) {
					$tabs_content[ $last_section ] .= '</div></div>';
					$last_group                     = '';
				}
				$last_section = $k;
				if ( 'panel' == $v['type'] || $allow_subtabs ) {
					$last_panel = $k;
					if ( 'section' == $v['type'] && ! empty( $last_panel_super ) ) {
						$tabs_titles[ $last_panel_super ][ 'super' ] = true;
						$tabs_titles[ $k ][ 'sub' ] = true;
					}
				}
				if ( 'panel' == $v['type'] ) {
					$last_panel_super = $k;
				}
			} elseif ( 'group' == $v['type'] || ( 'section' == $v['type'] && ! empty( $last_panel ) ) ) {
				// New group
				if ( empty( $last_group ) ) {
					$tabs_content[ $last_section ] = ( ! isset( $tabs_content[ $last_section ] ) ? '' : $tabs_content[ $last_section ] )
													. '<div class="helion_accordion helion_options_groups">';
				} else {
					$tabs_content[ $last_section ] .= '</div>';
				}
				$tabs_content[ $last_section ] .= '<h4 class="helion_accordion_title helion_options_group_title">' . esc_html( $v['title'] ) . '</h4>'
												. '<div class="helion_accordion_content helion_options_group_content">';
				$last_group                     = $k;
			} elseif ( in_array( $v['type'], array( 'group_end', 'section_end', 'panel_end' ) ) ) {
				// End panel, section or group
				if ( ! empty( $last_group ) && ( 'section_end' != $v['type'] || empty( $last_panel ) ) ) {
					$tabs_content[ $last_section ] .= '</div></div>';
					$last_group                     = '';
				}
				if ( 'panel_end' == $v['type'] ) {
					$last_panel = '';
					$last_panel_super = '';
				}
			} else {
				// Field's layout
				$tabs_content[ $last_section ] = ( ! isset( $tabs_content[ $last_section ] ) ? '' : $tabs_content[ $last_section ] )
												. helion_options_show_field( $k, $v );
			}
		}
		if ( ! empty( $last_group ) ) {
			$tabs_content[ $last_section ] .= '</div></div>';
		}

		if ( count( $tabs_content ) > 0 ) {
			// Remove empty sections
			foreach ( $tabs_content as $k => $v ) {
				if ( empty( $v ) && empty( $tabs_titles[ $k ]['super'] ) ) {
					unset( $tabs_titles[ $k ] );
					unset( $tabs_content[ $k ] );
				}
			}
			?>
			<div id="helion_options_tabs" class="helion_tabs helion_tabs_<?php echo esc_attr( helion_get_theme_setting( 'options_tabs_position' ) ); ?> <?php echo count( $tabs_titles ) > 1 ? 'with_tabs' : 'no_tabs'; ?>">
				<?php
				if ( count( $tabs_titles ) > 1 ) {
					?>
					<ul>
						<?php
						$cnt = 0;
						foreach ( $tabs_titles as $k => $v ) {
							$cnt++;
							echo '<li class="helion_tabs_title helion_tabs_title_' . esc_attr( $v['type'] )
									. ( ! empty( $v['super'] ) ? ' helion_tabs_title_super' : '' )
									. ( ! empty( $v['sub'] ) ? ' helion_tabs_title_sub' : '' )
								. '"><a href="#helion_options_section_' . esc_attr( ! empty( $v['super'] ) ? $cnt + 1 : $cnt ) . '">'
										. ( !empty( $v['icon'] ) ? '<i class="' . esc_attr( $v['icon'] ) . '"></i>' : '' )
										. '<span class="helion_tabs_caption">' . esc_html( $v['title'] ) . '</span>'
									. '</a>'
								. '</li>';
						}
						?>
					</ul>
					<?php
				}
				$cnt = 0;
				foreach ( $tabs_content as $k => $v ) {
					$cnt++;
					if ( ! empty( $v['super'] ) ) {
						continue;
					}
					?>
					<div id="helion_options_section_<?php echo esc_attr( $cnt ); ?>" class="helion_tabs_section helion_options_section">
						<?php helion_show_layout( $v ); ?>
					</div>
					<?php
				}
				?>
			</div>
			<?php
		}
	}
}


// Display single option's field
if ( ! function_exists( 'helion_options_show_field' ) ) {
	function helion_options_show_field( $name, $field, $post_type = '' ) {

		$inherit_allow = ! empty( $post_type );
		$inherit_state = ! empty( $post_type ) && isset( $field['val'] ) && helion_is_inherit( $field['val'] );

		$field_data_present = 'info' != $field['type'] || ! empty( $field['override']['desc'] ) || ! empty( $field['desc'] );

		if ( ( 'hidden' == $field['type'] && $inherit_allow )         // Hidden field in the post meta (not in the root Theme Options)
			|| ( ! empty( $field['hidden'] ) && ! $inherit_allow )    // Field only for post meta in the root Theme Options
		) {
			return '';
		}

		if ( 'hidden' == $field['type'] ) {
			$output = isset( $field['val'] )
							? '<input type="hidden" name="helion_options_field_' . esc_attr( $name ) . '"'
								. ' value="' . esc_attr( $field['val'] ) . '"'
								. ' />'
							: '';

		} else {
			$output = ( ! empty( $field['class'] ) && strpos( $field['class'], 'helion_new_row' ) !== false
						? '<div class="helion_new_row_before"></div>'
						: '' )
						. '<div class="helion_options_item helion_options_item_' . esc_attr( $field['type'] )
									. ( $inherit_allow ? ' helion_options_inherit_' . ( $inherit_state ? 'on' : 'off' ) : '' )
									. ( ! empty( $field['class'] ) ? ' ' . esc_attr( $field['class'] ) : '' )
									. '">'
							. '<h4 class="helion_options_item_title">'
								. esc_html( $field['title'] )
								. ( ! empty( $field['override'] )
										? ' <span class="helion_options_asterisk"></span>'
										: '' )
								. ( $inherit_allow
										? '<span class="helion_options_inherit_lock" id="helion_options_inherit_' . esc_attr( $name ) . '" tabindex="0"></span>'
										: '' )
							. '</h4>'
							. ( $field_data_present
								? '<div class="helion_options_item_data">'
									. '<div class="helion_options_item_field" data-param="' . esc_attr( $name ) . '"'
										. ( ! empty( $field['linked'] ) ? ' data-linked="' . esc_attr( $field['linked'] ) . '"' : '' )
										. '>'
								: '' );

			if ( 'checkbox' == $field['type'] ) {
				// Type 'checkbox'
				$output .= '<label class="helion_options_item_label">'
							// Hack to always send checkbox value even it not checked
							. '<input type="hidden" name="helion_options_field_' . esc_attr( $name ) . '" value="' . esc_attr( helion_is_inherit( $field['val'] ) ? '' : $field['val'] ) . '" />'
							. '<input type="checkbox" name="helion_options_field_' . esc_attr( $name ) . '_chk" value="1"'
									. ( 1 == $field['val'] ? ' checked="checked"' : '' )
									. ' />'
							. '<span class="helion_options_item_caption">'
								. esc_html( $field['title'] )
							. '</span>'
						. '</label>';

			} else if ( 'switch' == $field['type'] ) {
				// Type 'switch'
				$output .= '<label class="helion_options_item_label">'
							// Hack to always send checkbox value even it not checked
							. '<input type="hidden" name="helion_options_field_' . esc_attr( $name ) . '" value="' . esc_attr( helion_is_inherit( $field['val'] ) ? '' : $field['val'] ) . '" />'
							. '<input type="checkbox" name="helion_options_field_' . esc_attr( $name ) . '_chk" value="1"'
									. ( 1 == $field['val'] ? ' checked="checked"' : '' )
									. ' />'
							. '<span class="helion_options_item_holder" tabindex="0">'
								. '<span class="helion_options_item_holder_wrap">'
									. '<span class="helion_options_item_holder_inner">'
										. '<span class="helion_options_item_holder_on"></span>'
										. '<span class="helion_options_item_holder_handle"></span>'
										. '<span class="helion_options_item_holder_off"></span>'
									. '</span>'
								. '</span>'
							. '</span>'
							. '<span class="helion_options_item_caption">'
								. esc_html( $field['title'] )
							. '</span>'
						. '</label>';

			} elseif ( in_array( $field['type'], array( 'radio' ) ) ) {
				// Type 'radio' (2+ choises)
				$field['options'] = apply_filters( 'helion_filter_options_get_list_choises', $field['options'], $name );
				$first            = true;
				foreach ( $field['options'] as $k => $v ) {
					$output .= '<label class="helion_options_item_label">'
								. '<input type="radio" name="helion_options_field_' . esc_attr( $name ) . '"'
										. ' value="' . esc_attr( $k ) . '"'
										. ( ( '#' . $field['val'] ) == ( '#' . $k ) || ( $first && ! isset( $field['options'][ $field['val'] ] ) ) ? ' checked="checked"' : '' )
										. ' />'
								. '<span class="helion_options_item_holder" tabindex="0"></span>'
								. '<span class="helion_options_item_caption">'
									. esc_html( $v )
								. '</span>'
							. '</label>';
					$first   = false;
				}

			} elseif ( in_array( $field['type'], array( 'text', 'time', 'date' ) ) ) {
				// Type 'text' or 'time' or 'date'
				$output .= '<input type="text" name="helion_options_field_' . esc_attr( $name ) . '"'
								. ' value="' . esc_attr( helion_is_inherit( $field['val'] ) ? '' : $field['val'] ) . '"'
								. ' />';

			} elseif ( 'textarea' == $field['type'] ) {
				// Type 'textarea'
				$output .= '<textarea name="helion_options_field_' . esc_attr( $name ) . '">'
								. esc_html( helion_is_inherit( $field['val'] ) ? '' : $field['val'] )
							. '</textarea>';

			} elseif ( 'text_editor' == $field['type'] ) {
				// Type 'text_editor'
				$output .= '<input type="hidden" id="helion_options_field_' . esc_attr( $name ) . '"'
								. ' name="helion_options_field_' . esc_attr( $name ) . '"'
								. ' value="' . esc_textarea( helion_is_inherit( $field['val'] ) ? '' : $field['val'] ) . '"'
								. ' />'
							. helion_show_custom_field(
								'helion_options_field_' . esc_attr( $name ) . '_tinymce',
								$field,
								helion_is_inherit( $field['val'] ) ? '' : $field['val']
							);

			} elseif ( 'select' == $field['type'] ) {
				// Type 'select'
				$field['options'] = apply_filters( 'helion_filter_options_get_list_choises', $field['options'], $name );
				$output          .= '<select size="1" name="helion_options_field_' . esc_attr( $name ) . '">';
				foreach ( $field['options'] as $k => $v ) {
					$output .= '<option value="' . esc_attr( $k ) . '"' . ( ( '#' . $field['val'] ) == ( '#' . $k ) ? ' selected="selected"' : '' ) . '>' . esc_html( $v ) . '</option>';
				}
				$output .= '</select>';

			} elseif ( in_array( $field['type'], array( 'image', 'media', 'video', 'audio' ) ) ) {
				// Type 'image', 'media', 'video' or 'audio'
				if ( (int) $field['val'] > 0 ) {
					$image        = wp_get_attachment_image_src( $field['val'], 'full' );
					$field['val'] = $image[0];
				}
				$output .= '<input type="hidden" id="helion_options_field_' . esc_attr( $name ) . '"'
								. ' name="helion_options_field_' . esc_attr( $name ) . '"'
								. ' value="' . esc_attr( helion_is_inherit( $field['val'] ) ? '' : $field['val'] ) . '"'
								. ' />'
						. helion_show_custom_field(
							'helion_options_field_' . esc_attr( $name ) . '_button',
							array(
								'type'            => 'mediamanager',
								'multiple'        => ! empty( $field['multiple'] ),
								'data_type'       => $field['type'],
								'linked_field_id' => 'helion_options_field_' . esc_attr( $name ),
							),
							helion_is_inherit( $field['val'] ) ? '' : $field['val']
						);

			} elseif ( 'color' == $field['type'] ) {
				// Type 'color'
				$output .= '<input type="text" id="helion_options_field_' . esc_attr( $name ) . '"'
								. ' class="helion_color_selector"'
								. ' name="helion_options_field_' . esc_attr( $name ) . '"'
								. ' value="' . esc_attr( $field['val'] ) . '"'
								. ' />';

			} elseif ( 'icon' == $field['type'] ) {
				// Type 'icon'
				$output .= '<input type="hidden" id="helion_options_field_' . esc_attr( $name ) . '"'
								. ' name="helion_options_field_' . esc_attr( $name ) . '"'
								. ' value="' . esc_attr( helion_is_inherit( $field['val'] ) ? '' : $field['val'] ) . '"'
								. ' />'
							. helion_show_custom_field(
								'helion_options_field_' . esc_attr( $name ) . '_button',
								array(
									'type'   => 'icons',
									'style'  => ! empty( $field['style'] ) ? $field['style'] : 'icons',
									'button' => true,
									'icons'  => true,
								),
								helion_is_inherit( $field['val'] ) ? '' : $field['val']
							);

			} elseif ( 'checklist' == $field['type'] ) {
				// Type 'checklist'
				$output .= '<input type="hidden" id="helion_options_field_' . esc_attr( $name ) . '"'
								. ' name="helion_options_field_' . esc_attr( $name ) . '"'
								. ' value="' . esc_attr( helion_is_inherit( $field['val'] ) ? '' : $field['val'] ) . '"'
								. ' />'
							. helion_show_custom_field(
								'helion_options_field_' . esc_attr( $name ) . '_list',
								$field,
								helion_is_inherit( $field['val'] ) ? '' : $field['val']
							);

			} elseif ( 'scheme_editor' == $field['type'] ) {
				// Type 'scheme_editor'
				$output .= '<input type="hidden" id="helion_options_field_' . esc_attr( $name ) . '"'
								. ' name="helion_options_field_' . esc_attr( $name ) . '"'
								. ' value="' . esc_attr( helion_is_inherit( $field['val'] ) ? '' : $field['val'] ) . '"'
								. ' />'
							. helion_show_custom_field(
								'helion_options_field_' . esc_attr( $name ) . '_scheme',
								$field,
								helion_unserialize( $field['val'] )
							);

			} elseif ( in_array( $field['type'], array( 'slider', 'range' ) ) ) {
				// Type 'slider' || 'range'
				$field['show_value'] = ! isset( $field['show_value'] ) || $field['show_value'];
				$output             .= '<input type="' . ( ! $field['show_value'] ? 'hidden' : 'text' ) . '" id="helion_options_field_' . esc_attr( $name ) . '"'
								. ' name="helion_options_field_' . esc_attr( $name ) . '"'
								. ' value="' . esc_attr( helion_is_inherit( $field['val'] ) ? '' : $field['val'] ) . '"'
								. ( $field['show_value'] ? ' class="helion_range_slider_value"' : '' )
								. ' data-type="' . esc_attr( $field['type'] ) . '"'
								. ' />'
							. ( $field['show_value'] && ! empty( $field['units'] ) ? '<span class="helion_range_slider_units">' . esc_html( $field['units'] ) . '</span>' : '' )
							. helion_show_custom_field(
								'helion_options_field_' . esc_attr( $name ) . '_slider',
								$field,
								helion_is_inherit( $field['val'] ) ? '' : $field['val']
							);
			}

			$output .= ( $inherit_allow
							? '<div class="helion_options_inherit_cover' . ( ! $inherit_state ? ' helion_hidden' : '' ) . '">'
								. '<span class="helion_options_inherit_label">' . esc_html__( 'Inherit', 'helion' ) . '</span>'
								. '<input type="hidden" name="helion_options_inherit_' . esc_attr( $name ) . '"'
										. ' value="' . esc_attr( $inherit_state ? 'inherit' : '' ) . '"'
										. ' />'
								. '</div>'
							: '' )
						. ( $field_data_present ? '</div>' : '' )
						. ( ! empty( $field['override']['desc'] ) || ! empty( $field['desc'] )
							? '<div class="helion_options_item_description">'
								. ( ! empty( $field['override']['desc'] )   // param 'desc' already processed with wp_kses()!
										? $field['override']['desc']
										: $field['desc'] )
								. '</div>'
							: '' )
					. ( $field_data_present ? '</div>' : '' )
				. '</div>';
		}
		return $output;
	}
}


// Show theme specific fields
function helion_show_custom_field( $id, $field, $value ) {
	$output = '';

	switch ( $field['type'] ) {

		case 'mediamanager':
			wp_enqueue_media();
			$title   = empty( $field['data_type'] ) || 'image' == $field['data_type']
							? ( ! empty( $field['multiple'] ) ? esc_html__( 'Add Images', 'helion' ) : esc_html__( 'Choose Image', 'helion' ) )
							: ( ! empty( $field['multiple'] ) ? esc_html__( 'Add Media', 'helion' ) : esc_html__( 'Choose Media', 'helion' ) );
			$images  = explode( '|', $value );
			$output .= '<span class="helion_media_selector_preview' . ( is_array( $images ) && count( $images ) > 0 ? ' helion_media_selector_preview_with_image' : '' ) . '">';
			if ( is_array( $images ) ) {
				foreach ( $images as $img ) {
					$output .= $img && ! helion_is_inherit( $img )
							? '<span class="helion_media_selector_preview_image" tabindex="0">'
									. ( in_array( helion_get_file_ext( $img ), array( 'gif', 'jpg', 'jpeg', 'png' ) )
											? '<img src="' . esc_url( $img ) . '" alt="' . esc_attr__( 'Selected image', 'helion' ) . '">'
											: '<a href="' . esc_attr( $img ) . '">' . esc_html( basename( $img ) ) . '</a>'
										)
								. '</span>'
							: '';
				}
			}

			$output .= '</span>';
			$output .= '<input type="button"'
							. ' id="' . esc_attr( $id ) . '"'
							. ' class="button mediamanager helion_media_selector"'
							. '	data-param="' . esc_attr( $id ) . '"'
							. '	data-choose="' . esc_attr( $title ) . '"'
							. ' data-update="' . esc_attr( $title ) . '"'
							. '	data-multiple="' . esc_attr( ! empty( $field['multiple'] ) ? '1' : '0' ) . '"'
							. '	data-type="' . esc_attr( ! empty( $field['data_type'] ) ? $field['data_type'] : 'image' ) . '"'
							. '	data-linked-field="' . esc_attr( $field['linked_field_id'] ) . '"'
							. ' value="' .  esc_attr( $title ) . '"'
						. '>';
			break;

		case 'icons':
			$icons_type = ! empty( $field['style'] )
							? $field['style']
							: helion_get_theme_setting( 'icons_type' );
			if ( empty( $field['return'] ) ) {
				$field['return'] = 'full';
			}
			$helion_icons = helion_get_list_icons( $icons_type );
			if ( is_array( $helion_icons ) ) {
				if ( ! empty( $field['button'] ) ) {
					$output .= '<span id="' . esc_attr( $id ) . '"'
									. ' tabindex="0"'
									. ' class="helion_list_icons_selector'
											. ( 'icons' == $icons_type && ! empty( $value ) ? ' ' . esc_attr( $value ) : '' )
											. '"'
									. ' title="' . esc_attr__( 'Select icon', 'helion' ) . '"'
									. ' data-style="' . esc_attr( $icons_type ) . '"'
									. ( in_array( $icons_type, array( 'images', 'svg' ) ) && ! empty( $value )
										? ' style="background-image: url(' . esc_url( 'slug' == $field['return'] ? $helion_icons[ $value ] : $value ) . ');"'
										: ''
										)
								. '></span>';
				}
				if ( ! empty( $field['icons'] ) ) {
					$output .= '<div class="helion_list_icons">'
								. '<input type="text" class="helion_list_icons_search" placeholder="' . esc_attr__( 'Search for an icon', 'helion' ) . '">'
								. '<div class="helion_list_icons_inner">';
					foreach ( $helion_icons as $slug => $icon ) {
						$output .= '<span tabindex="0" class="' . esc_attr( 'icons' == $icons_type ? $icon : $slug )
								. ( ( 'full' == $field['return'] ? $icon : $slug ) == $value ? ' helion_list_active' : '' )
								. '"'
								. ' title="' . esc_attr( $slug ) . '"'
								. ' data-icon="' . esc_attr( 'full' == $field['return'] ? $icon : $slug ) . '"'
								. ( ! empty( $icon ) && in_array( $icons_type, array( 'images', 'svg' ) ) ? ' style="background-image: url(' . esc_url( $icon ) . ');"' : '' )
								. '></span>';
					}
					$output .= '</div></div>';
				}
			}
			break;

		case 'checklist':
			if ( ! empty( $field['sortable'] ) ) {
				wp_enqueue_script( 'jquery-ui-sortable', false, array( 'jquery', 'jquery-ui-core' ), null, true );
			}
			$output .= '<div class="helion_checklist helion_checklist_' . esc_attr( $field['dir'] )
						. ( ! empty( $field['sortable'] ) ? ' helion_sortable' : '' )
						. '">';
			if ( ! is_array( $value ) ) {
				if ( ! empty( $value ) && ! helion_is_inherit( $value ) ) {
					parse_str( str_replace( '|', '&', $value ), $value );
				} else {
					$value = array();
				}
			}
			// Sort options by values order
			if ( ! empty( $field['sortable'] ) && is_array( $value ) ) {
				$field['options'] = helion_array_merge( $value, $field['options'] );
			}
			foreach ( $field['options'] as $k => $v ) {
				$output .= '<label class="helion_checklist_item_label'
								. ( ! empty( $field['sortable'] ) ? ' helion_sortable_item' : '' )
								. '">'
							. '<input type="checkbox" value="1" data-name="' . $k . '"'
								. ( isset( $value[ $k ] ) && 1 == (int) $value[ $k ] ? ' checked="checked"' : '' )
								. ' />'
							. ( substr( $v, 0, 4 ) == 'http' ? '<img src="' . esc_url( $v ) . '">' : esc_html( $v ) )
						. '</label>';
			}
			$output .= '</div>';
			break;

		case 'slider':
		case 'range':
			wp_enqueue_script( 'jquery-ui-slider', false, array( 'jquery', 'jquery-ui-core' ), null, true );
			$is_range   = 'range' == $field['type'];
			$field_min  = ! empty( $field['min'] ) ? $field['min'] : 0;
			$field_max  = ! empty( $field['max'] ) ? $field['max'] : 100;
			$field_step = ! empty( $field['step'] ) ? $field['step'] : 1;
			$field_val  = ! empty( $value )
							? ( $value . ( $is_range && strpos( $value, ',' ) === false ? ',' . $field_max : '' ) )
							: ( $is_range ? $field_min . ',' . $field_max : $field_min );
			$output    .= '<div id="' . esc_attr( $id ) . '"'
							. ' class="helion_range_slider"'
							. ' data-range="' . esc_attr( $is_range ? 'true' : 'min' ) . '"'
							. ' data-min="' . esc_attr( $field_min ) . '"'
							. ' data-max="' . esc_attr( $field_max ) . '"'
							. ' data-step="' . esc_attr( $field_step ) . '"'
							. '>'
							. '<span class="helion_range_slider_label helion_range_slider_label_min">'
								. esc_html( $field_min )
							. '</span>'
							. '<span class="helion_range_slider_label helion_range_slider_label_avg">'
								. esc_html( round( ( $field_max + $field_min ) / 2, 2 ) )
							. '</span>'
							. '<span class="helion_range_slider_label helion_range_slider_label_max">'
								. esc_html( $field_max )
							. '</span>';
			$output    .= '<div class="helion_range_slider_scale">';
			for ( $i = 0; $i <= 11; $i++ ) {
				$output    .= '<span></span>';
			}
			$output    .= '</div>';
			$values     = explode( ',', $field_val );
			for ( $i = 0; $i < count( $values ); $i++ ) {
				$output .= '<span class="helion_range_slider_label helion_range_slider_label_cur">'
								. esc_html( $values[ $i ] )
							. '</span>';
			}
			$output .= '</div>';
			break;

		case 'text_editor':
			if ( function_exists( 'wp_enqueue_editor' ) ) {
				wp_enqueue_editor();
			}
			ob_start();
			wp_editor(
				$value, $id, array(
					'default_editor' => 'tmce',
					'wpautop'        => isset( $field['wpautop'] ) ? $field['wpautop'] : false,
					'teeny'          => isset( $field['teeny'] ) ? $field['teeny'] : false,
					'textarea_rows'  => isset( $field['rows'] ) && $field['rows'] > 1 ? $field['rows'] : 10,
					'editor_height'  => 16 * ( isset( $field['rows'] ) && $field['rows'] > 1 ? (int) $field['rows'] : 10 ),
					'tinymce'        => array(
						'resize'             => false,
						'wp_autoresize_on'   => false,
						'add_unload_trigger' => false,
					),
				)
			);
			$editor_html = ob_get_contents();
			ob_end_clean();
			$output .= '<div class="helion_text_editor">' . $editor_html . '</div>';
			break;

		case 'scheme_editor':
			if ( ! is_array( $value ) ) {
				break;
			}
			if ( empty( $field['colorpicker'] ) ) {
				$field['colorpicker'] = 'internal';
			}
			$output .= '<div class="helion_scheme_editor">';
			// Select scheme
			$output .= '<div class="helion_scheme_editor_scheme">'
							. '<select class="helion_scheme_editor_selector">';
			foreach ( $value as $scheme => $v ) {
				$output .= '<option value="' . esc_attr( $scheme ) . '">' . esc_html( $v['title'] ) . '</option>';
			}
			$output .= '</select>';
			// Scheme controls
			$output .= '<span class="helion_scheme_editor_controls">'
							. '<span class="helion_scheme_editor_control helion_scheme_editor_control_reset" title="' . esc_attr__( 'Reload scheme', 'helion' ) . '"></span>'
							. '<span class="helion_scheme_editor_control helion_scheme_editor_control_copy" title="' . esc_attr__( 'Duplicate scheme', 'helion' ) . '"></span>'
							. '<span class="helion_scheme_editor_control helion_scheme_editor_control_delete" title="' . esc_attr__( 'Delete scheme', 'helion' ) . '"></span>'
						. '</span>'
					. '</div>';
			// Select type
			$output .= '<div class="helion_scheme_editor_type">'
							. '<div class="helion_scheme_editor_row">'
								. '<span class="helion_scheme_editor_row_cell">'
									. esc_html__( 'Editor type', 'helion' )
								. '</span>'
								. '<span class="helion_scheme_editor_row_cell helion_scheme_editor_row_cell_span">'
									. '<label>'
										. '<input name="helion_scheme_editor_type" type="radio" value="simple" checked="checked"> '
										. '<span class="helion_options_item_holder" tabindex="0"></span>'
										. '<span class="helion_options_item_caption">'
											. esc_html__( 'Simple', 'helion' )
										. '</span>'
									. '</label>'
									. '<label>'
										. '<input name="helion_scheme_editor_type" type="radio" value="advanced"> '
										. '<span class="helion_options_item_holder" tabindex="0"></span>'
										. '<span class="helion_options_item_caption">'
											. esc_html__( 'Advanced', 'helion' )
										. '</span>'
									. '</label>'
								. '</span>'
							. '</div>'
						. '</div>';
			// Colors
			$groups  = helion_storage_get( 'scheme_color_groups' );
			$colors  = helion_storage_get( 'scheme_color_names' );
			$output .= '<div class="helion_scheme_editor_colors">';
			foreach ( $value as $scheme => $v ) {
				$output .= '<div class="helion_scheme_editor_header">'
								. '<span class="helion_scheme_editor_header_cell helion_scheme_editor_row_cell_caption"></span>';
				foreach ( $groups as $group_name => $group_data ) {
					$output .= '<span class="helion_scheme_editor_header_cell helion_scheme_editor_row_cell_color" title="' . esc_attr( $group_data['description'] ) . '">'
								. esc_html( $group_data['title'] )
								. '</span>';
				}
				$output .= '</div>';
				foreach ( $colors as $color_name => $color_data ) {
					$output .= '<div class="helion_scheme_editor_row">'
								. '<span class="helion_scheme_editor_row_cell helion_scheme_editor_row_cell_caption" title="' . esc_attr( $color_data['description'] ) . '">'
								. esc_html( $color_data['title'] )
								. '</span>';
					foreach ( $groups as $group_name => $group_data ) {
						$slug    = 'main' == $group_name
									? $color_name
									: str_replace( 'text_', '', "{$group_name}_{$color_name}" );
						$output .= '<span class="helion_scheme_editor_row_cell helion_scheme_editor_row_cell_color">'
									. ( isset( $v['colors'][ $slug ] )
										? "<input type=\"text\" name=\"{$slug}\" class=\""
											. ( 'tiny' == $field['colorpicker']
												? 'tinyColorPicker'
												: ( 'spectrum' == $field['colorpicker']
													? 'spectrumColorPicker'
													: 'iColorPicker'
												)
												) 
											. '" value="' . esc_attr( $v['colors'][ $slug ] ) . '">'
										: ''
										)
									. '</span>';
					}
					$output .= '</div>';
				}
				break;
			}
			$output .= '</div>'
					. '</div>';
			break;
	}
	return apply_filters( 'helion_filter_show_custom_field', $output, $id, $field, $value );
}


// Refresh data in the linked field
// according the main field value
if ( ! function_exists( 'helion_refresh_linked_data' ) ) {
	function helion_refresh_linked_data( $value, $linked_name ) {
		if ( 'parent_cat' == $linked_name ) {
			$tax   = helion_get_post_type_taxonomy( $value );
			$terms = ! empty( $tax ) ? helion_get_list_terms( false, $tax ) : array();
			$terms = helion_array_merge( array( 0 => esc_html__( '- Select category -', 'helion' ) ), $terms );
			helion_storage_set_array2( 'options', $linked_name, 'options', $terms );
		}
	}
}


// AJAX: Refresh data in the linked fields
if ( ! function_exists( 'helion_callback_get_linked_data' ) ) {
	add_action( 'wp_ajax_helion_get_linked_data', 'helion_callback_get_linked_data' );
	function helion_callback_get_linked_data() {
		if ( ! wp_verify_nonce( helion_get_value_gp( 'nonce' ), admin_url( 'admin-ajax.php' ) ) ) {
			wp_die();
		}
		$response  = array( 'error' => '' );
		if ( ! empty( $_REQUEST['chg_name'] ) ) {
			$chg_name  = wp_kses_data( wp_unslash( $_REQUEST['chg_name'] ) );
			$chg_value = wp_kses_data( wp_unslash( $_REQUEST['chg_value'] ) );
			if ( 'post_type' == $chg_name ) {
				$tax              = helion_get_post_type_taxonomy( $chg_value );
				$terms            = ! empty( $tax ) ? helion_get_list_terms( false, $tax ) : array();
				$response['list'] = helion_array_merge( array( 0 => esc_html__( '- Select category -', 'helion' ) ), $terms );
			}
		}
		echo json_encode( $response );
		wp_die();
	}
}
