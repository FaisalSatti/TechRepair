<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

/**
 * Shortcode converter function
 */
function woo_product_slider_id( $id ) {
	echo do_shortcode( '[woo_product_slider id="' . $id . '"]' );
}

/**
 * Functions
 */
class SP_Woo_Product_Slider_Functions {

	/**
	 * SP_Woo_Product_Slider_Functions single instance of the class
	 *
	 * @var null
	 * @since 2.0
	 */
	protected static $_instance = null;

	/**
	 * Main SP_Woo_Product_Slider_Functions Instance
	 *
	 * @since 2.0
	 * @static
	 * @see SP_Woo_Product_Slider_Functions()
	 * @return self Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Initialize the class
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 100 );
		add_filter( 'admin_footer_text', array( $this, 'admin_footer' ), 1, 2 );
	}

	/**
	 * Admin Menu
	 */
	function admin_menu() {
		add_submenu_page( 'edit.php?post_type=sp_wps_shortcodes', __( 'Product Slider for WooCommerce Help', 'woo-product-slider' ), __( 'Help', 'woo-product-slider' ), 'manage_options', 'wps_help', array( $this, 'help_page_callback' ) );
	}

	/**
	 * Help Page Callback
	 */
	public function help_page_callback() {
		?>
		<div class="wrap about-wrap sp-wps-help">
			<h1><?php _e( 'Welcome to Product Slider for WooCommerce!', 'woo-product-slider' ); ?></h1>
			<p class="about-text"><?php _e( 'Thank you for installing Product Slider for WooCommerce! You\'re now running the most popular WooCommerce Product Slider plugin. This video will help you get started with the plugin.', 'woo-product-slider' ); ?></p>
			<div class="wp-badge"></div>

			<hr>

			<div class="headline-feature feature-video">
				<iframe width="560" height="315" src="https://www.youtube.com/embed/CE0VJoJhAjw" frameborder="0" allowfullscreen></iframe>
			</div>

			<hr>

			<div class="feature-section three-col">
				<div class="col">
					<div class="sp-wps-feature sp-wps-text-center">
						<i class="fa fa-life-ring"></i>
						<h3>Need any Assistance?</h3>
						<p>Our Expert Support Team is always ready to help you out promptly.</p>
						<a href="https://shapedplugin.com/support-forum/" target="_blank" class="button button-primary">Contact Support</a>
					</div>
				</div>
				<div class="col">
					<div class="sp-wps-feature sp-wps-text-center">
						<i class="fa fa-file-text"></i>
						<h3>Looking for Documentation?</h3>
						<p>We have detailed documentation on every aspects of Product Slider for WooCommerce.</p>
						<a href="https://shapedplugin.com/docs/docs/woocommerce-product-slider/" target="_blank" class="button button-primary">Documentation</a>
					</div>
				</div>
				<div class="col">
					<div class="sp-wps-feature sp-wps-text-center">
						<i class="fa fa-bug"></i>
						<h3>Found any Bugs?</h3>
						<p>Report any bug that you found, Get a instant solutions from developer.</p>
						<a href="https://shapedplugin.com/support-forum/" target="_blank" class="button button-primary">Report a Bug</a>
					</div>
				</div>
			</div>

			<hr>
			
		</div>
		<?php
	}

	/**
	 * Review Text
	 *
	 * @param $text
	 *
	 * @return string
	 */
	public function admin_footer( $text ) {
		$screen = get_current_screen();
		if ( 'sp_wps_shortcodes' === $screen->post_type || 'sp_wps_shortcodes_page_wps_settings' === $screen->id ) {

			$url  = 'https://wordpress.org/support/plugin/woo-product-slider/reviews/?filter=5#new-post';
			$text = sprintf( __( 'If you like <strong>Product Slider for WooCommerce</strong> please leave us a <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. Your Review is very important to us as it helps us to grow more. ', 'woo-product-slider' ), $url );
		}

		return $text;
	}

}

new SP_Woo_Product_Slider_Functions();
