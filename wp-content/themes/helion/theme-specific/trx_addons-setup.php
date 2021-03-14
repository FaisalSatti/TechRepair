<?php
/* Theme-specific action to configure ThemeREX Addons components
------------------------------------------------------------------------------- */


/* ThemeREX Addons components
------------------------------------------------------------------------------- */
if ( ! function_exists( 'helion_trx_addons_theme_specific_components' ) ) {
	add_filter( 'trx_addons_filter_components_editor', 'helion_trx_addons_theme_specific_components' );
	function helion_trx_addons_theme_specific_components( $enable = false ) {
		return HELION_THEME_FREE
					? false     // Free version
					: false;     // Pro version or Developer mode
	}
}

if ( ! function_exists( 'helion_trx_addons_theme_specific_setup1' ) ) {
	add_action( 'after_setup_theme', 'helion_trx_addons_theme_specific_setup1', 1 );
	function helion_trx_addons_theme_specific_setup1() {
		if ( helion_exists_trx_addons() ) {
			add_filter( 'trx_addons_cv_enable', 'helion_trx_addons_cv_enable' );
            add_filter( 'trx_addons_addons_list', 'helion_trx_addons_addons_list', 100 );
			add_filter( 'trx_addons_demo_enable', 'helion_trx_addons_demo_enable' );
			add_filter( 'trx_addons_filter_edd_themes_market', 'helion_trx_addons_edd_themes_market_enable' );
			add_filter( 'trx_addons_api_list', 'helion_trx_addons_api_list' );
			add_filter( 'trx_addons_cpt_list', 'helion_trx_addons_cpt_list' );
			add_filter( 'trx_addons_sc_list', 'helion_trx_addons_sc_list' );
			add_filter( 'trx_addons_widgets_list', 'helion_trx_addons_widgets_list' );
		}
	}
}

// Addons
if ( ! function_exists( 'helion_trx_addons_addons_list' ) ) {
    //Handler of the add_filter( 'trx_addons_addons_list', 'helion_trx_addons_addons_list', 100 );
    function helion_trx_addons_addons_list( $list = array() ) {
        // To do: Enable/Disable theme-specific addons via add/remove it in the list
        if ( is_array( $list ) ) {
            $required_addons = array(
            'image-effects'   => array( 'title' => esc_html__( 'Image effects', 'helion' ) ),
            );
            foreach( $required_addons as $k => $v ) {
                if ( ! isset( $list[ $k ] ) || ! is_array( $list[ $k ] ) ) {
                    $list[ $k ] = $v;
                }
                $list[ $k ]['required'] = true;
            }
        }
        return $list;
    }
}




if ( ! function_exists( 'helion_trx_addons_theme_specific_setup' ) ) {
	add_action( 'after_setup_theme', 'helion_trx_addons_theme_specific_setup' );
	function helion_trx_addons_theme_specific_setup() {
		if ( helion_exists_trx_addons() ) {
			if ( ! is_admin() ) {
				add_action( 'helion_action_before_post_meta', 'helion_trx_addons_action_before_post_meta' );
			}
		}
	}
}


// CV
if ( ! function_exists( 'helion_trx_addons_cv_enable' ) ) {
	
	function helion_trx_addons_cv_enable( $enable = false ) {
		// To do: return false if theme not use CV functionality
		return HELION_THEME_FREE
					? false     // Free version
					: true;     // Pro version
	}
}

// Demo mode
if ( ! function_exists( 'helion_trx_addons_demo_enable' ) ) {
	
	function helion_trx_addons_demo_enable( $enable = false ) {
		// To do: return false if theme not use Demo functionality
		return HELION_THEME_FREE
					? false     // Free version
					: true;     // Pro version
	}
}

// EDD Themes market
if ( ! function_exists( 'helion_trx_addons_edd_themes_market_enable' ) ) {
	
	function helion_trx_addons_edd_themes_market_enable( $enable = false ) {
		// To do: return false if theme not Themes market functionality
		return HELION_THEME_FREE
					? false     // Free version
					: true;     // Pro version
	}
}


