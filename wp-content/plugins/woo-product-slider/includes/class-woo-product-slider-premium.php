<?php
/**
 * The premium page.
 *
 * @since      2.2.0
 * @package    Woo_Product_Slider
 * @subpackage Woo_Product_Slider/admin/view
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access.

/**
 * The premium page class.
 */
class Woo_Product_Slider_Premium {

	/**
	 * Single instance of the class
	 *
	 * @var null
	 * @since 2.2.0
	 */
	protected static $_instance = null;

	/**
	 * Main Instance
	 *
	 * @since 2.2.0
	 * @static
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
		add_action( 'admin_menu', array( $this, 'premium_admin_menu' ), 100 );
	}

	/**
	 * Add admin menu.
	 *
	 * @since 2.2.0
	 * @return void
	 */
	public function premium_admin_menu() {
		add_submenu_page(
			'edit.php?post_type=sp_wps_shortcodes',
			__( 'Premium', 'woo-product-slider' ),
			__( 'Premium', 'woo-product-slider' ),
			'manage_options',
			'wps_premium',
			array(
				$this,
				'premium_page_callback',
			)
		);
	}

	/**
	 * The Premium Callback.
	 *
	 * @since 2.2.0
	 * @return void
	 */
	public function premium_page_callback() {
		?><div class="wrap about-wrap sp-wps-help sp-wps-upgrade">
			<h1><?php _e( 'Upgrade to <span>Product Slider Pro for WooCommerce</span>', 'woo-product-slider' ); ?></h1>
			<p class="about-text">
			<?php
			esc_html_e(
				'Get more Advanced Functionality & Flexibility with the Premium version.',
				'woo-product-slider'
			);
			?>
			</p>
			<div class="wp-badge"></div>
			<ul>
				<li class="wps-upgrade-btn"><a href="https://shapedplugin.com/plugin/woocommerce-product-slider-pro/?ref=1" target="_blank">Buy Now <img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPHN2ZyB3aWR0aD0iMTc5MiIgaGVpZ2h0PSIxNzkyIiB2aWV3Qm94PSIwIDAgMTc5MiAxNzkyIiBmaWxsPSIjZmZmIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Ik0xMTUyIDg5NnEwIDI2LTE5IDQ1bC00NDggNDQ4cS0xOSAxOS00NSAxOXQtNDUtMTktMTktNDV2LTg5NnEwLTI2IDE5LTQ1dDQ1LTE5IDQ1IDE5bDQ0OCA0NDhxMTkgMTkgMTkgNDV6Ii8+PC9zdmc+" alt="" style="max-width: 15px;"/></a></li>
				<li class="wps-upgrade-btn"><a href="https://demo.shapedplugin.com/woocommerce-product-slider/" target="_blank">Live Demo & All Features <img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPHN2ZyB3aWR0aD0iMTUiIGhlaWdodD0iMTUiIHZpZXdCb3g9IjAgMCAxNzkyIDE3OTIiIGZpbGw9IiMwMDczYWEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0iTTk3OSA5NjBxMCAxMy0xMCAyM2wtNDY2IDQ2NnEtMTAgMTAtMjMgMTB0LTIzLTEwbC01MC01MHEtMTAtMTAtMTAtMjN0MTAtMjNsMzkzLTM5My0zOTMtMzkzcS0xMC0xMC0xMC0yM3QxMC0yM2w1MC01MHExMC0xMCAyMy0xMHQyMyAxMGw0NjYgNDY2cTEwIDEwIDEwIDIzem0zODQgMHEwIDEzLTEwIDIzbC00NjYgNDY2cS0xMCAxMC0yMyAxMHQtMjMtMTBsLTUwLTUwcS0xMC0xMC0xMC0yM3QxMC0yM2wzOTMtMzkzLTM5My0zOTNxLTEwLTEwLTEwLTIzdDEwLTIzbDUwLTUwcTEwLTEwIDIzLTEwdDIzIDEwbDQ2NiA0NjZxMTAgMTAgMTAgMjN6Ii8+PC9zdmc+" alt="" style="max-width: 15px;"/></a></li>
			</ul>

			<hr>

			<div class="sp-wps-pro-features">
				<h2 class="sp-wps-text-center">Premium Features You'll Love</h2>
				<p class="sp-wps-text-center sp-wps-pro-subtitle">We've added 100+ extra features in our Premium Version of this plugin. Let’s see some amazing features.</p>

				<div class="feature-section three-col">
					<div class="col">
						<div class="sp-wps-feature">
							<h3><span class="dashicons dashicons-yes"></span>Slick and Beautiful</h3>
							<p>The plugin is carefully crafted and well designed, highly customizable. Product Slider for WooCommerce has a slick and robust settings panel with 30+ beautiful themes.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-wps-feature">
							<h3><span class="dashicons dashicons-yes"></span>Responsive Mobile Ready</h3>
							<p>Product Slider for WooCommerce is fully responsive and looks fine on any device. You have full control to set specific columns on devices. It adapts to fit any screen size.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-wps-feature">
							<h3><span class="dashicons dashicons-yes"></span>Filter the List of Products</h3>
							<p>Filter the list of products you want to show in the slider or grid: <strong>featured, categories, tags, on sale, best selling, top-rated, most viewed, SKU, attribute, free, out of stock,</strong> etc.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-wps-feature">
							<h3><span class="dashicons dashicons-yes"></span>Display Specific Products</h3>
							<p>A product slider is one of the best ways to highlight your specific products and, if you put this slider in strategic positions, it will boost your WooCommerce store sales.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-wps-feature">
							<h3><span class="dashicons dashicons-yes"></span>Upsells and Cross-sells</h3>
							<p>Display Upsells & Cross-sells product slider and boost sales. Upsells products are recommended instead of the currently viewed product and Cross-sells are promoted in the cart.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-wps-feature">
							<h3><span class="dashicons dashicons-yes"></span>Related Products Slider</h3>
							<p>Quickly increase your customers’ engagement with your products by adding Related Products Slider at the bottom of your single product page. Boost your internal traffic by up to 10%!</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-wps-feature">
							<h3><span class="dashicons dashicons-yes"></span>Filter by Product Types</h3>
							<p>You can filter by different product types with the Product Slider Pro for WooCommerce: <strong>Simple Product, Grouped Product, External/Affiliate Product, Variable product.</strong></p>
						</div>
					</div>
					<div class="col">
						<div class="sp-wps-feature">
							<h3><span class="dashicons dashicons-yes"></span>Shortcode Generator</h3>
							<p>The plugin has a built-in Shortcode Generator settings panel. You don’t need any coding skills to show a product slider or grid. You can create, edit and delete shortcodes easily.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-wps-feature">
							<h3><span class="dashicons dashicons-yes"></span>2 Layouts (Slider & Grid)</h3>
							<p>Product Slider Pro for WooCommerce comes with 2 unique layouts presets like Carousel Slider and Grid to display your products. The layout presets are fully customizable.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-wps-feature">
							<h3><span class="dashicons dashicons-yes"></span>Ajax pagination Types</h3>
							<p>The Grid layout has 4 types of pagination control: Ajax number, Load more (ajax), Infinite scroll (ajax), No ajax. Set how many members want to show per page and per click.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-wps-feature">
							<h3><span class="dashicons dashicons-yes"></span>30+ Pre-designed Themes</h3>
							<p>The premium plugin comes with 30+ ready themes that are fully customizable directly from the generator settings. Choose your desired theme and stylize to fit your requirements.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-wps-feature">
							<h3><span class="dashicons dashicons-yes"></span>Unlimited Product Sliders</h3>
							<p>Create unlimited product sliders, grids as you like across your WooCommerce store or site in a few clicks with beautifully designed themes and multiple options.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-wps-feature">
							<h3><span class="dashicons dashicons-yes"></span>940+ Google Fonts</h3>
							<p>This is very important to be able to set fonts & colors to match your brand. The Pro plugin supports 950+ Google fonts & typography options. Enable or disable Google fonts loading.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-wps-feature">
							<h3><span class="dashicons dashicons-yes"></span>Customize Everything</h3>
							<p>You will be able to make it look exactly how you want with layout and colors & typography settings! You have full control over styling to design your way. No coding skills required!</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-wps-feature">
							<h3><span class="dashicons dashicons-yes"></span>10+ Slider Controls</h3>
							<p>You can set how many products to scroll at a time in the slider, speed, autoplay, swipe, pause on hover, infinite loop, mouse draggable, ticker mode, and many other settings.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-wps-feature">
							<h3><span class="dashicons dashicons-yes"></span>Multiple Row Slider</h3>
							<p>You can slide the unlimited number of rows at a time in product slider. We normally set a single row by default. Set the number of product row(s) in the slider as you desire.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-wps-feature">
							<h3><span class="dashicons dashicons-yes"></span>Woo Plugins Compatibility</h3>
							<p>The Product Slider for WooCommerce plugin is tested to be compatible with most plugins in the WooCommerce including Wishlist & Compare and Quick View plugins.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-wps-feature">
							<h3><span class="dashicons dashicons-yes"></span>Manage Ribbon and Badge</h3>
							<p>You can show different ribbon and badge for products in the slider or grid layout. The badges e.g. On Sale! and Out of Stock are fully customizable. Boost conversions of up to 50%!</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-wps-feature">
							<h3><span class="dashicons dashicons-yes"></span>Product Image Lightbox</h3>
							<p>Lightbox functionality for your product image can help you to zoom in or larger view images when it is clicked on. It attracts your visitor much than generally sized images.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-wps-feature">
							<h3><span class="dashicons dashicons-yes"></span>Trendy Images Effects</h3>
							<p>You can show a modern highlighted box-shadow and material style for the product. <strong>Product image hover flip, grayscale, mouse overlay.</strong> Customize image border, style, color, etc.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-wps-feature">
							<h3><span class="dashicons dashicons-yes"></span>Custom Image Size</h3>
							<p>You can change the default size of product images from the settings panel. This way, when you upload new images they will be resized to the specified dimensions.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-wps-feature">
							<h3><span class="dashicons dashicons-yes"></span>Page Builders Ready</h3>
							<p>The plugin supports Gutenberg and popular page builder plugins: Gutenberg, WPBakery, Elementor, Divi, BeaverBuilder, Fusion Builder, SiteOrgin, Themify Builder, etc.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-wps-feature">
							<h3><span class="dashicons dashicons-yes"></span>Multilingual Supported</h3>
							<p>We would like to speak to your language. It is fully multilingual ready with WPML, Loco Translate, Polylang, qTranslate-x, GTranslate, Google Language Translator, WPGlobus, etc.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-wps-feature">
							<h3><span class="dashicons dashicons-yes"></span>Top-notch Support</h3>
							<p>A fully dedicated Support Team is always ready to help you instantly whenever you face any issues to configure or use the plugin. We care for our customers day by day, not for one time.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-wps-feature">
							<h3><span class="dashicons dashicons-yes"></span>Lifetime Automatic Updates</h3>
							<p>We are regularly working to improve the Product Slider for WooCommerce plugin and adding new features, bug fixing, and security patches. So you guaranteed for better updates.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-wps-feature">
							<h3><span class="dashicons dashicons-yes"></span>Countless Theme Compatibility</h3>
							<p>We have tested it’s compatibility on different WordPress themes including Genesis, Divi, WooThemes, ThemeForest or WordPress.org. In some themes, you need some tweaks.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-wps-feature">
							<h3><span class="dashicons dashicons-yes"></span>Advanced Plugin Settings</h3>
							<p>The plugin is fully customizable and has a custom CSS field to override styles if necessary. You can enqueue or dequeue different JS/CSS to avoid conflicts and loading issue.</p>
						</div>
					</div>
				</div>

			</div>
			<hr>					
			<h2 class="sp-wps-text-center sp-wps-promo-video-title">Watch How <b>Product Slider Pro for WooCommerce</b> Works</h2>
				<div class="headline-feature feature-video">

				<iframe width="1050" height="590" src="https://www.youtube.com/embed/N26gMvfeGiw" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
				</div>
				<hr>
				<div class="sp-wps-join-community sp-wps-text-center">
					<h2>Join the <b>20000+</b> Happy Users Worldwide!</h2>
					<a class="wps-upgrade-btn" target="_blank" href="https://shapedplugin.com/plugin/woocommerce-product-slider-pro/?ref=1">Get a license instantly</a>
					<p>Every purchase comes with <b>14-days</b> money back guarantee and access to our incredibly Top-notch Support with lightening-fast response time and 100% satisfaction rate.</p>
				</div>
				<br>
				<br>

				<hr>
				<div class="sp-wps-upgrade-sticky-footer sp-wps-text-center">
					<p><a href="https://demo.shapedplugin.com/woocommerce-product-slider/" target="_blank" class="button
					button-primary">Live Demo</a> <a href="https://shapedplugin.com/plugin/woocommerce-product-slider-pro/?ref=1" target="_blank" class="button button-primary">Upgrade Now</a></p>
				</div>
		</div>
		<?php
	}

}
