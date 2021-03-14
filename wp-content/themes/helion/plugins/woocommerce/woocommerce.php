<?php
/* Woocommerce support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 1 - register filters, that add/remove lists items for the Theme Options
if ( ! function_exists( 'helion_woocommerce_theme_setup1' ) ) {
	add_action( 'after_setup_theme', 'helion_woocommerce_theme_setup1', 1 );
	function helion_woocommerce_theme_setup1() {

		// Theme-specific parameters for WooCommerce
		add_theme_support(
			'woocommerce', array(
				// Image width for thumbnails gallery
				'gallery_thumbnail_image_width' => 150,
			)
		);

		// Next setting from the WooCommerce 3.0+ enable built-in image zoom on the single product page
		add_theme_support( 'wc-product-gallery-zoom' );

		// Next setting from the WooCommerce 3.0+ enable built-in image slider on the single product page
		add_theme_support( 'wc-product-gallery-slider' );

		// Next setting from the WooCommerce 3.0+ enable built-in image lightbox on the single product page
		add_theme_support( 'wc-product-gallery-lightbox' );

		// Disable WooCommerce ads
		add_filter( 'woocommerce_allow_marketplace_suggestions', '__return_false' );

		// Add new sidebar for WooCommerce
		add_filter( 'helion_filter_list_sidebars', 'helion_woocommerce_list_sidebars' );

		// Add 'product' to the list with supported post types
		add_filter( 'helion_filter_list_posts_types', 'helion_woocommerce_list_post_types' );

		// Detect if WooCommerce support 'Product Grid' feature
		$product_grid = helion_exists_woocommerce() && function_exists( 'wc_get_theme_support' ) ? wc_get_theme_support( 'product_grid' ) : false;
		add_theme_support( 'wc-product-grid-enable', isset( $product_grid['min_columns'] ) && isset( $product_grid['max_columns'] ) );
	}
}

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'helion_woocommerce_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'helion_woocommerce_theme_setup3', 3 );
	function helion_woocommerce_theme_setup3() {
		if ( helion_exists_woocommerce() ) {

			// Section 'WooCommerce'
			helion_storage_set_array_before(
				'options', 'fonts', array_merge(
					array(
						'shop'               => array(
							'title'      => esc_html__( 'Shop', 'helion' ),
							'desc'       => wp_kses_data( __( 'Select theme-specific parameters to display the shop pages', 'helion' ) ),
							'priority'   => 80,
							'expand_url' => esc_url( helion_woocommerce_get_shop_page_link() ),
							'icon'       => 'icon-cart-2',
							'type'       => 'section',
						),

						'products_info_shop' => array(
							'title'  => esc_html__( 'Products list', 'helion' ),
							'desc'   => '',
							'qsetup' => esc_html__( 'General', 'helion' ),
							'type'   => 'info',
						),
						'shop_mode'          => array(
							'title'   => esc_html__( 'Shop style', 'helion' ),
							'desc'    => wp_kses_data( __( 'Select style for the products list. Attention! If the visitor has already selected the list type at the top of the page - his choice is remembered and has priority over this option', 'helion' ) ),
							'std'     => 'thumbs',
							'options' => array(
								'thumbs' => esc_html__( 'Grid', 'helion' ),
								'list'   => esc_html__( 'List', 'helion' ),
							),
							'qsetup'  => esc_html__( 'General', 'helion' ),
							'type'    => 'select',
						),
					),
					! get_theme_support( 'wc-product-grid-enable' )
					? array(
						'posts_per_page_shop' => array(
							'title' => esc_html__( 'Products per page', 'helion' ),
							'desc'  => wp_kses_data( __( 'How many products should be displayed on the shop page. If empty - use global value from the menu Settings - Reading', 'helion' ) ),
							'std'   => '',
							'type'  => 'text',
						),
						'blog_columns_shop'   => array(
							'title'      => esc_html__( 'Grid columns', 'helion' ),
							'desc'       => wp_kses_data( __( 'How many columns should be used for the shop products in the grid view (from 2 to 4)?', 'helion' ) ),
							'dependency' => array(
								'shop_mode' => array( 'thumbs' ),
							),
							'std'        => 2,
							'options'    => helion_get_list_range( 2, 4 ),
							'type'       => 'select',
						),
					)
					: array(),
					array(
						'blog_animation_shop' => array(
							'title' => esc_html__( 'Products animation on the shop page', 'helion' ),
							'desc' => wp_kses_data( __( 'Select animation to show products on the shop page. Attention! Do not use any animation on pages with the "wheel to the anchor" behaviour!', 'helion' ) ),
							'std' => 'none',
							'options' => array(),
							'type' => 'select',
						),
						'shop_hover'              => array(
							'title'   => esc_html__( 'Hover style', 'helion' ),
							'desc'    => wp_kses_data( __( 'Hover style on the products in the shop archive', 'helion' ) ),
							'std'     => 'shop',
							'options' => apply_filters(
								'helion_filter_shop_hover', array(
									'none'         => esc_html__( 'None', 'helion' ),
									'shop'         => esc_html__( 'Icons', 'helion' ),
									'shop_buttons' => esc_html__( 'Buttons', 'helion' ),
								)
							),
							'qsetup'  => esc_html__( 'General', 'helion' ),
							'type'    => 'select',
						),
						'shop_pagination'         => array(
							'title'   => esc_html__( 'Pagination style', 'helion' ),
							'desc'    => wp_kses_data( __( 'Pagination style in the shop archive', 'helion' ) ),
							'std'     => 'numbers',
							'options' => apply_filters(
								'helion_filter_shop_pagination', array(
									'pages'    => esc_html__( 'Page numbers', 'helion' ),
									'more'     => esc_html__( 'Load more', 'helion' ),
									'infinite' => esc_html__( 'Infinite scroll', 'helion' ),
								)
							),
							'qsetup'  => esc_html__( 'General', 'helion' ),
							'type'    => 'select',
						),

						'single_info_shop'        => array(
							'title' => esc_html__( 'Single product', 'helion' ),
							'desc'  => '',
							'type'  => 'info',
						),
						'single_product_layout'      => array(
							'title'      => esc_html__( 'Layout of the single product', 'helion' ),
							'desc'       => wp_kses_data( __( 'Select layout of the single products page', 'helion' ) ),
							'std'        => 'default',
							'override' => array(
								'mode'    => 'product',
								'section' => esc_html__( 'Content', 'helion' ),
							),
							'options'    => apply_filters(
															'helion_filter_woocommerce_single_product_layouts',
															array(
																'default'   => esc_html__( 'Default', 'helion' ),
																'stretched' => esc_html__( 'Stretched', 'helion' ),
															)
														),
							'type'       => 'hidden',
						),
						'show_related_posts_shop' => array(
							'title'  => esc_html__( 'Show related products', 'helion' ),
							'desc'   => wp_kses_data( __( "Show section 'Related products' on the single product page", 'helion' ) ),
							'std'    => 1,
							'type'   => 'switch',
						),
						'related_posts_shop'      => array(
							'title'      => esc_html__( 'Related products', 'helion' ),
							'desc'       => wp_kses_data( __( 'How many related products should be displayed on the single product page?', 'helion' ) ),
							'dependency' => array(
								'show_related_posts_shop' => array( 1 ),
							),
							'std'        => 3,
							'options'    => helion_get_list_range( 1, 9 ),
							'type'       => 'select',
						),
						'related_columns_shop'    => array(
							'title'      => esc_html__( 'Related columns', 'helion' ),
							'desc'       => wp_kses_data( __( 'How many columns should be used to output related products on the single product page?', 'helion' ) ),
							'dependency' => array(
								'show_related_posts_shop' => array( 1 ),
							),
							'std'        => 3,
							'options'    => helion_get_list_range( 1, 4 ),
							'type'       => 'select',
						),
					),
					helion_options_get_list_cpt_options( 'shop' )
				)
			);
			// Set 'WooCommerce Sidebar' as default for all shop pages
			helion_storage_set_array2( 'options', 'sidebar_widgets_shop', 'std', 'woocommerce_widgets' );
		}
	}
}


// Move section 'Shop' inside the section 'WooCommerce' in the Customizer (if WooCommerce 3.3+ is installed)
if ( ! function_exists( 'helion_woocommerce_customizer_register_controls' ) ) {
	add_action( 'customize_register', 'helion_woocommerce_customizer_register_controls', 100 );
	function helion_woocommerce_customizer_register_controls( $wp_customize ) {
		if ( helion_exists_woocommerce() ) {
			$panel = $wp_customize->get_panel( 'woocommerce' );
			$sec   = $wp_customize->get_section( 'shop' );
			if ( is_object( $panel ) && is_object( $sec ) ) {
				$sec->panel    = 'woocommerce';
				$sec->title    = esc_html__( 'Theme-specific options', 'helion' );
				$sec->priority = 100;
			}
		}
	}
}


// Set theme-specific default columns number in the new WooCommerce after switch theme
if ( ! function_exists( 'helion_woocommerce_action_switch_theme' ) ) {
	add_action( 'after_switch_theme', 'helion_woocommerce_action_switch_theme' );
	function helion_woocommerce_action_switch_theme() {
		if ( helion_exists_woocommerce() ) {
			update_option( 'woocommerce_catalog_columns', apply_filters( 'helion_filter_woocommerce_columns', 3 ) );
		}
	}
}

// Set theme-specific default columns number in the new WooCommerce after plugin activation
if ( ! function_exists( 'helion_woocommerce_action_activated_plugin' ) ) {
	add_action( 'activated_plugin', 'helion_woocommerce_action_activated_plugin', 10, 2 );
	function helion_woocommerce_action_activated_plugin( $plugin, $network_activation ) {
		if ( 'woocommerce/woocommerce.php' == $plugin ) {
			update_option( 'woocommerce_catalog_columns', apply_filters( 'helion_filter_woocommerce_columns', 3 ) );
		}
	}
}



// Check if meta box is allowed
if ( ! function_exists( 'helion_woocommerce_allow_override_options' ) ) {
	if ( ! HELION_THEME_FREE ) {
		add_filter( 'helion_filter_allow_override_options', 'helion_woocommerce_allow_override_options', 10, 2);
	}
	function helion_woocommerce_allow_override_options( $allow, $post_type ) {
		return $allow || 'product' == $post_type;
	}
}


// Add section 'Products' to the Front Page option
if ( ! function_exists( 'helion_woocommerce_front_page_options' ) ) {
	if ( ! HELION_THEME_FREE ) {
		add_filter( 'helion_filter_front_page_options', 'helion_woocommerce_front_page_options' );
	}
	function helion_woocommerce_front_page_options( $options ) {
		if ( helion_exists_woocommerce() ) {

			$options['front_page_sections']['std']    .= ( ! empty( $options['front_page_sections']['std'] ) ? '|' : '' ) . 'woocommerce=1';
			$options['front_page_sections']['options'] = array_merge(
				$options['front_page_sections']['options'],
				array(
					'woocommerce' => esc_html__( 'Products', 'helion' ),
				)
			);
			$options                                   = array_merge(
				$options, array(

					// Front Page Sections - WooCommerce
					'front_page_woocommerce'               => array(
						'title'    => esc_html__( 'Products', 'helion' ),
						'desc'     => '',
						'priority' => 200,
						'type'     => 'section',
					),
					'front_page_woocommerce_layout_info'   => array(
						'title' => esc_html__( 'Layout', 'helion' ),
						'desc'  => '',
						'type'  => 'info',
					),
					'front_page_woocommerce_fullheight'    => array(
						'title'   => esc_html__( 'Full height', 'helion' ),
						'desc'    => wp_kses_data( __( 'Stretch this section to the window height', 'helion' ) ),
						'std'     => 0,
						'refresh' => false,
						'type'    => 'switch',
					),
					'front_page_woocommerce_stack'         => array(
						'title'      => esc_html__( 'Stack this section', 'helion' ),
						'desc'       => wp_kses_data( __( 'Add the behavior of "a stack" for this section to fix it when you scroll to the top of the screen.', 'helion' ) ),
						'std'        => 0,
						'refresh'    => false,
						'dependency' => array(
							'front_page_woocommerce_fullheight' => array( 1 ),
						),
						'type'       => 'switch',
					),
					'front_page_woocommerce_paddings'      => array(
						'title'   => esc_html__( 'Paddings', 'helion' ),
						'desc'    => wp_kses_data( __( 'Select paddings inside this section', 'helion' ) ),
						'std'     => 'medium',
						'options' => helion_get_list_paddings(),
						'refresh' => false,
						'type'    => 'radio',
					),
					'front_page_woocommerce_heading_info'  => array(
						'title' => esc_html__( 'Title', 'helion' ),
						'desc'  => '',
						'type'  => 'info',
					),
					'front_page_woocommerce_caption'       => array(
						'title'   => esc_html__( 'Section title', 'helion' ),
						'desc'    => '',
						'refresh' => false,
						'std'     => wp_kses_data( __( 'This text can be changed in the section "Products"', 'helion' ) ),
						'type'    => 'text',
					),
					'front_page_woocommerce_description'   => array(
						'title'   => esc_html__( 'Description', 'helion' ),
						'desc'    => wp_kses_data( __( "Short description after the section's title", 'helion' ) ),
						'refresh' => false,
						'std'     => wp_kses_data( __( 'This text can be changed in the section "Products"', 'helion' ) ),
						'type'    => 'textarea',
					),
					'front_page_woocommerce_products_info' => array(
						'title' => esc_html__( 'Products parameters', 'helion' ),
						'desc'  => '',
						'type'  => 'info',
					),
					'front_page_woocommerce_products'      => array(
						'title'   => esc_html__( 'Type of the products', 'helion' ),
						'desc'    => '',
						'std'     => 'products',
						'options' => array(
							'recent_products'       => esc_html__( 'Recent products', 'helion' ),
							'featured_products'     => esc_html__( 'Featured products', 'helion' ),
							'top_rated_products'    => esc_html__( 'Top rated products', 'helion' ),
							'sale_products'         => esc_html__( 'Sale products', 'helion' ),
							'best_selling_products' => esc_html__( 'Best selling products', 'helion' ),
							'product_category'      => esc_html__( 'Products from categories', 'helion' ),
							'products'              => esc_html__( 'Products by IDs', 'helion' ),
						),
						'type'    => 'select',
					),
					'front_page_woocommerce_products_categories' => array(
						'title'      => esc_html__( 'Categories', 'helion' ),
						'desc'       => esc_html__( 'Comma separated category slugs. Used only with "Products from categories"', 'helion' ),
						'dependency' => array(
							'front_page_woocommerce_products' => array( 'product_category' ),
						),
						'std'        => '',
						'type'       => 'text',
					),
					'front_page_woocommerce_products_per_page' => array(
						'title' => esc_html__( 'Per page', 'helion' ),
						'desc'  => wp_kses_data( __( 'How many products will be displayed on the page. Attention! For "Products by IDs" specify comma separated list of the IDs', 'helion' ) ),
						'std'   => 3,
						'type'  => 'text',
					),
					'front_page_woocommerce_products_columns' => array(
						'title' => esc_html__( 'Columns', 'helion' ),
						'desc'  => wp_kses_data( __( 'How many columns will be used', 'helion' ) ),
						'std'   => 3,
						'type'  => 'text',
					),
					'front_page_woocommerce_products_orderby' => array(
						'title'   => esc_html__( 'Order by', 'helion' ),
						'desc'    => wp_kses_data( __( 'Not used with Best selling products', 'helion' ) ),
						'std'     => 'date',
						'options' => array(
							'date'  => esc_html__( 'Date', 'helion' ),
							'title' => esc_html__( 'Title', 'helion' ),
						),
						'type'    => 'radio',
					),
					'front_page_woocommerce_products_order' => array(
						'title'   => esc_html__( 'Order', 'helion' ),
						'desc'    => wp_kses_data( __( 'Not used with Best selling products', 'helion' ) ),
						'std'     => 'desc',
						'options' => array(
							'asc'  => esc_html__( 'Ascending', 'helion' ),
							'desc' => esc_html__( 'Descending', 'helion' ),
						),
						'type'    => 'radio',
					),
					'front_page_woocommerce_color_info'    => array(
						'title' => esc_html__( 'Colors and images', 'helion' ),
						'desc'  => '',
						'type'  => 'info',
					),
					'front_page_woocommerce_scheme'        => array(
						'title'   => esc_html__( 'Color scheme', 'helion' ),
						'desc'    => wp_kses_data( __( 'Color scheme for this section', 'helion' ) ),
						'std'     => 'inherit',
						'options' => array(),
						'refresh' => false,
						'type'    => 'radio',
					),
					'front_page_woocommerce_bg_image'      => array(
						'title'           => esc_html__( 'Background image', 'helion' ),
						'desc'            => wp_kses_data( __( 'Select or upload background image for this section', 'helion' ) ),
						'refresh'         => '.front_page_section_woocommerce',
						'refresh_wrapper' => true,
						'std'             => '',
						'type'            => 'image',
					),
					'front_page_woocommerce_bg_color_type'  => array(
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
					'front_page_woocommerce_bg_color'       => array(
						'title'      => esc_html__( 'Custom color', 'helion' ),
						'desc'       => wp_kses_data( __( 'Custom background color for this section', 'helion' ) ),
						'std'        => HELION_THEME_FREE ? '#000' : '',
						'refresh'    => false,
						'dependency' => array(
							'front_page_woocommerce_bg_color_type' => array( 'custom' ),
						),
						'type'       => 'color',
					),
					'front_page_woocommerce_bg_mask'       => array(
						'title'   => esc_html__( 'Background mask', 'helion' ),
						'desc'    => wp_kses_data( __( 'Use Background color as section mask with specified opacity. If 0 - mask is not used', 'helion' ) ),
						'std'     => 1,
						'max'     => 1,
						'step'    => 0.1,
						'refresh' => false,
						'type'    => 'slider',
					),
					'front_page_woocommerce_anchor_info'   => array(
						'title' => esc_html__( 'Anchor', 'helion' ),
						'desc'  => wp_kses_data( __( 'You can select icon and/or specify a text to create anchor for this section and show it in the side menu (if selected in the section "Header - Menu".', 'helion' ) )
									. '<br>'
									. wp_kses_data( __( 'Attention! Anchors available only if plugin "ThemeREX Addons is installed and activated!', 'helion' ) ),
						'type'  => 'info',
					),
					'front_page_woocommerce_anchor_icon'   => array(
						'title' => esc_html__( 'Anchor icon', 'helion' ),
						'desc'  => '',
						'std'   => '',
						'type'  => 'icon',
					),
					'front_page_woocommerce_anchor_text'   => array(
						'title' => esc_html__( 'Anchor text', 'helion' ),
						'desc'  => '',
						'std'   => '',
						'type'  => 'text',
					),
				)
			);
		}
		return $options;
	}
}

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'helion_woocommerce_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'helion_woocommerce_theme_setup9', 9 );
	function helion_woocommerce_theme_setup9() {
		if ( helion_exists_woocommerce() ) {
			add_action( 'wp_enqueue_scripts', 'helion_woocommerce_frontend_scripts', 1100 );
			add_action( 'wp_enqueue_scripts', 'helion_woocommerce_responsive_styles', 2000 );
			add_filter( 'helion_filter_merge_styles', 'helion_woocommerce_merge_styles' );
			add_filter( 'helion_filter_merge_styles_responsive', 'helion_woocommerce_merge_styles_responsive' );
			add_filter( 'helion_filter_merge_scripts', 'helion_woocommerce_merge_scripts' );
			add_filter( 'helion_filter_get_post_info', 'helion_woocommerce_get_post_info' );
			add_filter( 'helion_filter_post_type_taxonomy', 'helion_woocommerce_post_type_taxonomy', 10, 2 );
			add_action( 'helion_action_override_theme_options', 'helion_woocommerce_override_theme_options' );
			add_filter( 'helion_filter_detect_blog_mode', 'helion_woocommerce_detect_blog_mode' );
			add_filter( 'helion_filter_get_post_categories', 'helion_woocommerce_get_post_categories' );
			add_filter( 'helion_filter_allow_override_header_image', 'helion_woocommerce_allow_override_header_image' );
			add_filter( 'helion_filter_get_blog_title', 'helion_woocommerce_get_blog_title' );
			add_action( 'helion_action_before_post_meta', 'helion_woocommerce_action_before_post_meta' );
			if ( ! is_admin() ) {
				add_action( 'pre_get_posts', 'helion_woocommerce_pre_get_posts' );
				add_filter( 'helion_filter_sidebar_control_text', 'helion_woocommerce_sidebar_control_text' );
				add_filter( 'helion_filter_sidebar_control_title', 'helion_woocommerce_sidebar_control_title' );
			}
		}
		if ( is_admin() ) {
			add_filter( 'helion_filter_tgmpa_required_plugins', 'helion_woocommerce_tgmpa_required_plugins' );
		}

		// Add wrappers and classes to the standard WooCommerce output
		if ( helion_exists_woocommerce() ) {

			// Remove WOOC sidebar
			remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

			// Remove link around product item
			remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

			// Add Wishlist button
			if ( get_option('yith_woocompare_compare_button_in_products_list') == 'yes' ) {
				add_action( 'woocommerce_after_shop_loop_item', 'helion_woocommerce_add_wishlist_wrap', 19 );
				add_action( 'woocommerce_after_shop_loop_item', 'helion_woocommerce_add_wishlist_link', 20 );
			}
			// Remove link around product category
			remove_action( 'woocommerce_before_subcategory', 'woocommerce_template_loop_category_link_open', 10 );
			remove_action( 'woocommerce_after_subcategory', 'woocommerce_template_loop_category_link_close', 10 );

			// Detect we are inside subcategory
			add_action( 'woocommerce_before_subcategory', 'helion_woocommerce_before_subcategory', 1 );
			add_action( 'woocommerce_after_subcategory', 'helion_woocommerce_after_subcategory', 9999 );

			// Open main content wrapper - <article>
			remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
			add_action( 'woocommerce_before_main_content', 'helion_woocommerce_wrapper_start', 10 );
			// Close main content wrapper - </article>
			remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
			add_action( 'woocommerce_after_main_content', 'helion_woocommerce_wrapper_end', 10 );

			// Close header section
			add_action( 'woocommerce_after_main_content', 'helion_woocommerce_archive_description', 1 );
			add_action( 'woocommerce_before_shop_loop', 'helion_woocommerce_archive_description', 5 );
			add_action( 'woocommerce_no_products_found', 'helion_woocommerce_archive_description', 5 );

			// Add theme specific search form
			add_filter( 'get_product_search_form', 'helion_woocommerce_get_product_search_form' );

			// Change text on 'Add to cart' button
			add_filter( 'woocommerce_product_add_to_cart_text', 'helion_woocommerce_add_to_cart_text' );
			add_filter( 'woocommerce_product_single_add_to_cart_text', 'helion_woocommerce_add_to_cart_text' );

			// Wrap 'Add to cart' button
			add_filter( 'woocommerce_loop_add_to_cart_link', 'helion_woocommerce_add_to_cart_link', 10, 3 );

			// Add list mode buttons
			add_action( 'woocommerce_before_shop_loop', 'helion_woocommerce_before_shop_loop', 10 );

			// Show 'No products' if no search results and display mode 'both'
			add_action( 'woocommerce_after_shop_loop', 'helion_woocommerce_after_shop_loop', 1 );

			// Set columns number for the products loop
			if ( ! get_theme_support( 'wc-product-grid-enable' ) ) {
				add_filter( 'loop_shop_columns', 'helion_woocommerce_loop_shop_columns' );
				add_filter( 'post_class', 'helion_woocommerce_loop_shop_columns_class' );
				add_filter( 'product_cat_class', 'helion_woocommerce_loop_shop_columns_class', 10, 3 );
			}
			// Open product/category item wrapper
			add_action( 'woocommerce_before_subcategory_title', 'helion_woocommerce_item_wrapper_start', 9 );
			add_action( 'woocommerce_before_shop_loop_item_title', 'helion_woocommerce_item_wrapper_start', 9 );
			// Close featured image wrapper and open title wrapper
			add_action( 'woocommerce_before_subcategory_title', 'helion_woocommerce_title_wrapper_start', 20 );
			add_action( 'woocommerce_before_shop_loop_item_title', 'helion_woocommerce_title_wrapper_start', 20 );

			// Add tags before title
			add_action( 'woocommerce_before_shop_loop_item_title', 'helion_woocommerce_title_tags', 30 );

			// Wrap product title to the link
			add_filter( 'the_title', 'helion_woocommerce_the_title' );
			// Wrap category title to the link
			// Old way: before WooCommerce 3.2.2
			// New way: WooCommerce 3.2.2+
			add_action( 'woocommerce_before_subcategory_title', 'helion_woocommerce_before_subcategory_title', 22, 1 );
			add_action( 'woocommerce_after_subcategory_title', 'helion_woocommerce_after_subcategory_title', 2, 1 );

			// Close title wrapper and add description in the list mode
			add_action( 'woocommerce_after_shop_loop_item_title', 'helion_woocommerce_title_wrapper_end', 7 );
			add_action( 'woocommerce_after_subcategory_title', 'helion_woocommerce_title_wrapper_end2', 10 );
			// Close product/category item wrapper
			add_action( 'woocommerce_after_subcategory', 'helion_woocommerce_item_wrapper_end', 20 );
			add_action( 'woocommerce_after_shop_loop_item', 'helion_woocommerce_item_wrapper_end', 20 );

			// Add product ID into product meta section (after categories and tags)
			add_action( 'woocommerce_product_meta_end', 'helion_woocommerce_show_product_id', 10 );

			// Set columns number for the product's thumbnails
			add_filter( 'woocommerce_product_thumbnails_columns', 'helion_woocommerce_product_thumbnails_columns' );

			// Wrap price (WooCommerce use priority 10 to output price)
			add_action( 'woocommerce_after_shop_loop_item_title', 'helion_woocommerce_price_wrapper_start', 9 );
			add_action( 'woocommerce_after_shop_loop_item_title', 'helion_woocommerce_price_wrapper_end', 11 );

			// Add 'Out of stock' label
			add_action( 'helion_action_woocommerce_item_featured_link_start', 'helion_woocommerce_add_out_of_stock_label' );

			// Decorate 'Sale' label
			add_filter( 'woocommerce_sale_flash', 'helion_woocommerce_add_sale_percent', 10, 3 );

			// Add custom paginations to the loop
			add_action( 'woocommerce_after_shop_loop', 'helion_woocommerce_pagination', 9 );

			// Add WooCommerce-specific classes
			add_filter( 'body_class', 'helion_woocommerce_add_body_classes' );

			// Detect current shop mode
			if ( ! is_admin() ) {
				$shop_mode = helion_get_value_gp( 'shop_mode' );
				if ( empty( $shop_mode ) ) {
					$shop_mode = helion_get_value_gpc( 'helion_shop_mode' );
				}
				if ( empty( $shop_mode ) && helion_check_theme_option( 'shop_mode' ) ) {
					$shop_mode = helion_get_theme_option( 'shop_mode' );
				}
				if ( empty( $shop_mode ) ) {
					$shop_mode = 'thumbs';
				}
				helion_storage_set( 'shop_mode', $shop_mode );
			}
		}
	}
}

// Theme init priorities:
// Action 'wp'
// 1 - detect override mode. Attention! Only after this step you can use overriden options (separate values for the shop, courses, etc.)
if ( ! function_exists( 'helion_woocommerce_theme_setup_wp' ) ) {
	add_action( 'wp', 'helion_woocommerce_theme_setup_wp' );
	function helion_woocommerce_theme_setup_wp() {
		if ( helion_exists_woocommerce() ) {
			// Set columns number for the related products
			if ( (int) helion_get_theme_option( 'show_related_posts' ) == 0 || (int) helion_get_theme_option( 'related_posts' ) == 0 ) {
				remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
			} else {
				add_filter( 'woocommerce_output_related_products_args', 'helion_woocommerce_output_related_products_args' );
				add_filter( 'woocommerce_related_products_columns', 'helion_woocommerce_related_products_columns' );
				add_filter( 'woocommerce_cross_sells_columns', 'helion_woocommerce_related_products_columns' );
				add_filter( 'woocommerce_upsells_columns', 'helion_woocommerce_related_products_columns' );
			}
			// Decorate breadcrumbs
			add_filter( 'woocommerce_breadcrumb_defaults', 'helion_woocommerce_breadcrumb_defaults' );
			if ( is_product() && helion_get_theme_option( 'single_product_layout' ) == 'stretched' ) {
				remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
				add_action( 'woocommerce_single_product_summary', 'woocommerce_breadcrumb', 3 );
			}
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'helion_woocommerce_tgmpa_required_plugins' ) ) {
	
	function helion_woocommerce_tgmpa_required_plugins( $list = array() ) {
		if ( helion_storage_isset( 'required_plugins', 'woocommerce' ) && helion_storage_get_array( 'required_plugins', 'woocommerce', 'install' ) !== false ) {
			$list[] = array(
				'name'     => helion_storage_get_array( 'required_plugins', 'woocommerce', 'title' ),
				'slug'     => 'woocommerce',
				'required' => false,
			);
		}
		return $list;
	}
}


// Check if WooCommerce installed and activated
if ( ! function_exists( 'helion_exists_woocommerce' ) ) {
	function helion_exists_woocommerce() {
		return class_exists( 'Woocommerce' );
	}
}

// Return true, if current page is any woocommerce page
if ( ! function_exists( 'helion_is_woocommerce_page' ) ) {
	function helion_is_woocommerce_page() {
		$rez = false;
		if ( helion_exists_woocommerce() ) {
			$rez = is_woocommerce() || is_shop() || is_product() || is_product_category() || is_product_tag() || is_product_taxonomy() || is_cart() || is_checkout() || is_account_page();
		}
		return $rez;
	}
}

// Detect current blog mode
if ( ! function_exists( 'helion_woocommerce_detect_blog_mode' ) ) {
	
	function helion_woocommerce_detect_blog_mode( $mode = '' ) {
		if ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) {
			$mode = 'shop';
		} elseif ( is_product() || is_cart() || is_checkout() || is_account_page() ) {
			$mode = 'shop'; 
		}
		return $mode;
	}
}

// Override options with stored page meta on 'Shop' pages
if ( ! function_exists( 'helion_woocommerce_override_theme_options' ) ) {
	
	function helion_woocommerce_override_theme_options() {
		// Remove ' || is_product()' from the condition in the next row
		// if you don't need to override theme options from the page 'Shop' on single products
		if ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() || is_product() ) {
			$id = helion_woocommerce_get_shop_page_id();
			if ( 0 < $id ) {
				// Get Theme Options from the shop page
				$shop_meta = get_post_meta( $id, 'helion_options', true );
				if ( ! is_array( $shop_meta ) ) {
					$shop_meta = array();
				}
				// Add (override) with current post (product) options
				if ( helion_storage_isset( 'options_meta' ) ) {
					$options_meta = helion_storage_get( 'options_meta' );
					if ( is_array( $options_meta ) ) {
						$shop_meta = array_merge( $shop_meta, $options_meta );
					}
				}
				helion_storage_set( 'options_meta', $shop_meta );
			}
		}
	}
}

// Add WooCommerce-specific classes to the body
if ( ! function_exists( 'helion_woocommerce_add_body_classes' ) ) {
	
	function helion_woocommerce_add_body_classes( $classes ) {
		if ( is_product() ) {
			$classes[] = 'single_product_layout_' . helion_get_theme_option( 'single_product_layout' );
		}
		return $classes;
	}
}


// Return current page title
if ( ! function_exists( 'helion_woocommerce_get_blog_title' ) ) {
	
	function helion_woocommerce_get_blog_title( $title = '' ) {
		if ( ! helion_exists_trx_addons() && helion_exists_woocommerce() && helion_is_woocommerce_page() && is_shop() ) {
			$id    = helion_woocommerce_get_shop_page_id();
			$title = $id ? get_the_title( $id ) : esc_html__( 'Shop', 'helion' );
		}
		return $title;
	}
}


// Return taxonomy for current post type
if ( ! function_exists( 'helion_woocommerce_post_type_taxonomy' ) ) {
	
	function helion_woocommerce_post_type_taxonomy( $tax = '', $post_type = '' ) {
		if ( 'product' == $post_type ) {
			$tax = 'product_cat';
		}
		return $tax;
	}
}

// Return true if page title section is allowed
if ( ! function_exists( 'helion_woocommerce_allow_override_header_image' ) ) {
	
	function helion_woocommerce_allow_override_header_image( $allow = true ) {
		return is_product() ? false : $allow;
	}
}

// Return shop page ID
if ( ! function_exists( 'helion_woocommerce_get_shop_page_id' ) ) {
	function helion_woocommerce_get_shop_page_id() {
		return get_option( 'woocommerce_shop_page_id' );
	}
}

// Return shop page link
if ( ! function_exists( 'helion_woocommerce_get_shop_page_link' ) ) {
	function helion_woocommerce_get_shop_page_link() {
		$url = '';
		$id  = helion_woocommerce_get_shop_page_id();
		if ( $id ) {
			$url = get_permalink( $id );
		}
		return $url;
	}
}

// Show categories of the current product
if ( ! function_exists( 'helion_woocommerce_get_post_categories' ) ) {
	
	function helion_woocommerce_get_post_categories( $cats = '' ) {
		if ( get_post_type() == 'product' ) {
			$cats = helion_get_post_terms( ', ', get_the_ID(), 'product_cat' );
		}
		return $cats;
	}
}

// Add 'product' to the list of the supported post-types
if ( ! function_exists( 'helion_woocommerce_list_post_types' ) ) {
	
	function helion_woocommerce_list_post_types( $list = array() ) {
		$list['product'] = esc_html__( 'Products', 'helion' );
		return $list;
	}
}

// Show price of the current product in the widgets and search results
if ( ! function_exists( 'helion_woocommerce_get_post_info' ) ) {
	
	function helion_woocommerce_get_post_info( $post_info = '' ) {
		if ( get_post_type() == 'product' ) {
			global $product;
			$price_html = $product->get_price_html();
			if ( ! empty( $price_html ) ) {
				$post_info = '<div class="post_price product_price price">' . trim( $price_html ) . '</div>' . $post_info;
			}
		}
		return $post_info;
	}
}

// Show price of the current product in the search results streampage
if ( ! function_exists( 'helion_woocommerce_action_before_post_meta' ) ) {
	
	function helion_woocommerce_action_before_post_meta() {
		if ( ! is_single() && get_post_type() == 'product' ) {
			global $product;
			$price_html = $product->get_price_html();
			if ( ! empty( $price_html ) ) {
				?><div class="post_price product_price price"><?php helion_show_layout( $price_html ); ?></div>
				<?php
			}
		}
	}
}

// Change text of the sidebar control
if ( ! function_exists( 'helion_woocommerce_sidebar_control_text' ) ) {
	
	function helion_woocommerce_sidebar_control_text( $text ) {
		if ( ! empty( $text ) && helion_exists_woocommerce() && helion_is_woocommerce_page() ) {
			$text = esc_html__( 'Filters', 'helion' );
		}
		return $text;
	}
}

// Change title of the sidebar control
if ( ! function_exists( 'helion_woocommerce_sidebar_control_title' ) ) {
	
	function helion_woocommerce_sidebar_control_title( $title ) {
		if ( ! empty( $title ) && helion_exists_woocommerce() && helion_is_woocommerce_page() ) {
			$title = esc_html__( 'Filters', 'helion' );
		}
		return $title;
	}
}

// Enqueue WooCommerce custom styles
if ( ! function_exists( 'helion_woocommerce_frontend_scripts' ) ) {
	
	function helion_woocommerce_frontend_scripts() {
		if ( helion_is_on( helion_get_theme_option( 'debug_mode' ) ) ) {
			$helion_url = helion_get_file_url( 'plugins/woocommerce/woocommerce.css' );
			if ( '' != $helion_url ) {
				wp_enqueue_style( 'helion-woocommerce', $helion_url, array(), null );
			}
			$helion_url = helion_get_file_url( 'plugins/woocommerce/woocommerce.js' );
			if ( '' != $helion_url ) {
				wp_enqueue_script( 'helion-woocommerce', $helion_url, array( 'jquery' ), null, true );
			}
		}
	}
}

// Enqueue WooCommerce responsive styles
if ( ! function_exists( 'helion_woocommerce_responsive_styles' ) ) {
	
	function helion_woocommerce_responsive_styles() {
		if ( helion_is_on( helion_get_theme_option( 'debug_mode' ) ) ) {
			$helion_url = helion_get_file_url( 'plugins/woocommerce/woocommerce-responsive.css' );
			if ( '' != $helion_url ) {
				wp_enqueue_style( 'helion-woocommerce-responsive', $helion_url, array(), null );
			}
		}
	}
}

// Merge custom styles
if ( ! function_exists( 'helion_woocommerce_merge_styles' ) ) {
	
	function helion_woocommerce_merge_styles( $list ) {
		$list[] = 'plugins/woocommerce/woocommerce.css';
		return $list;
	}
}


// Merge responsive styles
if ( ! function_exists( 'helion_woocommerce_merge_styles_responsive' ) ) {
	
	function helion_woocommerce_merge_styles_responsive( $list ) {
		$list[] = 'plugins/woocommerce/woocommerce-responsive.css';
		return $list;
	}
}

// Merge custom scripts
if ( ! function_exists( 'helion_woocommerce_merge_scripts' ) ) {
	
	function helion_woocommerce_merge_scripts( $list ) {
		$list[] = 'plugins/woocommerce/woocommerce.js';
		return $list;
	}
}



// Add WooCommerce specific items into lists
//------------------------------------------------------------------------

// Add sidebar
if ( ! function_exists( 'helion_woocommerce_list_sidebars' ) ) {
	
	function helion_woocommerce_list_sidebars( $list = array() ) {
		$list['woocommerce_widgets'] = array(
			'name'        => esc_html__( 'WooCommerce Widgets', 'helion' ),
			'description' => esc_html__( 'Widgets to be shown on the WooCommerce pages', 'helion' ),
		);
		return $list;
	}
}




// Decorate WooCommerce output: Loop
//------------------------------------------------------------------------

// Add query vars to set products per page
if ( ! function_exists( 'helion_woocommerce_pre_get_posts' ) ) {
	
	function helion_woocommerce_pre_get_posts( $query ) {
		if ( ! $query->is_main_query() ) {
			return;
		}
		if ( $query->get( 'wc_query' ) == 'product_query' ) {
			$ppp = get_theme_mod( 'posts_per_page_shop', 0 );
			if ( $ppp > 0 ) {
				$query->set( 'posts_per_page', $ppp );
			}
		}
	}
}


// Add custom paginations to the loop
if ( ! function_exists( 'helion_woocommerce_pagination' ) ) {
	
	function helion_woocommerce_pagination() {
		$pagination = helion_get_theme_option('shop_pagination');
		if ( in_array( $pagination, array( 'more', 'infinite' ) ) ) {
			helion_show_pagination(
				array(
					'pagination'   => $pagination,
					'class_prefix' => 'woocommerce'
				)
			);
		}
	}
}


// Before main content
if ( ! function_exists( 'helion_woocommerce_wrapper_start' ) ) {
	function helion_woocommerce_wrapper_start() {
		if ( is_product() || is_cart() || is_checkout() || is_account_page() ) {
			?>
			<article class="post_item_single post_type_product">
			<?php
		} else {
			?>
			<div class="list_products shop_mode_<?php echo esc_attr( ! helion_storage_empty( 'shop_mode' ) ? helion_storage_get( 'shop_mode' ) : 'thumbs' ); ?>">
				<div class="list_products_header">
			<?php
			helion_storage_set( 'woocommerce_list_products_header', true );
		}
	}
}

// After main content
if ( ! function_exists( 'helion_woocommerce_wrapper_end' ) ) {
	function helion_woocommerce_wrapper_end() {
		if ( is_product() || is_cart() || is_checkout() || is_account_page() ) {
			?>
			</article><!-- /.post_item_single -->
			<?php
		} else {
			?>
			</div><!-- /.list_products -->
			<?php
		}
	}
}

// Close header section
if ( ! function_exists( 'helion_woocommerce_archive_description' ) ) {
	
	
	
	function helion_woocommerce_archive_description() {
		if ( helion_storage_get( 'woocommerce_list_products_header' ) ) {
			?>
			</div><!-- /.list_products_header -->
			<?php
			helion_storage_set( 'woocommerce_list_products_header', false );
			remove_action( 'woocommerce_after_main_content', 'helion_woocommerce_archive_description', 1 );
		} elseif ( ! is_singular() ) {
			get_template_part( apply_filters( 'helion_filter_get_template_part', 'content', 'none-search' ), 'none-search' );
		}
	}
}

// Add list mode buttons
if ( ! function_exists( 'helion_woocommerce_before_shop_loop' ) ) {
	
	function helion_woocommerce_before_shop_loop() {
		?>
		<div class="helion_shop_mode_buttons"><form action="<?php echo esc_url( helion_get_current_url() ); ?>" method="post"><input type="hidden" name="helion_shop_mode" value="<?php echo esc_attr( helion_storage_get( 'shop_mode' ) ); ?>" /><a href="#" class="woocommerce_thumbs icon-th" title="<?php esc_attr_e( 'Show products as thumbs', 'helion' ); ?>"></a><a href="#" class="woocommerce_list icon-th-list" title="<?php esc_attr_e( 'Show products as list', 'helion' ); ?>"></a></form></div><!-- /.helion_shop_mode_buttons -->
		<?php
	}
}

// Show 'No products' if no search results and display mode 'both'
if ( ! function_exists( 'helion_woocommerce_after_shop_loop' ) ) {
	
	function helion_woocommerce_after_shop_loop() {
		if ( ! have_posts() && 'products' != woocommerce_get_loop_display_mode() ) {
			wc_get_template( 'loop/no-products-found.php' );
		}
	}
}

// Number of columns for the shop streampage
if ( ! function_exists( 'helion_woocommerce_loop_shop_columns' ) ) {
	
	function helion_woocommerce_loop_shop_columns( $cols ) {
		return max( 2, min( 4, helion_get_theme_option( 'blog_columns' ) ) );
	}
}

// Add column class into product item in shop streampage
if ( ! function_exists( 'helion_woocommerce_loop_shop_columns_class' ) ) {
	
	
	function helion_woocommerce_loop_shop_columns_class( $classes, $class = '', $cat = '' ) {
		global $woocommerce_loop;
		if ( is_product() ) {
			if ( ! empty( $woocommerce_loop['columns'] ) ) {
				$classes[] = ' column-1_' . esc_attr( $woocommerce_loop['columns'] );
			}
		} elseif ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) {
			$classes[] = ' column-1_' . esc_attr( max( 2, min( 4, helion_get_theme_option( 'blog_columns' ) ) ) );
		}
		return $classes;
	}
}

// Detect when we are in a subcategory: start category
if ( ! function_exists( 'helion_woocommerce_before_subcategory' ) ) {
	
	function helion_woocommerce_before_subcategory( $cat = '' ) {
		helion_storage_set( 'in_product_category', $cat );
	}
}

// Detect when we are in a subcategory: end category
if ( ! function_exists( 'helion_woocommerce_after_subcategory' ) ) {
	
	function helion_woocommerce_after_subcategory( $cat = '' ) {
		helion_storage_set( 'in_product_category', false );
	}
}
	

// Open item wrapper for categories and products
if ( ! function_exists( 'helion_woocommerce_item_wrapper_start' ) ) {
	
	
	function helion_woocommerce_item_wrapper_start( $cat = '' ) {
		helion_storage_set( 'in_product_item', true );
		$hover = helion_get_theme_option( 'shop_hover' );
		?>
		<div class="post_item post_layout_<?php
			echo esc_attr( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy()
				? helion_storage_get( 'shop_mode' )
				: 'thumbs'
			);
			?>"
			<?php helion_add_blog_animation(); ?>
		>
			<div class="post_featured hover_<?php echo esc_attr( $hover ); ?>">
				<?php do_action( 'helion_action_woocommerce_item_featured_start' ); ?>
				<a href="<?php echo esc_url( is_object( $cat ) ? get_term_link( $cat->slug, 'product_cat' ) : get_permalink() ); ?>">
				<?php
				do_action( 'helion_action_woocommerce_item_featured_link_start' );
	}
}

// Open item wrapper for categories and products
if ( ! function_exists( 'helion_woocommerce_open_item_wrapper' ) ) {
	
	
	function helion_woocommerce_title_wrapper_start( $cat = '' ) {
				do_action( 'helion_action_woocommerce_item_featured_link_end' );
		?>
				</a>
				<?php
				$hover = helion_get_theme_option( 'shop_hover' );
				if ( 'none' != $hover ) {
					?>
					<div class="mask"></div>
					<?php
					helion_hovers_add_icons( $hover, array( 'cat' => $cat ) );
				}
				do_action( 'helion_action_woocommerce_item_featured_end' );
				?>
			</div><!-- /.post_featured -->
			<div class="post_data">
				<div class="post_data_inner">
					<div class="post_header entry-header">
					<?php
					do_action( 'helion_action_woocommerce_item_header_start' );
	}
}


// Display product's tags before the title
if ( ! function_exists( 'helion_woocommerce_title_tags' ) ) {
	
	function helion_woocommerce_title_tags() {
		global $product;
		helion_show_layout( wc_get_product_tag_list( $product->get_id(), ', ', '<div class="post_tags product_tags">', '</div>' ) );
	}
}

// Wrap product title to the link
if ( ! function_exists( 'helion_woocommerce_the_title' ) ) {
	
	function helion_woocommerce_the_title( $title ) {
		if ( helion_storage_get( 'in_product_item' ) && get_post_type() == 'product' ) {
			$title = '<a href="' . esc_url( get_permalink() ) . '">' . esc_html( $title ) . '</a>';
		}
		return $title;
	}
}

// Wrap category title to the link: open tag
if ( ! function_exists( 'helion_woocommerce_before_subcategory_title' ) ) {
	
	function helion_woocommerce_before_subcategory_title( $cat ) {
		if ( helion_storage_get( 'in_product_item' ) && is_object( $cat ) ) {
			?>
			<a href="<?php echo esc_url( get_term_link( $cat->slug, 'product_cat' ) ); ?>">
			<?php
		}
	}
}

// Wrap category title to the link: close tag
if ( ! function_exists( 'helion_woocommerce_after_subcategory_title' ) ) {
	
	function helion_woocommerce_after_subcategory_title( $cat ) {
		if ( helion_storage_get( 'in_product_item' ) && is_object( $cat ) ) {
			?>
			</a>
			<?php
		}
	}
}

// Add excerpt in output for the product in the list mode
if ( ! function_exists( 'helion_woocommerce_title_wrapper_end' ) ) {
	
	function helion_woocommerce_title_wrapper_end() {
			do_action( 'helion_action_woocommerce_item_header_end' );
		?>
			</div><!-- /.post_header -->
		<?php
		if ( helion_storage_get( 'shop_mode' ) == 'list' && ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) && ! is_product() ) {
			?>
			<div class="post_content entry-content"><?php helion_show_layout( get_the_excerpt() ); ?></div>
			<?php
		}
	}
}

// Add excerpt in output for the product in the list mode
if ( ! function_exists( 'helion_woocommerce_title_wrapper_end2' ) ) {
	
	function helion_woocommerce_title_wrapper_end2( $category ) {
			do_action( 'helion_action_woocommerce_item_header_end' );
		?>
			</div><!-- /.post_header -->
		<?php
		if ( helion_storage_get( 'shop_mode' ) == 'list' && is_shop() && ! is_product() ) {
			?>
			<div class="post_content entry-content"><?php helion_show_layout( $category->description ); ?></div><!-- /.post_content -->
			<?php
		}
	}
}

// Close item wrapper for categories and products
if ( ! function_exists( 'helion_woocommerce_close_item_wrapper' ) ) {
	
	
	function helion_woocommerce_item_wrapper_end( $cat = '' ) {
		?>
				</div><!-- /.post_data_inner -->
			</div><!-- /.post_data -->
		</div><!-- /.post_item -->
		<?php
		helion_storage_set( 'in_product_item', false );
	}
}

// Change text on 'Add to cart' button
if ( ! function_exists( 'helion_woocommerce_add_to_cart_text' ) ) {
	function helion_woocommerce_add_to_cart_text( $text = '' ) {
		global $product;
		return is_object( $product ) && $product->is_in_stock()
		&& 'grouped' !== $product->get_type()
		&& ( 'external' !== $product->get_type() || $product->get_button_text() == '' )
		? esc_html__( 'Buy now', 'helion' )
		: $text;
	}
}

// Wrap 'Add to cart' button
if ( ! function_exists( 'helion_woocommerce_add_to_cart_link' ) ) {
	
	function helion_woocommerce_add_to_cart_link( $html, $product = false, $args = array() ) {
		return helion_is_off( helion_get_theme_option( 'shop_hover' ) ) ? sprintf( '<div class="add_to_cart_wrap">%s</div>', $html ) : $html;
	}
}


// Add wrap for buttons 'Compare' and 'Add to wishlist'
if ( ! function_exists( 'helion_woocommerce_add_wishlist_wrap' ) ) {
	
	function helion_woocommerce_add_wishlist_wrap() {
		if ( function_exists( 'YITH_WCWL' ) ) {
			?><div class="yith_buttons_wrap"><?php
		}
	}
}

// Add button 'Add to wishlist'
if ( ! function_exists( 'helion_woocommerce_add_wishlist_link' ) ) {
    //Handler of the add_action( 'woocommerce_after_shop_loop_item', 'helion_woocommerce_add_wishlist_link' ), 20 );
    function helion_woocommerce_add_wishlist_link() {
        if ( function_exists( 'YITH_WCWL' ) ) {
            $enabled_on_loop = 'yes' == get_option( 'yith_wcwl_show_on_loop' );
            $position = get_option( 'yith_wcwl_loop_position' );
            if ( $enabled_on_loop && 'before_image' == $position ) {
                helion_show_layout( do_shortcode( '[yith_wcwl_add_to_wishlist]' ) );
            }
            ?></div><?php
        }
    }
}


// Add label 'out of stock'
if ( ! function_exists( 'helion_woocommerce_add_out_of_stock_label' ) ) {
	
	function helion_woocommerce_add_out_of_stock_label() {
		global $product;
		$cat = helion_storage_get( 'in_product_category' );
		if ( empty($cat) || ! is_object($cat) ) {
			if ( is_object( $product ) && ! $product->is_in_stock() ) {
				?>
				<span class="outofstock_label"><?php esc_html_e( 'Out of stock', 'helion' ); ?></span>
				<?php
			}
		}
	}
}


// Add label with sale percent instead standard 'Sale'
if ( ! function_exists( 'helion_woocommerce_add_sale_percent' ) ) {
	
	function helion_woocommerce_add_sale_percent( $label, $post = '', $product = '' ) {
		$percent = '';
		if ( is_object( $product ) ) {
			if ( 'variable' === $product->get_type() ) {
				$prices  = $product->get_variation_prices();
				if ( ! is_array( $prices['regular_price'] ) && ! is_array( $prices['sale_price'] ) && $prices['regular_price'] > $prices['sale_price'] ) {
					$percent = round( ( $prices['regular_price'] - $prices['sale_price'] ) / $prices['regular_price'] * 100 );
				}
			} else {
				$price = $product->get_price_html();
				$prices = explode( '<ins', $price );
				if ( count( $prices ) > 1 ) {
					$prices[1] = '<ins' . $prices[1];
					$price_old = helion_parse_num( $prices[0] );
					$price_new = helion_parse_num( $prices[1] );
					if ( $price_old > 0 && $price_old > $price_new ) {
						$percent = round( ( $price_old - $price_new ) / $price_old * 100 );
					}
				}
			}
		}
		return ! empty( $percent ) ? '<span class="onsale">-' . esc_html( $percent ) . '%</span>' : $label;
	}
}


// Wrap price - start (WooCommerce use priority 10 to output price)
if ( ! function_exists( 'helion_woocommerce_price_wrapper_start' ) ) {
	
	function helion_woocommerce_price_wrapper_start() {
		if ( helion_storage_get( 'shop_mode' ) == 'thumbs' && ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) && ! is_product() ) {
			global $product;
			$price_html = $product->get_price_html();
			if ( '' != $price_html ) {
				?>
				<div class="price_wrap">
				<?php
			}
		}
	}
}


// Wrap price - start (WooCommerce use priority 10 to output price)
if ( ! function_exists( 'helion_woocommerce_price_wrapper_end' ) ) {
	
	function helion_woocommerce_price_wrapper_end() {
		if ( helion_storage_get( 'shop_mode' ) == 'thumbs' && ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) && ! is_product() ) {
			global $product;
			$price_html = $product->get_price_html();
			if ( '' != $price_html ) {
				?>
				</div><!-- /.price_wrap -->
				<?php
			}
		}
	}
}


// Decorate price
if ( ! function_exists( 'helion_woocommerce_get_price_html' ) ) {
	
	function helion_woocommerce_get_price_html( $price = '' ) {
		if ( ! is_admin() && ! empty( $price ) ) {
			$sep = get_option( 'woocommerce_price_decimal_sep' );
			if ( empty( $sep ) ) {
				$sep = '.';
			}
			$price = preg_replace( '/([0-9,]+)(\\' . trim( $sep ) . ')([0-9]{2})/', '\\1<span class="decimals_separator">\\2</span><span class="decimals">\\3</span>', $price );
		}
		return $price;
	}
}


// Decorate breadcrumbs
if ( ! function_exists( 'helion_woocommerce_breadcrumb_defaults' ) ) {
	
	function helion_woocommerce_breadcrumb_defaults( $args ) {
		$args['delimiter'] = '<span class="woocommerce-breadcrumb-delimiter"></span>';
		$args['before']    = '<span class="woocommerce-breadcrumb-item">';
		$args['after']     = '</span>';
		return $args;
	}
}

		



// Decorate WooCommerce output: Single product
//------------------------------------------------------------------------

// Add Product ID for the single product
if ( ! function_exists( 'helion_woocommerce_show_product_id' ) ) {
	
	function helion_woocommerce_show_product_id() {
		$authors = wp_get_post_terms( get_the_ID(), 'pa_product_author' );
		if ( is_array( $authors ) && count( $authors ) > 0 ) {
			echo '<span class="product_author">' . esc_html__( 'Author: ', 'helion' );
			$delim = '';
			foreach ( $authors as $author ) {
				echo  esc_html( $delim ) . '<span>' . esc_html( $author->name ) . '</span>';
				$delim = ', ';
			}
			echo '</span>';
		}
		echo '<span class="product_id">' . esc_html__( 'Product ID: ', 'helion' ) . '<span>' . get_the_ID() . '</span></span>';
	}
}

// Number columns for the product's thumbnails
if ( ! function_exists( 'helion_woocommerce_product_thumbnails_columns' ) ) {
	
	function helion_woocommerce_product_thumbnails_columns( $cols ) {
		return 4;
	}
}

// Set products number for the related products
if ( ! function_exists( 'helion_woocommerce_output_related_products_args' ) ) {
	
	function helion_woocommerce_output_related_products_args( $args ) {
		$args['posts_per_page'] = (int) helion_get_theme_option( 'show_related_posts' )
										? max( 0, min( 9, helion_get_theme_option( 'related_posts' ) ) )
										: 0;
		$args['columns']        = max( 1, min( 4, helion_get_theme_option( 'related_columns' ) ) );
		return $args;
	}
}

// Set columns number for the related products
if ( ! function_exists( 'helion_woocommerce_related_products_columns' ) ) {
	
	
	
	function helion_woocommerce_related_products_columns( $columns = 0 ) {
		$columns = max( 1, min( 4, helion_get_theme_option( 'related_columns' ) ) );
		return $columns;
	}
}



// Decorate WooCommerce output: Widgets
//------------------------------------------------------------------------

// Search form
if ( ! function_exists( 'helion_woocommerce_get_product_search_form' ) ) {
	
	function helion_woocommerce_get_product_search_form( $form ) {
		return '
		<form role="search" method="get" class="search_form" action="' . esc_url( home_url( '/' ) ) . '">
			<input type="text" class="search_field" placeholder="' . esc_attr__( 'Search for products &hellip;', 'helion' ) . '" value="' . get_search_query() . '" name="s" /><button class="search_button" type="submit">' . esc_html__( 'Search', 'helion' ) . '</button>
			<input type="hidden" name="post_type" value="product" />
		</form>
		';
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if ( helion_exists_woocommerce() ) {
	require_once HELION_THEME_DIR . 'plugins/woocommerce/woocommerce-styles.php';
}