// API
if ( ! function_exists( 'helion_trx_addons_api_list' ) ) {
	
	function helion_trx_addons_api_list( $list = array() ) {
		// To do: Enable/Disable Third-party plugins API via add/remove it in the list

		// If it's a free version - leave only basic set
		if ( HELION_THEME_FREE ) {
			$free_api = array( 'elementor', 'instagram_feed', 'woocommerce', 'contact-form-7' );
			foreach ( $list as $k => $v ) {
				if ( ! in_array( $k, $free_api ) ) {
					unset( $list[ $k ] );
				}
			}
		}
		return $list;
	}
}


// CPT
if ( ! function_exists( 'helion_trx_addons_cpt_list' ) ) {
	
	function helion_trx_addons_cpt_list( $list = array() ) {
		// To do: Enable/Disable CPT via add/remove it in the list

		// If it's a free version - leave only basic set
		if ( HELION_THEME_FREE ) {
			$free_cpt = array( 'layouts', 'portfolio', 'post', 'services', 'team', 'testimonials' );
			foreach ( $list as $k => $v ) {
				if ( ! in_array( $k, $free_cpt ) ) {
					unset( $list[ $k ] );
				}
			}
		}
		return $list;
	}
}

// Shortcodes
if ( ! function_exists( 'helion_trx_addons_sc_list' ) ) {
	
	function helion_trx_addons_sc_list( $list = array() ) {
		// To do: Add/Remove shortcodes into list
		// If you add new shortcode - in the theme's folder must exists /trx_addons/shortcodes/new_sc_name/new_sc_name.php

		// If it's a free version - leave only basic set
		if ( HELION_THEME_FREE ) {
			$free_shortcodes = array( 'action', 'anchor', 'blogger', 'button', 'form', 'icons', 'price', 'promo', 'socials' );
			foreach ( $list as $k => $v ) {
				if ( ! in_array( $k, $free_shortcodes ) ) {
					unset( $list[ $k ] );
				}
			}
		}


        //Blogger layouts
        if ( isset( $list['blogger']['layouts_sc']['news']) ) {
            unset($list['blogger']['templates']['news']['magazine']);
        }
        //Blogger Templates
        $list['blogger']['templates']['default']['only_featured'] = array(
            'title' => esc_html__('Only Featured Image', 'helion'),
            'layout' => array(
                'featured' => array(
                ),
            )
        );
        $list['blogger']['templates']['default']['classic_alter'] = array(
            'title' => esc_html__('Classic Alternative', 'helion'),
            'layout' => array(
                'featured' => array(
                ),
                'content' => array(
                    'meta', 'title', 'excerpt', 'readmore'
                )
            )
        );
        $list['blogger']['templates']['default']['category_top'] = array(
            'title' => esc_html__('Info over image (category top)', 'helion'),
            'layout' => array(
                'featured' => array(
                    'tl' => array(
                        'meta_categories'
                    ),
                    'bl' => array(
                        'title', 'meta_date'
                    ),
                ),
            )
        );
        $list['blogger']['templates']['default']['category_top_extra'] = array(
            'title' => esc_html__('Info over image (category top extra)', 'helion'),
            'layout' => array(
                'featured' => array(
                    'tl' => array(
                        'meta_categories'
                    ),
                    'bl' => array(
                        'title', 'meta_date'
                    ),
                ),
            )
        );

        $list['blogger']['templates']['default']['category_center'] = array(
            'title' => esc_html__('Info over image (category center)', 'helion'),
            'layout' => array(
                'featured' => array(
                    'mc' => array(
                        'title', 'meta_categories'
                    ),
                ),
            )
        );

        $list['blogger']['templates']['default']['over_bottom_left'] = array(
            'title' => esc_html__('Info over image (bottom left)', 'helion'),
            'layout' => array(
                'featured' => array(
                    'bl' => array(
                        'meta_categories', 'title'
                    ),
                ),
            )
        );


        //Announce
        $list['blogger']['templates']['news']['announce'] = array(
            'title' => esc_html__('Announce', 'helion'),
            'grid'  => array(
                // One post
                array(
                    'grid-layout' => array(
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                    )
                ),
                // Two posts
                array(
                    'grid-layout' => array(
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full'  )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full'  )
                        ),
                    )
                ),
                // Three posts
                array(
                    'grid-layout' => array(
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full'  )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full'  )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full'  )
                        ),
                    )
                ),
                // Four posts
                array(
                    'grid-layout' => array(
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full'  )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle'  )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle'  )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full'  )
                        ),
                    )
                ),
                // Five posts
                array(
                    'grid-layout' => array(
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full'  )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle'  )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle'  )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle'  )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle'  )
                        ),
                    )
                ),
                // Six posts
                array(
                    'grid-layout' => array(
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                    )
                ),
                // Seven posts
                array(
                    'grid-layout' => array(
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                    )
                ),
                // Eight posts
                array(
                    'grid-layout' => array(
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1,  'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle')
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                    )
                ),
                // Nine posts
                array(
                    'grid-layout' => array(
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1,  'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                    )
                ),
                // Ten posts
                array(
                    'grid-layout' => array(
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/over_bottom_left',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                    )
                ),
            )
        );

        //Modern
        $list['blogger']['templates']['news']['modern'] = array(
            'title' => esc_html__('Modern', 'helion'),
            'grid'  => array(
                // One post
                array(
                    'grid-layout' => array(
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1,'thumb_size' => 'full' )
                        ),
                    )
                ),
                // Two posts
                array(
                    'grid-layout' => array(
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                    )
                ),
                // Three posts
                array(
                    'grid-layout' => array(
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                    )
                ),
                // Four posts
                array(
                    'grid-layout' => array(
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full'  )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                    )
                ),
                // Five posts
                array(
                    'grid-layout' => array(
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                    )
                ),
                // Six posts
                array(
                    'grid-layout' => array(
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                    )
                ),
                // Seven posts
                array(
                    'grid-layout' => array(
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/only_featured',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                    )
                ),
                // Eight posts
                array(
                    'grid-layout' => array(
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                    )
                ),
                // Nine posts
                array(
                    'grid-layout' => array(
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                    )
                ),
                // Ten posts
                array(
                    'grid-layout' => array(
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                    )
                ),
                // Eleven posts
                array(
                    'grid-layout' => array(
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                    )
                ),

                // Twelve posts
                array(
                    'grid-layout' => array(
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'rectangle' )
                        ),
                        array(
                            'template' => 'default/category_center',
                            'args' => array( 'image_ratio' => '16:9', 'columns' => 1, 'thumb_size' => 'full' )
                        ),
                    )
                ),
            )
        );


		return $list;
	}
}


