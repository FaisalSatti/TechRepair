<?php
/**
 * This is to register the shortcode generator post type.
 *
 * @package Woo_Product_Slider
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class SP_WPS_ShortCodes {

	/**
	 * @var
	 */
	private static $_instance;

	/**
	 * @return SP_WPS_ShortCodes
	 */
	public static function getInstance() {
		if ( ! self::$_instance ) {
			self::$_instance = new SP_WPS_ShortCodes();
		}

		return self::$_instance;
	}

	/**
	 * SP_WPS_ShortCodes constructor.
	 */
	public function __construct() {
		add_filter( 'init', array( $this, 'register_post_type' ) );
	}

	/**
	 * ShortCode generator post type.
	 */
	function register_post_type() {

		$labels = apply_filters(
			'sp_wps_post_type_labels',
			array(
				'name'               => esc_html__( 'All Sliders', 'woo-product-slider' ),
				'singular_name'      => esc_html__( 'Product Slider', 'woo-product-slider' ),
				'menu_name'          => esc_html__( 'Product Slider', 'woo-product-slider' ),
				'all_items'          => esc_html__( 'All Sliders', 'woo-product-slider' ),
				'add_new'            => esc_html__( 'Add New', 'woo-product-slider' ),
				'add_new_item'       => esc_html__( 'Add New Slider', 'woo-product-slider' ),
				'edit'               => esc_html__( 'Edit', 'woo-product-slider' ),
				'edit_item'          => esc_html__( 'Edit Slider', 'woo-product-slider' ),
				'new_item'           => esc_html__( 'Product Slider', 'woo-product-slider' ),
				'search_items'       => esc_html__( 'Search Product Sliders', 'woo-product-slider' ),
				'not_found'          => esc_html__( 'No Product Sliders found', 'woo-product-slider' ),
				'not_found_in_trash' => esc_html__( 'No Product Sliders in Trash', 'woo-product-slider' ),
				'parent'             => esc_html__( 'Parent Product Slider', 'woo-product-slider' ),
			)
		);

		$args = apply_filters(
			'sp_wps_post_type_args',
			array(
				'labels'          => $labels,
				'hierarchical'    => false,
				'description'     => esc_html__( 'Product Slider for WooCommerce', 'woo-product-slider' ),
				'public'          => false,
				'show_ui'         => current_user_can( 'manage_options' ) ? true : false,
				'show_in_menu'    => current_user_can( 'manage_options' ) ? true : false,
				'menu_icon'       => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxNy4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkNhcGFfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHdpZHRoPSI2MTJweCIgaGVpZ2h0PSI3OTJweCIgdmlld0JveD0iMCAwIDYxMiA3OTIiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDYxMiA3OTIiIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPGc+DQoJPGc+DQoJCTxwYXRoIGZpbGw9IiM5RkE0QTkiIGQ9Ik01NzcuODAxLDMwMi40MDhsLTQyMy44OTktMzkuNTMxYy0yLjMyNS0wLjI3NC00LjUxNC0wLjI3NC02LjcwMi0wLjEzN2wtMTYuOTYxLTgyLjA3MQ0KCQkJYy0wLjQxLTEuNjQxLTAuODIxLTMuMjgzLTEuNTA1LTQuNjUxYy0yLjU5OS02LjU2Ni04LjA3LTExLjktMTUuMzItMTQuMDg5bC04MC44NC0yMy44MDENCgkJCWMtMTIuNDQ4LTMuNjkzLTI1LjU3OSwzLjQyLTI5LjI3MiwxNi4wMDRjLTMuNjkzLDEyLjQ0OCwzLjQyLDI1LjU3OSwxNi4wMDQsMjkuMjcybDY3LjQzNSwxOS44MzRsODEuMTE0LDM5MC45MzQNCgkJCWMxLjUwNSwxMS40OSwxMS4zNTMsMjAuMzgxLDIzLjM5LDIwLjM4MWwzNDguODA0LTAuMTM3YzEyLjk5NSwwLDIzLjUyNy0xMC41MzMsMjMuNTI3LTIzLjUyN3MtMTAuNTMyLTIzLjUyNy0yMy41MjctMjMuNTI3DQoJCQlsLTMyOS41MTcsMC4xMzdsLTExLjIxNi01NC4wM2gzNDYuODg5YzE4LjE5MiwwLDMzLjkyMy0xMi4wMzcsMzcuMjA2LTI4LjU4OGwyOC4wNDEtMTQxLjMNCgkJCUM2MTUuMTQzLDMyMy4zMzYsNTk5LjgyMywzMDQuNDYsNTc3LjgwMSwzMDIuNDA4eiIvPg0KCQk8Y2lyY2xlIGZpbGw9IiM5RkE0QTkiIGN4PSIyNTguMjY5IiBjeT0iNjgyLjk0NiIgcj0iNDcuMDU0Ii8+DQoJCTxjaXJjbGUgZmlsbD0iIzlGQTRBOSIgY3g9IjQ4My4xNDUiIGN5PSI2ODIuOTQ2IiByPSI0Ny4wNTQiLz4NCgkJPHBhdGggZmlsbD0iIzlGQTRBOSIgZD0iTTIxNy45MTgsMjQ0LjY4NGw1LjMzNSwwLjU0N2wxNi42ODgsMS41MDVsNjguNjY2LDYuNDI5bDIuNDYyLTI2LjQNCgkJCWMzLjQyLTM1LjcwMSwzNS4wMTctNjEuODI3LDcwLjcxOC01OC41NDRjMzUuNzAxLDMuNDIsNjEuODI3LDM1LjAxNyw1OC41NDQsNzAuNzE4bC0yLjQ2MiwyNi40bDY4LjY2Niw2LjQyOWwyMS44ODYsMi4wNTINCgkJCWM4LjA3LDAuODIxLDE1LjMyLTUuMTk4LDE2LjAwNC0xMy4yNjhsMi4xODktMjMuNTI3YzAuODIxLTguMDctNS4xOTgtMTUuMzItMTMuMjY4LTE2LjAwNGwtMjEuODg2LTIuMDUyDQoJCQljLTEuNzc4LTE4LjE5My03LjI1LTM1LjQyOC0xNS43My01MC44ODRsMTYuOTYxLTE0LjA4OWM2LjI5Mi01LjE5OCw3LjExMy0xNC40OTksMS45MTUtMjAuNzkxbC0xNS4wNDYtMTguMTkzDQoJCQljLTUuMTk4LTYuMjkyLTE0LjQ5OS03LjExMy0yMC43OTEtMS45MTVsLTE3LjA5OCwxMy45NTJjLTEzLjY3OS0xMS4wOC0yOS41NDYtMTkuNjk3LTQ3LjA1NC0yNC44OTVsMi4wNTItMjEuODg2DQoJCQljMC44MjEtOC4wNy01LjE5OC0xNS4zMi0xMy4yNjgtMTYuMDA0bC0yMy41MjctMi4xODljLTguMDctMC44MjEtMTUuMzIsNS4xOTgtMTYuMDA0LDEzLjI2OGwtMi4wNTIsMjEuODg2DQoJCQljLTE4LjE5MiwxLjc3OC0zNS40MjcsNy4yNS01MC44ODQsMTUuNzNsLTE0LjA4OS0xNi45NjFjLTUuMTk4LTYuMjkyLTE0LjQ5OS03LjExMy0yMC43OTEtMS45MTVsLTE4LjE5MywxNS4wNDYNCgkJCWMtNi4yOTIsNS4xOTgtNy4xMTMsMTQuNDk5LTEuOTE1LDIwLjc5MWwxNC4wODksMTYuOTYxYy0xMS4wOCwxMy42NzktMTkuNjk3LDI5LjU0Ni0yNC44OTUsNDcuMDU0bC0yMS44ODYtMi4wNTINCgkJCWMtOC4wNy0wLjgyMS0xNS4zMiw1LjE5OC0xNi4wMDQsMTMuMjY4bC0yLjE4OSwyMy41MjdDMjAzLjgyOSwyMzYuNzUxLDIwOS44NDcsMjQzLjg2MywyMTcuOTE4LDI0NC42ODR6Ii8+DQoJPC9nPg0KPC9nPg0KPC9zdmc+DQo=',
				'menu_position'   => 57,
				'query_var'       => false,
				'capability_type' => 'post',
				'supports'        => array(
					'title',
				),
			)
		);

		register_post_type( 'sp_wps_shortcodes', $args );
	}

}

new SP_WPS_ShortCodes();