// Widgets
if ( ! function_exists( 'helion_trx_addons_widgets_list' ) ) {
	
	function helion_trx_addons_widgets_list( $list = array() ) {
		// To do: Add/Remove widgets into list
		// If you add widget - in the theme's folder must exists /trx_addons/widgets/new_widget_name/new_widget_name.php

		// If it's a free version - leave only basic set
		if ( HELION_THEME_FREE ) {
			$free_widgets = array( 'aboutme', 'banner', 'contacts', 'flickr', 'popular_posts', 'recent_posts', 'slider', 'socials' );
			foreach ( $list as $k => $v ) {
				if ( ! in_array( $k, $free_widgets ) ) {
					unset( $list[ $k ] );
				}
			}
		}
		return $list;
	}
}

// Add mobile menu to the plugin's cached menu list
if ( ! function_exists( 'helion_trx_addons_menu_cache' ) ) {
	add_filter( 'trx_addons_filter_menu_cache', 'helion_trx_addons_menu_cache' );
	function helion_trx_addons_menu_cache( $list = array() ) {
		if ( in_array( '#menu_main', $list ) ) {
			$list[] = '#menu_mobile';
		}
		$list[] = '.menu_mobile_inner > nav > ul';
		return $list;
	}
}

// Add theme-specific vars into localize array
if ( ! function_exists( 'helion_trx_addons_localize_script' ) ) {
	add_filter( 'helion_filter_localize_script', 'helion_trx_addons_localize_script' );
	function helion_trx_addons_localize_script( $arr ) {
		$arr['alter_link_color'] = helion_get_scheme_color( 'alter_link' );
		return $arr;
	}
}


// Shortcodes support
//------------------------------------------------------------------------

// Add new output types (layouts) in the shortcodes
if ( ! function_exists( 'helion_trx_addons_sc_type' ) ) {
	add_filter( 'trx_addons_sc_type', 'helion_trx_addons_sc_type', 10, 2 );
	function helion_trx_addons_sc_type( $list, $sc ) {
		// To do: check shortcode slug and if correct - add new 'key' => 'title' to the list
		if ( 'trx_sc_blogger' == $sc ) {
			$list = helion_array_merge( $list, helion_get_list_blog_styles( false, 'sc' ) );
		}
        if ( 'trx_sc_title' == $sc ) {
            $list['decoration'] = 'Decoration';
        }
        if ( 'trx_sc_title' == $sc ) {
            $list['alive'] = 'Alive';
        }
        if ( 'trx_sc_icons' == $sc ) {
            $list['extra'] = 'Extra';
        }
        if ( 'trx_sc_button' == $sc ) {
            $list['plain'] = 'Plain';
        }
        if ( 'trx_sc_testimonials' == $sc ) {
            $list['plain'] = 'Plain';
        }
        if ( 'trx_sc_testimonials' == $sc ) {
            $list['classic'] = 'Classic';
        }
        if ( 'trx_sc_services' == $sc ) {
            $list['modern'] = 'Modern';
        }
        if ( 'trx_sc_layouts_search' == $sc ) {
            $list['extra'] = 'Extra';
        }
        if ('trx_sc_layouts_menu' == $sc ) {
            $list['modern'] = 'Modern Burger';
        }

		return $list;
	}
}

// Add params values to the shortcode's atts
if ( ! function_exists( 'helion_trx_addons_sc_prepare_atts' ) ) {
	add_filter( 'trx_addons_filter_sc_prepare_atts', 'helion_trx_addons_sc_prepare_atts', 10, 2 );
	function helion_trx_addons_sc_prepare_atts( $atts, $sc ) {
		if ( 'trx_sc_blogger' == $sc ) {
			$list = helion_get_list_blog_styles( false, 'sc' );
			if ( isset( $list[ $atts['type'] ] ) ) {
				$custom_type = '';
				if ( strpos( $atts['type'], 'blog-custom-' ) === 0 ) {
					$blog_id = helion_get_custom_blog_id( $atts['type'] );
					$blog_meta = helion_get_custom_layout_meta( $blog_id );
					$custom_type = ! empty( $blog_meta['scripts_required'] ) ? $blog_meta['scripts_required'] : 'custom';
				}
				// Classes for the container with posts
				$columns = $atts['columns'] > 0
								? $atts['columns']
								: ( 1 < $atts['count']
									? $atts['count']
									: ( -1 == $atts['count']
										? 3
										: 1
										)
									);
				$atts['posts_container'] = 'posts_container'
					. ' ' . esc_attr( $atts['type'] ) . '_wrap'
					. ( $columns > 1
							? ' ' . esc_attr( $atts['type'] ) . '_' . $columns 
							: '' )
					. ( in_array( $atts['type'], array( 'portfolio', 'gallery' ) )
							? ' masonry_wrap' . ( $columns > 1 ? ' masonry_' . $columns : '' )
							: '' )
					. ( 'gallery' == $atts['type']
							? ' portfolio_wrap' . ( $columns > 1 ? ' portfolio_' . $columns : '' )
							: '' )
					. ( in_array( $atts['type'], array( 'classic', 'excerpt', 'plain' ) ) && $columns > 1
							? ' columns_wrap columns_padding_bottom' 
							: '' )
					. ( ! empty( $custom_type )
							? ( in_array( $custom_type, array( 'gallery', 'portfolio', 'masonry' ) )
								? ' ' . esc_attr( $custom_type ) . '_wrap' . ( $columns > 1 ? ' ' . esc_attr( $custom_type . '_' . $columns ) : '' )
								: ' columns_wrap columns_padding_bottom' )
							: '' )
					;
				// Scripts for masonry and portfolio
				if ( in_array( $atts['type'], array( 'gallery', 'portfolio', 'masonry' ) ) || in_array( $custom_type, array( 'gallery', 'portfolio', 'masonry' ) ) ) {
					helion_load_masonry_scripts();
				}
			}
		}
		return $atts;
	}
}


// Add new params to the default shortcode's atts
if ( ! function_exists( 'helion_trx_addons_sc_atts' ) ) {
	add_filter( 'trx_addons_sc_atts', 'helion_trx_addons_sc_atts', 10, 2 );
	function helion_trx_addons_sc_atts( $atts, $sc ) {

		// Param 'scheme'
		if ( in_array(
			$sc, array(
				'trx_sc_action',
				'trx_sc_blogger',
				'trx_sc_cars',
				'trx_sc_courses',
				'trx_sc_content',
				'trx_sc_dishes',
				'trx_sc_events',
				'trx_sc_form',
				'trx_sc_googlemap',
				'trx_sc_yandexmap',
				'trx_sc_osmap',
				'trx_sc_layouts',
				'trx_sc_portfolio',
				'trx_sc_price',
				'trx_sc_promo',
				'trx_sc_properties',
				'trx_sc_services',
				'trx_sc_team',
				'trx_sc_testimonials',
				'trx_sc_title',
				'trx_widget_audio',
				'trx_widget_twitter',
				'trx_sc_layouts_container',
			)
		) ) {
			$atts['scheme'] = 'inherit';
		}
		// Param 'color_style'
		if ( in_array(
			$sc, array(
				'trx_sc_action',
				'trx_sc_blogger',
				'trx_sc_cars',
				'trx_sc_courses',
				'trx_sc_content',
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
				'trx_sc_team',
				'trx_sc_testimonials',
				'trx_sc_title',
				'trx_widget_audio',
				'trx_widget_twitter'
			)
		) ) {
			$atts['color_style'] = 'default';
		}
		if ( in_array(
			$sc, array(
				'trx_sc_button',
			)
		) ) {
			if ( is_array( $atts['buttons'] ) ) {
				foreach( $atts['buttons'] as $k => $v ) {
					$atts['buttons'][ $k ]['color_style'] = 'default';
				}
			}
		}
        if ( 'trx_sc_services' == $sc ) {
            $atts['icon_link'] = '';
            $atts['show_subtitle'] = '';
        }

		return $atts;
	}
}

// Add new params to the shortcodes VC map
if ( ! function_exists( 'helion_trx_addons_sc_map' ) ) {
	add_filter( 'trx_addons_sc_map', 'helion_trx_addons_sc_map', 10, 2 );
	function helion_trx_addons_sc_map( $params, $sc ) {

		// Param 'scheme'
		if ( in_array(
			$sc, array(
				'trx_sc_action',
				'trx_sc_blogger',
				'trx_sc_cars',
				'trx_sc_courses',
				'trx_sc_content',
				'trx_sc_dishes',
				'trx_sc_events',
				'trx_sc_form',
				'trx_sc_googlemap',
				'trx_sc_yandexmap',
				'trx_sc_osmap',
				'trx_sc_layouts',
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
				'trx_widget_twitter',
				'trx_sc_layouts_container',
			)
		) ) {
			if ( empty( $params['params'] ) || ! is_array( $params['params'] ) ) {
				$params['params'] = array();
			}
			$params['params'][] = array(
				'param_name'  => 'scheme',
				'heading'     => esc_html__( 'Color scheme', 'helion' ),
				'description' => wp_kses_data( __( 'Select color scheme to decorate this block', 'helion' ) ),
				'group'       => esc_html__( 'Colors', 'helion' ),
				'admin_label' => true,
				'value'       => array_flip( helion_get_list_schemes( true ) ),
				'type'        => 'dropdown',
			);
		}
		// Param 'color_style'
		$param = array(
			'param_name'       => 'color_style',
			'heading'          => esc_html__( 'Color style', 'helion' ),
			'description'      => wp_kses_data( __( 'Select color style to decorate this block', 'helion' ) ),
			'edit_field_class' => 'vc_col-sm-4',
			'admin_label'      => true,
			'value'            => array_flip( helion_get_list_sc_color_styles() ),
			'type'             => 'dropdown',
		);
		if ( in_array( $sc, array( 'trx_sc_button' ) ) ) {
			if ( empty( $params['params'] ) || ! is_array( $params['params'] ) ) {
				$params['params'] = array();
			}
			foreach ( $params['params'] as $k => $p ) {
				if ( 'buttons' == $p['param_name'] ) {
					if ( ! empty( $p['params'] ) ) {
						$new_params = array();
						foreach ( $p['params'] as $v ) {
							$new_params[] = $v;
							if ( 'size' == $v['param_name'] ) {
								$new_params[] = $param;
							}
						}
						$params['params'][ $k ]['params'] = $new_params;
					}
				}
			}
		} elseif ( in_array(
			$sc, array(
				'trx_sc_action',
				'trx_sc_blogger',
				'trx_sc_cars',
				'trx_sc_courses',
				'trx_sc_content',
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
				'trx_widget_twitter',
			)
		) ) {
			if ( empty( $params['params'] ) || ! is_array( $params['params'] ) ) {
				$params['params'] = array();
			}
			$new_params = array();
			foreach ( $params['params'] as $v ) {
				if ( in_array( $v['param_name'], array( 'title_style', 'title_tag', 'title_align' ) ) ) {
					$v['edit_field_class'] = 'vc_col-sm-6';
				}
				$new_params[] = $v;
				if ( 'title_align' == $v['param_name'] ) {
					if ( ! empty( $v['group'] ) ) {
						$param['group'] = $v['group'];
					}
					$param['edit_field_class'] = 'vc_col-sm-6';
					$new_params[]              = $param;
				}
			}
			$params['params'] = $new_params;
		}

		return $params;
	}
}



// Add classes to the shortcode's output from new params
if ( ! function_exists( 'helion_trx_addons_sc_output' ) ) {
	add_filter( 'trx_addons_sc_output', 'helion_trx_addons_sc_output', 10, 4 );
	function helion_trx_addons_sc_output( $output, $sc, $atts, $content ) {
		$sc = str_replace( array( 'trx_widget', 'trx_' ), array( 'sc_widget', '' ), $sc );
		if ( substr( $sc, -3 ) == 'map' ) {
			$sc = str_replace( 'map', 'map_content', $sc );
		}
		if ( ! empty( $atts['scheme'] ) && ! helion_is_inherit( $atts['scheme'] ) ) {
			$output = str_replace( 'class="' . esc_attr( $sc ) . ' ', 'class="' . esc_attr( $sc ) . ' scheme_' . esc_attr( $atts['scheme'] ) . ' ', $output );
		}
		if ( ! empty( $atts['color_style'] ) && ! helion_is_inherit( $atts['color_style'] ) && 'default' != $atts['color_style'] ) {
			$output = str_replace( 'class="' . esc_attr( $sc ) . ' ', 'class="' . esc_attr( $sc ) . ' color_style_' . esc_attr( $atts['color_style'] ) . ' ', $output );
		}

		return $output;
	}
}

// Add color_style to the button items
if ( ! function_exists( 'helion_trx_addons_sc_item_link_classes' ) ) {
	add_filter( 'trx_addons_filter_sc_item_link_classes', 'helion_trx_addons_sc_item_link_classes', 10, 3 );
	function helion_trx_addons_sc_item_link_classes( $class, $sc, $atts=array() ) {
		if ( 'sc_button' == $sc ) {
			if ( ! empty( $atts['color_style'] ) && ! helion_is_inherit( $atts['color_style'] ) && 'default' != $atts['color_style'] ) {
				$class .= ' color_style_' . esc_attr( $atts['color_style'] );
			}
		}
		return $class;
	}
}



// Return tag for the item's title
if ( ! function_exists( 'helion_trx_addons_sc_item_title_tag' ) ) {
	add_filter( 'trx_addons_filter_sc_item_title_tag', 'helion_trx_addons_sc_item_title_tag' );
	function helion_trx_addons_sc_item_title_tag( $tag = '' ) {
		return 'h1' == $tag ? 'h2' : $tag;
	}
}

// Return args for the item's button
if ( ! function_exists( 'helion_trx_addons_sc_item_button_args' ) ) {
	add_filter( 'trx_addons_filter_sc_item_button_args', 'helion_trx_addons_sc_item_button_args', 10, 3 );
	function helion_trx_addons_sc_item_button_args( $args, $sc, $sc_args ) {
		if ( ! empty( $sc_args['color_style'] ) ) {
			$args['color_style'] = $sc_args['color_style'];
		}
		return $args;
	}
}

// Return theme specific title layout for the slider
if ( ! function_exists( 'helion_trx_addons_slider_title' ) ) {
	add_filter( 'trx_addons_filter_slider_title', 'helion_trx_addons_slider_title', 10, 2 );
	function helion_trx_addons_slider_title( $title, $data ) {
		$title = '';
        if ( ! empty( $data['cats'] ) ) {
            $title .= sprintf( '<div class="slide_cats">%s</div>', $data['cats'] );
        }
		if ( ! empty( $data['title'] ) ) {
			$title .= '<h3 class="slide_title">'
						. ( ! empty( $data['link'] ) ? '<a href="' . esc_url( $data['link'] ) . '">' : '' )
						. esc_html( $data['title'] )
						. ( ! empty( $data['link'] ) ? '</a>' : '' )
						. '</h3>';
		}
		return $title;
	}
}

// Add new styles to the Google map
if ( ! function_exists( 'helion_trx_addons_sc_googlemap_styles' ) ) {
	add_filter( 'trx_addons_filter_sc_googlemap_styles', 'helion_trx_addons_sc_googlemap_styles' );
	function helion_trx_addons_sc_googlemap_styles( $list ) {
		$list['dark'] = esc_html__( 'Dark', 'helion' );
		return $list;
	}
}

// Show reactions in the single posts
if ( ! function_exists( 'helion_trx_addons_action_before_post_meta' ) ) {
	
	function helion_trx_addons_action_before_post_meta() {
		if ( helion_exists_trx_addons() ) {
			if ( is_single() && ! is_attachment() && ! trx_addons_sc_stack_check() ) {
				// Emotions
				if ( trx_addons_is_on( trx_addons_get_option( 'emotions_allowed' ) ) ) {
					trx_addons_get_post_reactions( true );
				}
			}
		}
	}
}

// WP Editor addons
//------------------------------------------------------------------------

// Theme-specific configure of the WP Editor
if ( ! function_exists( 'helion_trx_addons_tiny_mce_style_formats' ) ) {
	add_filter( 'trx_addons_filter_tiny_mce_style_formats', 'helion_trx_addons_tiny_mce_style_formats' );
	function helion_trx_addons_tiny_mce_style_formats( $style_formats ) {
		// Add style 'Arrow' to the 'List styles'
		// Remove 'false &&' from the condition below to add new style to the list
		if ( is_array( $style_formats ) && count( $style_formats ) > 0 ) {
			foreach ( $style_formats as $k => $v ) {
				if ( esc_html__( 'List styles', 'helion' ) == $v['title'] ) {
                    $style_formats[ $k ]['items'][] = array(
                        'title'    => esc_html__( 'Arrow', 'helion' ),
                        'selector' => 'ul',
                        'classes'  => 'trx_addons_list trx_addons_list_arrow',
                    );
                }
                if ( esc_html__( 'List styles', 'helion' ) == $v['title'] ) {
                    $style_formats[ $k ]['items'][] = array(
                        'title'    => esc_html__( 'On Plate', 'helion' ),
                        'selector' => 'ul',
                        'classes'  => 'trx_addons_on_plate',
                    );
                }
                if (esc_html__('Inline', 'helion') == $v['title']) {
                    $style_formats[$k]['items'][] = array(
                        'title' => esc_html__('Tiny Font', 'helion'),
                        'inline' => 'span',
                        'classes' => 'trx_addons_tiny_font',
                    );
                }
                if (esc_html__('Headers', 'helion') == $v['title']) {
                    $style_formats[$k]['items'][] = array(
                        'title' => esc_html__('Title with Link', 'helion'),
                        'selector' => 'h1,h2,h3,h4,h5,h6',
                        'classes' => 'trx_addons_title_with_link',
                    );
                }

			}
		}
		return $style_formats;
	}
}


// Setup team and portflio pages
//------------------------------------------------------------------------

// Disable override header image on team and portfolio pages
if ( ! function_exists( 'helion_trx_addons_allow_override_header_image' ) ) {
	add_filter( 'helion_filter_allow_override_header_image', 'helion_trx_addons_allow_override_header_image' );
	function helion_trx_addons_allow_override_header_image( $allow ) {
		return is_single()
				&& (
					helion_is_team_page()
					|| helion_is_cars_page()
					|| helion_is_cars_agents_page()
					|| helion_is_properties_agents_page()
					)
				? false
				: $allow;
	}
}

// Get thumb size for the team items
if ( ! function_exists( 'helion_trx_addons_thumb_size' ) ) {
	add_filter( 'trx_addons_filter_thumb_size', 'helion_trx_addons_thumb_size', 10, 2 );
	function helion_trx_addons_thumb_size( $thumb_size = '', $type = '' ) {

        	  if ($type == 'team-default')
        		  $thumb_size = helion_get_thumb_size('portrait');

              if ($type == 'testimonials-default')
                  $thumb_size = helion_get_thumb_size('square');

              if ($type == 'testimonials-classic')
                  $thumb_size = helion_get_thumb_size('square');
		return $thumb_size;
	}
}

// Add fields to the meta box for the team members
// All other CPT meta boxes may be modified in the same method
if ( ! function_exists( 'helion_trx_addons_meta_box_fields' ) ) {
	add_filter( 'trx_addons_filter_meta_box_fields', 'helion_trx_addons_meta_box_fields', 10, 2 );
	function helion_trx_addons_meta_box_fields( $mb, $post_type ) {
		if ( defined( 'TRX_ADDONS_CPT_TEAM_PT' ) && TRX_ADDONS_CPT_TEAM_PT == $post_type ) {
			if ( ! isset( $mb['email'] ) ) {
				$mb['email'] = array(
					'title'   => esc_html__( 'E-mail', 'helion' ),
					'desc'    => wp_kses_data( __( "Team member's email", 'helion' ) ),
					'std'     => '',
					'details' => true,
					'type'    => 'text',
				);
			}
		}
        if (defined('TRX_ADDONS_CPT_PORTFOLIO_PT') && $post_type==TRX_ADDONS_CPT_PORTFOLIO_PT) {
            if ( !isset( $mb['gallery_wide'] ) ) {
                $mb['gallery_wide'] = array(
                    "title" => esc_html__("Gallery wide", 'helion'),
                    'desc'    => wp_kses_data( __( "The gallery is getting wide (details position not left or right), if the sidebar is hidden", 'helion' ) ),
                    "std" => '',
                    "dependency" => array(
                        "gallery" => array("not_empty"),
                    ),
                    "type" => "switch"
                );
            }
            if ( !isset( $mb['featured_wide'] ) ) {
                $mb['featured_wide'] = array(
                    "title" => esc_html__("Featured image wide", 'helion'),
                    'desc'    => wp_kses_data( __( "The featured image is getting wide (details position not left or right), if the sidebar is hidden", 'helion' ) ),
                    "std" => '',
                    "type" => "switch"
                );
            }

            if ( !isset( $mb['related_wide'] ) ) {
                $mb['related_wide'] = array(
                    "title" => esc_html__("Related posts wide", 'helion'),
                    'desc'    => wp_kses_data( __( "The related posts is getting wide, if the sidebar is hidden", 'helion' ) ),
                    "std" => '',
                    "type" => "switch"
                );
            }

            if ( !isset( $mb['portfolio_meta'] ) ) {
                $mb['portfolio_meta'] = array(
                    "title" => esc_html__('Project meta', 'helion'),
                    "type" => "section"
                );
            }

            if ( !isset( $mb['move_title'] ) ) {
                $mb['move_title'] = array(
                    "title" => esc_html__("Move title",  'helion'),
                    "desc" => wp_kses_data( __("Move title in project details section (If title is not used in header)", 'helion') ),
                    "std"   => 0,
                    "type"  => "switch"
                );
            }

            if ( !isset( $mb['disable_meta'] ) ) {
                $mb['disable_meta'] = array(
                    "title" => esc_html__("Disable meta",  'helion'),
                    "std"   => 0,
                    "section"   => "",
                    "type"  => "switch"
                );
            }

        }
		return $mb;

	}
}

// Add menu hover in List
if ( !function_exists( 'helion_filter_get_list_menu_hover' ) ) {
    add_filter('trx_addons_filter_get_list_menu_hover', 'helion_filter_get_list_menu_hover');
    function helion_filter_get_list_menu_hover($list)
    {
        $list['strike'] = esc_html__( 'Strike', 'helion' );
        return $list;
    }
}


// Remove input hover effects
if ( !function_exists( 'helion_filter_get_list_input_hover' ) ) {
    add_filter( 'trx_addons_filter_get_list_input_hover', 'helion_filter_get_list_input_hover');
    function helion_filter_get_list_input_hover($list) {
        unset($list['accent']);
        unset($list['path']);
        unset($list['jump']);
        unset($list['underline']);
        unset($list['iconed']);
        return $list;
    }
}

// Video Cover Thumb Size
if ( !function_exists( 'helion_filter_video_cover_thumb_size' ) ) {
    add_filter( 'trx_addons_filter_video_cover_thumb_size', 'helion_filter_video_cover_thumb_size');
    function helion_filter_video_cover_thumb_size($thumb_size) {
        $thumb_size = helion_get_thumb_size('full');
        return $thumb_size;
    }
}
