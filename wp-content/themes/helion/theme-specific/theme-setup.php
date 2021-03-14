<?php
/**
 * Setup theme-specific fonts and colors
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0.22
 */

// If this theme is a free version of premium theme
if ( ! defined( 'HELION_THEME_FREE' ) ) {
	define( 'HELION_THEME_FREE', false );
}
if ( ! defined( 'HELION_THEME_FREE_WP' ) ) {
	define( 'HELION_THEME_FREE_WP', false );
}

// If this theme is a part of Envato Elements
if ( ! defined( 'HELION_THEME_IN_ENVATO_ELEMENTS' ) ) {
	define( 'HELION_THEME_IN_ENVATO_ELEMENTS', false );
}

// If this theme uses multiple skins
if ( ! defined( 'HELION_ALLOW_SKINS' ) ) {
	define( 'HELION_ALLOW_SKINS', false );
}
if ( ! defined( 'HELION_DEFAULT_SKIN' ) ) {
	define( 'HELION_DEFAULT_SKIN', 'default' );
}



// Theme storage
// Attention! Must be in the global namespace to compatibility with WP CLI
//-------------------------------------------------------------------------
$GLOBALS['HELION_STORAGE'] = array(

	// Key validator: market[env|loc]-vendor[axiom|ancora|themerex]
	'theme_pro_key'       => 'env-axiom',

	// Generate Personal token from Envato to automatic upgrade theme
	'upgrade_token_url'   => '//build.envato.com/create-token/?default=t&purchase:download=t&purchase:list=t',

	// Theme-specific URLs (will be escaped in place of the output)
	'theme_demo_url'      => '//helion.axiomthemes.com',
	'theme_doc_url'       => '//helion.axiomthemes.com/doc/',

	'theme_upgrade_url'   => '//upgrade.themerex.net/',

	'theme_demofiles_url' => '//demofiles.axiomthemes.com/helion/',
	
	'theme_rate_url'      => '//themeforest.net/download',

	'theme_custom_url' => '//themerex.net/offers/?utm_source=offers&utm_medium=click&utm_campaign=themedash',

	'theme_download_url'  => '//themeforest.net/item/helion-personal-creative-portfolio-wordpress-theme-store',         // Axiom

	'theme_support_url'   => '//themerex.net/support/',                              // Axiom

	'theme_video_url'     => '//www.youtube.com/channel/UCBjqhuwKj3MfE3B6Hg2oA8Q',   // Axiom

	'theme_privacy_url'   => '//axiomthemes.com/privacy-policy/',                    // Axiom


	// Comma separated slugs of theme-specific categories (for get relevant news in the dashboard widget)
	// (i.e. 'children,kindergarten')
	'theme_categories'    => '',

	// Responsive resolutions
	// Parameters to create css media query: min, max
	'responsive'          => array(
		// By size
		'xxl'        => array( 'max' => 1679 ),
		'xl'         => array( 'max' => 1439 ),
		'lg'         => array( 'max' => 1279 ),
		'md_over'    => array( 'min' => 1024 ),
		'md'         => array( 'max' => 1023 ),
		'sm'         => array( 'max' => 767 ),
		'sm_wp'      => array( 'max' => 600 ),
		'xs'         => array( 'max' => 479 ),
		// By device
		'wide'       => array(
			'min' => 2160
		),
		'desktop'    => array(
			'min' => 1680,
			'max' => 2159,
		),
		'notebook'   => array(
			'min' => 1280,
			'max' => 1679,
		),
		'tablet'     => array(
			'min' => 768,
			'max' => 1279,
		),
		'not_mobile' => array(
			'min' => 768
		),
		'mobile'     => array(
			'max' => 767
		),
	),
);


//------------------------------------------------------------------------
// One-click import support
//------------------------------------------------------------------------

// Set theme specific importer options
if ( ! function_exists( 'helion_importer_set_options' ) ) {
	add_filter( 'trx_addons_filter_importer_options', 'helion_importer_set_options', 9 );
	function helion_importer_set_options( $options = array() ) {
		if ( is_array( $options ) ) {
			// Save or not installer's messages to the log-file
			$options['debug'] = false;
			// Allow import/export functionality
			$options['allow_import'] = true;
			$options['allow_export'] = false;
			// Prepare demo data
			$options['demo_url'] = esc_url('http://demofiles.axiomthemes.com/helion/');
			// Required plugins
			$options['required_plugins'] = array_keys( helion_storage_get( 'required_plugins' ) );
			// Set number of thumbnails (usually 3 - 5) to regenerate at once when its imported (if demo data was zipped without cropped images)
			// Set 0 to prevent regenerate thumbnails (if demo data archive is already contain cropped images)
			$options['regenerate_thumbnails'] = 0;
			// Default demo
			$options['files']['default']['title']       = esc_html__( 'Helion Demo', 'helion' );
			$options['files']['default']['domain_dev']  = '';   // Developers domain
			$options['files']['default']['domain_demo'] = esc_url( 'http://helion.axiomthemes.com' );   // Demo-site domain

			// The array with theme-specific banners, displayed during demo-content import.
			// If array with banners is empty - the banners are uploaded directly from demo-content server.
			$options['banners'] = array();
		}
		return $options;
	}
}


//------------------------------------------------------------------------
// OCDI support
//------------------------------------------------------------------------

// Set theme specific OCDI options
if ( ! function_exists( 'helion_ocdi_set_options' ) ) {
	add_filter( 'trx_addons_filter_ocdi_options', 'helion_ocdi_set_options', 9 );
	function helion_ocdi_set_options( $options = array() ) {
		if ( is_array( $options ) ) {
			// Prepare demo data
			$options['demo_url'] = esc_url('http://demofiles.axiomthemes.com/helion/');
			// Required plugins
			$options['required_plugins'] = array_keys( helion_storage_get( 'required_plugins' ) );
			// Demo-site domain
			$options['files']['ocdi']['title']       = esc_html__( 'Helion OCDI Demo', 'helion' );
			$options['files']['ocdi']['domain_demo'] = esc_url( 'http://helion.axiomthemes.com' );

		}
		return $options;
	}
}



// THEME-SUPPORTED PLUGINS
// If plugin not need - remove its settings from next array
//----------------------------------------------------------
$helion_theme_required_plugins_groups = array(
	'core'          => esc_html__( 'Core', 'helion' ),
	'page_builders' => esc_html__( 'Page Builders', 'helion' ),
	'ecommerce'     => esc_html__( 'E-Commerce & Donations', 'helion' ),
	'socials'       => esc_html__( 'Socials and Communities', 'helion' ),
	'events'        => esc_html__( 'Events and Appointments', 'helion' ),
	'content'       => esc_html__( 'Content', 'helion' ),
	'other'         => esc_html__( 'Other', 'helion' ),
);
$helion_theme_required_plugins        = array(
	'trx_addons'                 => array(
		'title'       => esc_html__( 'ThemeREX Addons', 'helion' ),
		'description' => esc_html__( "Will allow you to install recommended plugins, demo content, and improve the theme's functionality overall with multiple theme options", 'helion' ),
		'required'    => true,
		'logo'        => 'trx_addons.png',
		'group'       => $helion_theme_required_plugins_groups['core'],
	),
	'elementor'                  => array(
		'title'       => esc_html__( 'Elementor', 'helion' ),
		'description' => esc_html__( "Is a beautiful PageBuilder, even the free version of which allows you to create great pages using a variety of modules.", 'helion' ),
		'required'    => false,
		'logo'        => 'elementor.png',
		'group'       => $helion_theme_required_plugins_groups['page_builders'],
	),
    'essential-addons-for-elementor-lite'       => array(
        'title'       => esc_html__( 'Essential Addons for Elementor', 'helion' ),
        'description' => esc_html__( "The ultimate elements library for Elementor PageBuilder", 'helion' ),
        'required'    => false,
        'logo'        => 'essential-addons-for-elementor-lite.png',
        'group'       => $helion_theme_required_plugins_groups['page_builders'],
    ),
	'gutenberg'                  => array(
		'title'       => esc_html__( 'Gutenberg', 'helion' ),
		'description' => esc_html__( "It's a posts editor coming in place of the classic TinyMCE. Can be installed and used in parallel with Elementor", 'helion' ),
		'required'    => false,
		'install'     => false,          // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'gutenberg.png',
		'group'       => $helion_theme_required_plugins_groups['page_builders'],
	),
	'js_composer'                => array(
		'title'       => esc_html__( 'WPBakery PageBuilder', 'helion' ),
		'description' => esc_html__( "Popular PageBuilder which allows you to create excellent pages", 'helion' ),
		'required'    => false,
		'install'     => false,          // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'js_composer.jpg',
		'group'       => $helion_theme_required_plugins_groups['page_builders'],
	),
	'vc-extensions-bundle'       => array(
		'title'       => esc_html__( 'WPBakery PageBuilder extensions bundle', 'helion' ),
		'description' => esc_html__( "Many shortcodes for the WPBakery PageBuilder", 'helion' ),
		'required'    => false,
		'install'     => false,      // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'vc-extensions-bundle.png',
		'group'       => $helion_theme_required_plugins_groups['page_builders'],
	),
	'woocommerce'                => array(
		'title'       => esc_html__( 'WooCommerce', 'helion' ),
		'description' => esc_html__( "Connect the store to your website and start selling now", 'helion' ),
		'required'    => false,
		'logo'        => 'woocommerce.png',
		'group'       => $helion_theme_required_plugins_groups['ecommerce'],
	),
	'elegro-payment'             => array(
		'title'       => esc_html__( 'Elegro Crypto Payment', 'helion' ),
		'description' => esc_html__( "Extends WooCommerce Payment Gateways with an elegro Crypto Payment", 'helion' ),
		'required'    => false,
		'logo'        => 'elegro-payment.png',
		'group'       => $helion_theme_required_plugins_groups['ecommerce'],
	),
	'yith-woocommerce-compare'                => array(
		'title'       => esc_html__( 'YITH WooCommerce Compare', 'helion' ),
		'required'    => false,
		'logo'        => 'logo.jpg',
		'group'       => $helion_theme_required_plugins_groups['ecommerce'],
	),
	'yith-woocommerce-wishlist'                => array(
		'title'       => esc_html__( 'YITH WooCommerce Wishlist', 'helion' ),
		'required'    => false,
		'logo'        => 'logo.jpg',
		'group'       => $helion_theme_required_plugins_groups['ecommerce'],
	),
 	'mailchimp-for-wp'           => array(
		'title'       => esc_html__( 'MailChimp for WP', 'helion' ),
		'description' => esc_html__( "Allows visitors to subscribe to newsletters", 'helion' ),
		'required'    => false,
		'logo'        => 'mailchimp-for-wp.png',
		'group'       => $helion_theme_required_plugins_groups['socials'],
	),
 	'contact-form-7'             => array(
		'title'       => esc_html__( 'Contact Form 7', 'helion' ),
		'description' => esc_html__( "CF7 allows you to create an unlimited number of contact forms", 'helion' ),
		'required'    => false,
		'logo'        => 'contact-form-7.png',
		'group'       => $helion_theme_required_plugins_groups['content'],
	),
	'essential-grid'             => array(
		'title'       => esc_html__( 'Essential Grid', 'helion' ),
		'description' => '',
		'required'    => false,
		'logo'        => 'essential-grid.png',
		'group'       => $helion_theme_required_plugins_groups['content'],
	),
	'revslider'                  => array(
		'title'       => esc_html__( 'Revolution Slider', 'helion' ),
		'description' => '',
		'required'    => false,
		'logo'        => 'revslider.png',
		'group'       => $helion_theme_required_plugins_groups['content'],
	),
	'sitepress-multilingual-cms' => array(
		'title'       => esc_html__( 'WPML - Sitepress Multilingual CMS', 'helion' ),
		'description' => esc_html__( "Allows you to make your website multilingual", 'helion' ),
		'required'    => false,
		'install'     => false,      // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'sitepress-multilingual-cms.png',
		'group'       => $helion_theme_required_plugins_groups['content'],
	),
	'wp-gdpr-compliance'         => array(
		'title'       => esc_html__( 'WP GDPR Compliance', 'helion' ),
		'description' => esc_html__( "Allow visitors to decide for themselves what personal data they want to store on your site", 'helion' ),
		'required'    => false,
		'logo'        => 'wp-gdpr-compliance.png',
		'group'       => $helion_theme_required_plugins_groups['other'],
	),
    'trx_popup'                  => array(
        'title'       => esc_html__( 'ThemeREX Popup', 'helion' ),
        'description' => esc_html__( "Add popup to your site.", 'helion' ),
        'required'    => false,
        'logo'        => 'trx_popup.png',
        'group'       => $helion_theme_required_plugins_groups['other'],
    ),
    'trx_updater'                => array(
        'title'       => esc_html__( 'ThemeREX Updater', 'helion' ),
        'description' => esc_html__( "Update theme and theme-specific plugins from developer's upgrade server.", 'helion' ),
        'required'    => false,
        'logo'        => 'trx_updater.png',
        'group'       => $helion_theme_required_plugins_groups['other'],
    ),
);

if ( HELION_THEME_FREE ) {
	unset( $helion_theme_required_plugins['js_composer'] );
	unset( $helion_theme_required_plugins['vc-extensions-bundle'] );
	unset( $helion_theme_required_plugins['easy-digital-downloads'] );
	unset( $helion_theme_required_plugins['give'] );
	unset( $helion_theme_required_plugins['bbpress'] );
	unset( $helion_theme_required_plugins['booked'] );
	unset( $helion_theme_required_plugins['content_timeline'] );
	unset( $helion_theme_required_plugins['mp-timetable'] );
	unset( $helion_theme_required_plugins['learnpress'] );
	unset( $helion_theme_required_plugins['the-events-calendar'] );
	unset( $helion_theme_required_plugins['calculated-fields-form'] );
	unset( $helion_theme_required_plugins['essential-grid'] );
	unset( $helion_theme_required_plugins['revslider'] );
	unset( $helion_theme_required_plugins['ubermenu'] );
	unset( $helion_theme_required_plugins['sitepress-multilingual-cms'] );
	unset( $helion_theme_required_plugins['envato-market'] );
}

// Add plugins list to the global storage
$GLOBALS['HELION_STORAGE']['required_plugins'] = $helion_theme_required_plugins;



// THEME-SPECIFIC BLOG LAYOUTS
//----------------------------------------------
$helion_theme_blog_styles = array(
	'excerpt' => array(
		'title'   => esc_html__( 'Standard', 'helion' ),
		'archive' => 'index-excerpt',
		'item'    => 'content-excerpt',
		'styles'  => 'excerpt',
	),
	'plain' => array(
		'title'   => esc_html__( 'Plain', 'helion' ),
		'archive' => 'index-plain',
		'item'    => 'content-plain',
		'styles'  => 'plain',
	),
	'classic' => array(
		'title'   => esc_html__( 'Classic', 'helion' ),
		'archive' => 'index-classic',
		'item'    => 'content-classic',
		'columns' => array( 2, 3 ),
		'styles'  => 'classic',
	),
);
if ( ! HELION_THEME_FREE ) {
	$helion_theme_blog_styles['masonry']   = array(
		'title'   => esc_html__( 'Masonry', 'helion' ),
		'archive' => 'index-classic',
		'item'    => 'content-classic',
		'columns' => array( 2, 3 ),
		'styles'  => 'masonry',
	);
	$helion_theme_blog_styles['portfolio'] = array(
		'title'   => esc_html__( 'Portfolio', 'helion' ),
		'archive' => 'index-portfolio',
		'item'    => 'content-portfolio',
		'columns' => array( 2, 3, 4 ),
		'styles'  => 'portfolio',
	);
	$helion_theme_blog_styles['gallery']   = array(
		'title'   => esc_html__( 'Gallery', 'helion' ),
		'archive' => 'index-portfolio',
		'item'    => 'content-portfolio-gallery',
		'columns' => array( 2, 3, 4 ),
		'styles'  => array( 'portfolio', 'gallery' ),
	);
	$helion_theme_blog_styles['chess']     = array(
		'title'   => esc_html__( 'Chess', 'helion' ),
		'archive' => 'index-chess',
		'item'    => 'content-chess',
		'columns' => array( 1, 2, 3 ),
		'styles'  => 'chess',
	);
}

// Add list of blog styles to the global storage
$GLOBALS['HELION_STORAGE']['blog_styles'] = $helion_theme_blog_styles;



// THEME-SPECIFIC SINGLE POST LAYOUTS
//----------------------------------------------
$helion_theme_single_styles = array(
	'in-above'   => array(
		'title'  => esc_html__( 'The image inside the content area, the title above image', 'helion' ),
		'styles' => 'in-above',
	),
	'in-below'   => array(
		'title'  => esc_html__( 'The image inside the content area, the title below image', 'helion' ),
		'styles' => 'in-below',
	),
	'in-over'    => array(
		'title'  => esc_html__( 'The image inside the content area, the title over image', 'helion' ),
		'styles' => 'in-over',
	),
	'in-sticky'  => array(
		'title'  => esc_html__( 'The image inside the content area, the title is stick at the bottom side of the image', 'helion' ),
		'styles' => 'in-sticky',
	),
	'out-below-boxed'  => array(
		'title'  => esc_html__( 'Boxed image above the content area, the title below image', 'helion' ),
		'styles' => 'out-below-boxed',
	),
	'out-over-boxed'   => array(
		'title'  => esc_html__( 'Boxed image above the content area, the title over image', 'helion' ),
		'styles' => 'out-over-boxed',
	),
	'out-sticky-boxed' => array(
		'title'  => esc_html__( 'Boxed image above the content area, the title is stick at the bottom side of the image', 'helion' ),
		'styles' => 'out-sticky-boxed',
	),
	'out-below-fullwidth'  => array(
		'title'  => esc_html__( 'Fullwidth image above the content area, the title below image', 'helion' ),
		'styles' => 'out-below-fullwidth',
	),
	'out-over-fullwidth'   => array(
		'title'  => esc_html__( 'Fullwidth image above the content area, the title over image', 'helion' ),
		'styles' => 'out-over-fullwidth',
	),
	'out-sticky-fullwidth' => array(
		'title'  => esc_html__( 'Fullwidth image above the content area, the title is stick at the bottom side of the image', 'helion' ),
		'styles' => 'out-sticky-fullwidth',
	),
);

// Add list of single post styles to the global storage
$GLOBALS['HELION_STORAGE']['single_styles'] = $helion_theme_single_styles;


// Theme init priorities:
// Action 'after_setup_theme'
// 1 - register filters to add/remove lists items in the Theme Options
// 2 - create Theme Options
// 3 - add/remove Theme Options elements
// 5 - load Theme Options. Attention! After this step you can use only basic options (not overriden)
// 9 - register other filters (for installer, etc.)
//10 - standard Theme init procedures (not ordered)
// Action 'wp_loaded'
// 1 - detect override mode. Attention! Only after this step you can use overriden options (separate values for the shop, courses, etc.)

if ( ! function_exists( 'helion_customizer_theme_setup1' ) ) {
	add_action( 'after_setup_theme', 'helion_customizer_theme_setup1', 1 );
	function helion_customizer_theme_setup1() {

		// -----------------------------------------------------------------
		// -- ONLY FOR PROGRAMMERS, NOT FOR CUSTOMER
		// -- Internal theme settings
		// -----------------------------------------------------------------
		helion_storage_set(
			'settings', array(

				'duplicate_options'       => 'child',                   // none  - use separate options for the main and the child-theme
																		// child - duplicate theme options from the main theme to the child-theme only
																		// both  - sinchronize changes in the theme options between main and child themes

				'customize_refresh'       => 'auto',                    // Refresh method for preview area in the Appearance - Customize:
																		// auto - refresh preview area on change each field with Theme Options
																		// manual - refresh only obn press button 'Refresh' at the top of Customize frame

				'options_tabs_position'   => 'vertical',                // Position of tabs in the Theme and ThemeREX Addons options

				'allow_subtabs'           => true,						// Display sections as subtabs of panels in the Theme Options.
																		// If false - show sections as accordion.

				'max_load_fonts'          => 5,                         // Max fonts number to load from Google fonts or from uploaded fonts

				'comment_after_name'      => true,                      // Place 'comment' field after the 'name' and 'email'

				'show_author_avatar'      => true,                      // Display author's avatar in the post meta

				'icons_selector'          => 'internal',                // Icons selector in the shortcodes:
																		// internal - internal popup with plugin's or theme's icons list (fast and support images and svg)

				'icons_type'              => 'icons',                   // Type of icons (if 'icons_selector' is 'internal'):
																		// icons  - use font icons to present icons
																		// images - use images from theme's folder trx_addons/css/icons.png
																		// svg    - use svg from theme's folder trx_addons/css/icons.svg

				'socials_type'            => 'icons',                   // Type of socials icons (if 'icons_selector' is 'internal'):
																		// icons  - use font icons to present social networks
																		// images - use images from theme's folder trx_addons/css/icons.png
																		// svg    - use svg from theme's folder trx_addons/css/icons.svg

				'check_min_version'       => true,                      // Check if exists a .min version of .css and .js and return path to it
																		// instead the path to the original file
																		// (if debug_mode is on and modification time of the original file < time of the .min file)

				'autoselect_menu'         => false,                     // Show any menu if no menu selected in the location 'main_menu'
																		// (for example, the theme is just activated)

				'disable_jquery_ui'       => false,                     // Prevent loading custom jQuery UI libraries in the third-party plugins

				'use_mediaelements'       => true,                      // Load script "Media Elements" to play video and audio

				'tgmpa_upload'            => false,                     // Allow upload not pre-packaged plugins via TGMPA

				'allow_no_image'          => false,                     // Allow to use theme-specific image placeholder if no image present in the blog, related posts, post navigation, etc.

				'separate_schemes'        => true,                      // Save color schemes to the separate files __color_xxx.css (true) or append its to the __custom.css (false)

				'allow_fullscreen'        => false,                     // Allow cases 'fullscreen' and 'fullwide' for the body style in the Theme Options
																		// In the Page Options this styles are present always
																		// (can be removed if filter 'helion_filter_allow_fullscreen' return false)

				'attachments_navigation'  => false,                     // Add arrows on the single attachment page to navigate to the prev/next attachment

				'gutenberg_safe_mode'     => array(),                   // 'vc', 'elementor' - Prevent simultaneous editing of posts for Gutenberg and other PageBuilders (VC, Elementor)

				'gutenberg_add_context'   => false,                     // Add context to the Gutenberg editor styles with our method (if true - use if any problem with editor styles) or use native Gutenberg way via add_editor_style() (if false - used by default)

				'modify_gutenberg_blocks' => true,                      // Modify core blocks - add our parameters and classes

				'allow_gutenberg_blocks'  => true,                      // Allow our shortcodes and widgets as blocks in the Gutenberg (not ready yet - in the development now)

				'subtitle_above_title'    => true,                      // Put subtitle above the title in the shortcodes

				'add_hide_on_xxx'         => 'replace',                 // Add our breakpoints to the Responsive section of each element
																		// 'add' - add our breakpoints after Elementor's
																		// 'replace' - add our breakpoints instead Elementor's
																		// 'none' - don't add our breakpoints (using only Elementor's)
			)
		);

		// -----------------------------------------------------------------
		// -- Theme fonts (Google and/or custom fonts)
		// -----------------------------------------------------------------

		// Fonts to load when theme start
		// It can be Google fonts or uploaded fonts, placed in the folder /css/font-face/font-name inside the theme folder
		// Attention! Font's folder must have name equal to the font's name, with spaces replaced on the dash '-'
		
		helion_storage_set(
			'load_fonts', array(
                // Font-face packed with theme
                array(
                    'name'   => 'HKGrotesk',
                    'family' => 'sans-serif',
                ),
                array(
                    'name'   => 'LouisGeorgeCafe',
                    'family' => 'sans-serif',
                ),
			)
		);

		// Characters subset for the Google fonts. Available values are: latin,latin-ext,cyrillic,cyrillic-ext,greek,greek-ext,vietnamese
		helion_storage_set( 'load_fonts_subset', 'latin,latin-ext' );

		// Settings of the main tags
		// Attention! Font name in the parameter 'font-family' will be enclosed in the quotes and no spaces after comma!
		
		
		

        helion_storage_set(
            'theme_fonts', array(
                'p'       => array(
                    'title'           => esc_html__( 'Main text', 'helion' ),
                    'description'     => esc_html__( 'Font settings of the main text of the site. Attention! For correct display of the site on mobile devices, use only units "rem", "em" or "ex"', 'helion' ),
                    'font-family'     => '"HKGrotesk",sans-serif',
                    'font-size'       => '1rem',
                    'font-weight'     => '400',
                    'font-style'      => 'normal',
                    'line-height'     => '1.4857em',
                    'text-decoration' => 'none',
                    'text-transform'  => 'none',
                    'letter-spacing'  => '',
                    'margin-top'      => '0em',
                    'margin-bottom'   => '1.5em',
                ),
                'h1'      => array(
                    'title'           => esc_html__( 'Heading 1', 'helion' ),
                    'font-family'     => '"LouisGeorgeCafe",sans-serif',
                    'font-size'       => '2.737em',
                    'font-weight'     => '700',
                    'font-style'      => 'normal',
                    'line-height'     => '1em',
                    'text-decoration' => 'none',
                    'text-transform'  => 'none',
                    'letter-spacing'  => '0px',
                    'margin-top'      => '1.6917em',
                    'margin-bottom'   => '0.6223em',
                ),
                'h2'      => array(
                    'title'           => esc_html__( 'Heading 2', 'helion' ),
                    'font-family'     => '"LouisGeorgeCafe",sans-serif',
                    'font-size'       => '2.368em',
                    'font-weight'     => '700',
                    'font-style'      => 'normal',
                    'line-height'     => '1.0412em',
                    'text-decoration' => 'none',
                    'text-transform'  => 'none',
                    'letter-spacing'  => '0px',
                    'margin-top'      => '1.272em',
                    'margin-bottom'   => '0.4619em',
                ),
                'h3'      => array(
                    'title'           => esc_html__( 'Heading 3', 'helion' ),
                    'font-family'     => '"LouisGeorgeCafe",sans-serif',
                    'font-size'       => '1.895em',
                    'font-weight'     => '700',
                    'font-style'      => 'normal',
                    'line-height'     => '1.1225em',
                    'text-decoration' => 'none',
                    'text-transform'  => 'none',
                    'letter-spacing'  => '0px',
                    'margin-top'      => '1.2645em',
                    'margin-bottom'   => '0.6255em',
                ),
                'h4'      => array(
                    'title'           => esc_html__( 'Heading 4', 'helion' ),
                    'font-family'     => '"LouisGeorgeCafe",sans-serif',
                    'font-size'       => '1.474em',
                    'font-weight'     => '700',
                    'font-style'      => 'normal',
                    'line-height'     => '1.0677em',
                    'text-decoration' => 'none',
                    'text-transform'  => 'none',
                    'letter-spacing'  => '0px',
                    'margin-top'      => '1.7623em',
                    'margin-bottom'   => '0.8558em',
                ),
                'h5'      => array(
                    'title'           => esc_html__( 'Heading 5', 'helion' ),
                    'font-family'     => '"LouisGeorgeCafe",sans-serif',
                    'font-size'       => '1.105em',
                    'font-weight'     => '700',
                    'font-style'      => 'normal',
                    'line-height'     => '1.305em',
                    'text-decoration' => 'none',
                    'text-transform'  => 'none',
                    'letter-spacing'  => '0px',
                    'margin-top'      => '2.2em',
                    'margin-bottom'   => '1.208em',
                ),
                'h6'      => array(
                    'title'           => esc_html__( 'Heading 6', 'helion' ),
                    'font-family'     => '"LouisGeorgeCafe",sans-serif',
                    'font-size'       => '1em',
                    'font-weight'     => '700',
                    'font-style'      => 'normal',
                    'line-height'     => '1.2em',
                    'text-decoration' => 'none',
                    'text-transform'  => 'none',
                    'letter-spacing'  => '0px',
                    'margin-top'      => '2.5644em',
                    'margin-bottom'   => '1.225em',
                ),
                'logo'    => array(
                    'title'           => esc_html__( 'Logo text', 'helion' ),
                    'description'     => esc_html__( 'Font settings of the text case of the logo', 'helion' ),
                    'font-family'     => '"LouisGeorgeCafe",sans-serif',
                    'font-size'       => '1.895em',
                    'font-weight'     => '700',
                    'font-style'      => 'normal',
                    'line-height'     => '1.25em',
                    'text-decoration' => 'none',
                    'text-transform'  => 'uppercase',
                    'letter-spacing'  => '0px',
                ),
                'button'  => array(
                    'title'           => esc_html__( 'Buttons', 'helion' ),
                    'font-family'     => '"HKGrotesk",sans-serif',
                    'font-size'       => '17px',
                    'font-weight'     => '700',
                    'font-style'      => 'normal',
                    'line-height'     => '24px',
                    'text-decoration' => 'none',
                    'text-transform'  => 'none',
                    'letter-spacing'  => '0px',
                ),
                'input'   => array(
                    'title'           => esc_html__( 'Input fields', 'helion' ),
                    'description'     => esc_html__( 'Font settings of the input fields, dropdowns and textareas', 'helion' ),
                    'font-family'     => 'inherit',
                    'font-size'       => '16px',
                    'font-weight'     => '400',
                    'font-style'      => 'normal',
                    'line-height'     => '1.5em', // Attention! Firefox don't allow line-height less then 1.5em in the select
                    'text-decoration' => 'none',
                    'text-transform'  => 'none',
                    'letter-spacing'  => '0px',
                ),
                'info'    => array(
                    'title'           => esc_html__( 'Post meta', 'helion' ),
                    'description'     => esc_html__( 'Font settings of the post meta: date, counters, share, etc.', 'helion' ),
                    'font-family'     => 'inherit',
                    'font-size'       => '14px',  // Old value '13px' don't allow using 'font zoom' in the custom blog items
                    'font-weight'     => '400',
                    'font-style'      => 'normal',
                    'line-height'     => '1.5em',
                    'text-decoration' => 'none',
                    'text-transform'  => 'none',
                    'letter-spacing'  => '0px',
                    'margin-top'      => '',
                    'margin-bottom'   => '2.15em',
                ),
                'menu'    => array(
                    'title'           => esc_html__( 'Main menu', 'helion' ),
                    'description'     => esc_html__( 'Font settings of the main menu items', 'helion' ),
                    'font-family'     => '"HKGrotesk",sans-serif',
                    'font-size'       => '18px',
                    'font-weight'     => '700',
                    'font-style'      => 'normal',
                    'line-height'     => '1.5em',
                    'text-decoration' => 'none',
                    'text-transform'  => 'none',
                    'letter-spacing'  => '0px',
                ),
                'submenu' => array(
                    'title'           => esc_html__( 'Dropdown menu', 'helion' ),
                    'description'     => esc_html__( 'Font settings of the dropdown menu items', 'helion' ),
                    'font-family'     => '"HKGrotesk",sans-serif',
                    'font-size'       => '16px',
                    'font-weight'     => '700',
                    'font-style'      => 'normal',
                    'line-height'     => '1.5em',
                    'text-decoration' => 'none',
                    'text-transform'  => 'none',
                    'letter-spacing'  => '0px',
                ),
            )
        );

		// -----------------------------------------------------------------
		// -- Theme colors for customizer
		// -- Attention! Inner scheme must be last in the array below
		// -----------------------------------------------------------------
		helion_storage_set(
			'scheme_color_groups', array(
				'main'    => array(
					'title'       => esc_html__( 'Main', 'helion' ),
					'description' => esc_html__( 'Colors of the main content area', 'helion' ),
				),
				'alter'   => array(
					'title'       => esc_html__( 'Alter', 'helion' ),
					'description' => esc_html__( 'Colors of the alternative blocks (sidebars, etc.)', 'helion' ),
				),
				'extra'   => array(
					'title'       => esc_html__( 'Extra', 'helion' ),
					'description' => esc_html__( 'Colors of the extra blocks (dropdowns, price blocks, table headers, etc.)', 'helion' ),
				),
				'inverse' => array(
					'title'       => esc_html__( 'Inverse', 'helion' ),
					'description' => esc_html__( 'Colors of the inverse blocks - when link color used as background of the block (dropdowns, blockquotes, etc.)', 'helion' ),
				),
				'input'   => array(
					'title'       => esc_html__( 'Input', 'helion' ),
					'description' => esc_html__( 'Colors of the form fields (text field, textarea, select, etc.)', 'helion' ),
				),
			)
		);
		helion_storage_set(
			'scheme_color_names', array(
				'bg_color'    => array(
					'title'       => esc_html__( 'Background color', 'helion' ),
					'description' => esc_html__( 'Background color of this block in the normal state', 'helion' ),
				),
				'bg_hover'    => array(
					'title'       => esc_html__( 'Background hover', 'helion' ),
					'description' => esc_html__( 'Background color of this block in the hovered state', 'helion' ),
				),
				'bd_color'    => array(
					'title'       => esc_html__( 'Border color', 'helion' ),
					'description' => esc_html__( 'Border color of this block in the normal state', 'helion' ),
				),
				'bd_hover'    => array(
					'title'       => esc_html__( 'Border hover', 'helion' ),
					'description' => esc_html__( 'Border color of this block in the hovered state', 'helion' ),
				),
				'text'        => array(
					'title'       => esc_html__( 'Text', 'helion' ),
					'description' => esc_html__( 'Color of the plain text inside this block', 'helion' ),
				),
				'text_dark'   => array(
					'title'       => esc_html__( 'Text dark', 'helion' ),
					'description' => esc_html__( 'Color of the dark text (bold, header, etc.) inside this block', 'helion' ),
				),
				'text_light'  => array(
					'title'       => esc_html__( 'Text light', 'helion' ),
					'description' => esc_html__( 'Color of the light text (post meta, etc.) inside this block', 'helion' ),
				),
				'text_link'   => array(
					'title'       => esc_html__( 'Link', 'helion' ),
					'description' => esc_html__( 'Color of the links inside this block', 'helion' ),
				),
				'text_hover'  => array(
					'title'       => esc_html__( 'Link hover', 'helion' ),
					'description' => esc_html__( 'Color of the hovered state of links inside this block', 'helion' ),
				),
				'text_link2'  => array(
					'title'       => esc_html__( 'Link 2', 'helion' ),
					'description' => esc_html__( 'Color of the accented texts (areas) inside this block', 'helion' ),
				),
				'text_hover2' => array(
					'title'       => esc_html__( 'Link 2 hover', 'helion' ),
					'description' => esc_html__( 'Color of the hovered state of accented texts (areas) inside this block', 'helion' ),
				),
				'text_link3'  => array(
					'title'       => esc_html__( 'Link 3', 'helion' ),
					'description' => esc_html__( 'Color of the other accented texts (buttons) inside this block', 'helion' ),
				),
				'text_hover3' => array(
					'title'       => esc_html__( 'Link 3 hover', 'helion' ),
					'description' => esc_html__( 'Color of the hovered state of other accented texts (buttons) inside this block', 'helion' ),
				),
			)
		);
        $schemes = array(

            // Color scheme: 'default'
            'default' => array(
                'title'    => esc_html__( 'Default', 'helion' ),
                'internal' => true,
                'colors'   => array(

                    // Whole block border and background
                    'bg_color'         => '#ffffff', //ok
                    'bd_color'         => '#e3e3e3', //ok

                    // Text and links colors
                    'text'             => '#4a4f55', //ok
                    'text_light'       => '#898b8c', //ok
                    'text_dark'        => '#0d0d12', //ok
                    'text_link'        => '#e93314', //ok red
                    'text_hover'       => '#c32206', //ok dark red
                    'text_link2'       => '#f8632e', //ok orange
                    'text_hover2'      => '#d84713', //ok dark orange
                    'text_link3'       => '#0d0d12', //ok dark
                    'text_hover3'      => '#e93314', //ok red

                    // Alternative blocks (sidebar, tabs, alternative blocks, etc.)
                    'alter_bg_color'   => '#f8f5f2', //ok
                    'alter_bg_hover'   => '#f0edea', //ok
                    'alter_bd_color'   => '#e3e3e3', //ok
                    'alter_bd_hover'   => '#b4b4b4', //ok
                    'alter_text'       => '#4a4f55', //ok
                    'alter_light'      => '#898b8c', //ok
                    'alter_dark'       => '#0d0d12', //ok
                    'alter_link'       => '#e93314', //ok red
                    'alter_hover'      => '#c32206', //ok dark red
                    'alter_link2'      => '#f8632e', //ok orange
                    'alter_hover2'     => '#d84713', //ok dark orange
                    'alter_link3'      => '#0d0d12', //ok dark
                    'alter_hover3'     => '#e93314', //ok red

                    // Extra blocks (submenu, tabs, color blocks, etc.)
                    'extra_bg_color'   => '#0d0d12', //ok
                    'extra_bg_hover'   => '#2a2a2f', //ok
                    'extra_bd_color'   => '#e3e3e3', //ok
                    'extra_bd_hover'   => '#ffffff', //ok
                    'extra_text'       => '#aeaeae', //ok
                    'extra_light'      => '#6d7277', //ok
                    'extra_dark'       => '#ffffff', //ok
                    'extra_link'       => '#aeaeae', //ok
                    'extra_hover'      => '#e93314', //ok red
                    'extra_link2'      => '#80d572',
                    'extra_hover2'     => '#8be77c',
                    'extra_link3'      => '#ddb837',
                    'extra_hover3'     => '#eec432',

                    // Input fields (form's fields and textarea)
                    'input_bg_color'   => '#ffffff', //ok
                    'input_bg_hover'   => '#ffffff', //ok
                    'input_bd_color'   => '#e3e3e3', //ok
                    'input_bd_hover'   => '#b4b4b4', //ok
                    'input_text'       => '#4a4f55', //ok
                    'input_light'      => '#898b8c', //ok
                    'input_dark'       => '#0d0d12', //ok

                    // Inverse blocks (text and links on the 'text_link' background)
                    'inverse_bd_color' => '#67bcc1',
                    'inverse_bd_hover' => '#5aa4a9',
                    'inverse_text'     => '#4a4f55', //ok
                    'inverse_light'    => '#6d7277', //ok
                    'inverse_dark'     => '#0d0d12', //ok
                    'inverse_link'     => '#ffffff', //ok
                    'inverse_hover'    => '#ffffff', //ok
                ),
            ),

            // Color scheme: 'dark'
            'dark'    => array(
                'title'    => esc_html__( 'Dark', 'helion' ),
                'internal' => true,
                'colors'   => array(

                    // Whole block border and background
                    'bg_color'         => '#0d0d12', //ok
                    'bd_color'         => '#2a2a2f', //ok

                    // Text and links colors
                    'text'             => '#aeaeae', //ok
                    'text_light'       => '#898b8c', //ok
                    'text_dark'        => '#ffffff', //ok
                    'text_link'        => '#e93314', //ok red
                    'text_hover'       => '#c32206', //ok dark red
                    'text_link2'       => '#f8632e', //ok orange
                    'text_hover2'      => '#d84713', //ok dark orange
                    'text_link3'       => '#ffffff', //ok light
                    'text_hover3'      => '#e93314', //ok red

                    // Alternative blocks (sidebar, tabs, alternative blocks, etc.)
                    'alter_bg_color'   => '#1a1a1f', //ok
                    'alter_bg_hover'   => '#1e1e22', //ok
                    'alter_bd_color'   => '#2a2a2f', //ok
                    'alter_bd_hover'   => '#424248', //ok
                    'alter_text'       => '#aeaeae', //ok
                    'alter_light'      => '#898b8c', //ok
                    'alter_dark'       => '#ffffff', //ok
                    'alter_link'       => '#e93314', //ok red
                    'alter_hover'      => '#c32206', //ok dark red
                    'alter_link2'      => '#f8632e', //ok orange
                    'alter_hover2'     => '#d84713', //ok dark orange
                    'alter_link3'      => '#ffffff', //ok light
                    'alter_hover3'     => '#e93314', //ok red

                    // Extra blocks (submenu, tabs, color blocks, etc.)
                    'extra_bg_color'   => '#ffffff', //ok
                    'extra_bg_hover'   => '#e3e3e3', //ok
                    'extra_bd_color'   => '#2a2a2f', //ok
                    'extra_bd_hover'   => '#ffffff', //ok
                    'extra_text'       => '#6d7277', //ok
                    'extra_light'      => '#aeaeae', //ok
                    'extra_dark'       => '#ffffff', //ok
                    'extra_link'       => '#4a4f55', //ok
                    'extra_hover'      => '#e93314', //ok red
                    'extra_link2'      => '#80d572',
                    'extra_hover2'     => '#8be77c',
                    'extra_link3'      => '#ddb837',
                    'extra_hover3'     => '#eec432',

                    // Input fields (form's fields and textarea)
                    'input_bg_color'   => '#0d0d12', //ok
                    'input_bg_hover'   => '#0d0d12', //ok
                    'input_bd_color'   => '#2a2a2f', //ok
                    'input_bd_hover'   => '#424248', //ok
                    'input_text'       => '#898b8c', //ok
                    'input_light'      => '#aeaeae', //ok
                    'input_dark'       => '#ffffff', //ok

                    // Inverse blocks (text and links on the 'text_link' background)
                    'inverse_bd_color' => '#e36650',
                    'inverse_bd_hover' => '#cb5b47',
                    'inverse_text'     => '#4a4f55', //ok
                    'inverse_light'    => '#ffffff', //ok
                    'inverse_dark'     => '#0d0d12', //ok
                    'inverse_link'     => '#ffffff', //ok
                    'inverse_hover'    => '#0d0d12', //ok
                ),
            ),
        );
        helion_storage_set( 'schemes', $schemes );
        helion_storage_set( 'schemes_original', $schemes );

        // Simple scheme editor: lists the colors to edit in the "Simple" mode.
        // For each color you can set the array of 'slave' colors and brightness factors that are used to generate new values,
        // when 'main' color is changed
        // Leave 'slave' arrays empty if your scheme does not have a color dependency
        helion_storage_set(
            'schemes_simple', array(
                'text_link'        => array(),
                'text_hover'       => array(),
                'text_link2'       => array(),
                'text_hover2'      => array(),
                'text_link3'       => array(),
                'text_hover3'      => array(),

                'alter_link'       => array(),
                'alter_hover'      => array(),
                'alter_link2'      => array(),
                'alter_hover2'     => array(),
                'alter_link3'      => array(),
                'alter_hover3'     => array(),
                'extra_link'       => array(),
                'extra_hover'      => array(),
                'extra_link2'      => array(),
                'extra_hover2'     => array(),
                'extra_link3'      => array(),
                'extra_hover3'     => array(),
                'inverse_bd_color' => array(),
                'inverse_bd_hover' => array(),
            )
        );

        // Additional colors for each scheme
        // Parameters:	'color' - name of the color from the scheme that should be used as source for the transformation
        //				'alpha' - to make color transparent (0.0 - 1.0)
        //				'hue', 'saturation', 'brightness' - inc/dec value for each color's component
        helion_storage_set(
            'scheme_colors_add', array(
                'bg_color_0'        => array(
                    'color' => 'bg_color',
                    'alpha' => 0,
                ),
                'bg_color_02'       => array(
                    'color' => 'bg_color',
                    'alpha' => 0.2,
                ),
                'bg_color_07'       => array(
                    'color' => 'bg_color',
                    'alpha' => 0.7,
                ),
                'bg_color_08'       => array(
                    'color' => 'bg_color',
                    'alpha' => 0.8,
                ),
                'bg_color_09'       => array(
                    'color' => 'bg_color',
                    'alpha' => 0.9,
                ),
                'alter_bg_color_07' => array(
                    'color' => 'alter_bg_color',
                    'alpha' => 0.7,
                ),
                'alter_bg_color_04' => array(
                    'color' => 'alter_bg_color',
                    'alpha' => 0.4,
                ),
                'alter_bg_color_00' => array(
                    'color' => 'alter_bg_color',
                    'alpha' => 0,
                ),
                'alter_bg_color_02' => array(
                    'color' => 'alter_bg_color',
                    'alpha' => 0.2,
                ),
                'alter_bd_color_02' => array(
                    'color' => 'alter_bd_color',
                    'alpha' => 0.2,
                ),
                'alter_dark_01'     => array(
                    'color' => 'alter_dark',
                    'alpha' => 0.1,
                ),
                'alter_dark_02'     => array(
                    'color' => 'alter_dark',
                    'alpha' => 0.2,
                ),
                'alter_link_02'     => array(
                    'color' => 'alter_link',
                    'alpha' => 0.2,
                ),
                'alter_link_07'     => array(
                    'color' => 'alter_link',
                    'alpha' => 0.7,
                ),
                'extra_bg_color_05' => array(
                    'color' => 'extra_bg_color',
                    'alpha' => 0.5,
                ),
                'extra_bg_color_07' => array(
                    'color' => 'extra_bg_color',
                    'alpha' => 0.7,
                ),
                'extra_link_02'     => array(
                    'color' => 'extra_link',
                    'alpha' => 0.2,
                ),
                'extra_link_07'     => array(
                    'color' => 'extra_link',
                    'alpha' => 0.7,
                ),
                'text_dark_005'      => array(
                    'color' => 'text_dark',
                    'alpha' => 0.05,
                ),
                'text_dark_01'      => array(
                    'color' => 'text_dark',
                    'alpha' => 0.1,
                ),
                'text_dark_02'      => array(
                    'color' => 'text_dark',
                    'alpha' => 0.2,
                ),
                'text_dark_06'      => array(
                    'color' => 'text_dark',
                    'alpha' => 0.6,
                ),
                'text_dark_07'      => array(
                    'color' => 'text_dark',
                    'alpha' => 0.7,
                ),
                'text_dark_08'      => array(
                    'color' => 'text_dark',
                    'alpha' => 0.8,
                ),
                'text_link_02'      => array(
                    'color' => 'text_link',
                    'alpha' => 0.2,
                ),
                'text_link_07'      => array(
                    'color' => 'text_link',
                    'alpha' => 0.7,
                ),
                'inverse_link_00'      => array(
                    'color' => 'inverse_link',
                    'alpha' => 0,
                ),
                'inverse_link_05'      => array(
                    'color' => 'inverse_link',
                    'alpha' => 0.5,
                ),
                'inverse_link_08'      => array(
                    'color' => 'inverse_link',
                    'alpha' => 0.8,
                ),
                'inverse_dark_012'      => array(
                    'color' => 'inverse_dark',
                    'alpha' => 0.12,
                ),
                'inverse_dark_06'      => array(
                    'color' => 'inverse_dark',
                    'alpha' => 0.5,
                ),
                'inverse_dark_08'      => array(
                    'color' => 'inverse_dark',
                    'alpha' => 0.8,
                ),
                'text_dark_blend'   => array(
                    'color'      => 'text_dark',
                    'hue'        => 3,
                    'saturation' => -7,
                    'brightness' => 7,
                ),
                'text_link_blend'   => array(
                    'color'      => 'text_link',
                    'hue'        => 2,
                    'saturation' => -5,
                    'brightness' => 5,
                ),
                'alter_link_blend'  => array(
                    'color'      => 'alter_link',
                    'hue'        => 2,
                    'saturation' => -5,
                    'brightness' => 5,
                ),
            )
        );

        // Parameters to set order of schemes in the css
        helion_storage_set(
            'schemes_sorted', array(
                'color_scheme',
                'header_scheme',
                'menu_scheme',
                'sidebar_scheme',
                'footer_scheme',
            )
        );

        // -----------------------------------------------------------------
        // -- Theme specific thumb sizes
        // -----------------------------------------------------------------
        helion_storage_set(
            'theme_thumbs', apply_filters(
                'helion_filter_add_thumb_sizes', array(
                    // Width of the image is equal to the content area width (without sidebar)
                    // Height is fixed
                    'helion-thumb-huge'        => array(
                        'size'  => array( 1170, 658, true ),
                        'title' => esc_html__( 'Huge image', 'helion' ),
                        'subst' => 'trx_addons-thumb-huge',
                    ),
                    // Width of the image is equal to the content area width (with sidebar)
                    // Height is fixed
                    'helion-thumb-big'         => array(
                        'size'  => array( 750, 422, true ),
                        'title' => esc_html__( 'Large image', 'helion' ),
                        'subst' => 'trx_addons-thumb-big',
                    ),

                    // Width of the image is equal to the 1/3 of the content area width (without sidebar)
                    // Height is fixed
                    'helion-thumb-med'         => array(
                        'size'  => array( 370, 208, true ),
                        'title' => esc_html__( 'Medium image', 'helion' ),
                        'subst' => 'trx_addons-thumb-medium',
                    ),

                    // Small square image (for avatars in comments, etc.)
                    'helion-thumb-tiny'        => array(
                        'size'  => array( 90, 90, true ),
                        'title' => esc_html__( 'Small square avatar', 'helion' ),
                        'subst' => 'trx_addons-thumb-tiny',
                    ),

                    // Width of the image is equal to the content area width (with sidebar)
                    // Height is proportional (only downscale, not crop)
                    'helion-thumb-masonry-big' => array(
                        'size'  => array( 750, 0, false ),     // Only downscale, not crop
                        'title' => esc_html__( 'Masonry Large (scaled)', 'helion' ),
                        'subst' => 'trx_addons-thumb-masonry-big',
                    ),

                    // Width of the image is equal to the 1/3 of the full content area width (without sidebar)
                    // Height is proportional (only downscale, not crop)
                    'helion-thumb-masonry'     => array(
                        'size'  => array( 370, 0, false ),     // Only downscale, not crop
                        'title' => esc_html__( 'Masonry (scaled)', 'helion' ),
                        'subst' => 'trx_addons-thumb-masonry',
                    ),

                    // Small square image  ( recent posts, blogger )
                    'helion-thumb-square'        => array(
                        'size'  => array( 480, 480, true ),
                        'title' => esc_html__( 'Small square image', 'helion' ),
                        'subst' => 'trx_addons-thumb-square',
                    ),
                    // Portrait image ( team )
                    'helion-thumb-portrait'        => array(
                        'size'  => array( 370, 393, true ),
                        'title' => esc_html__( 'Portrait image', 'helion' ),
                        'subst' => 'trx_addons-thumb-portrait',
                    ),
                    // Rectangle image ( blogger )
                    'helion-thumb-rectangle'        => array(
                        'size'  => array( 740, 580, true ),
                        'title' => esc_html__( 'Rectangle image', 'helion' ),
                        'subst' => 'trx_addons-thumb-rectangle',
                    ),

                )
            )
        );
    }
}


// -----------------------------------------------------------------
// -- Theme options for customizer
// -----------------------------------------------------------------
if ( ! function_exists( 'helion_create_theme_options' ) ) {

	function helion_create_theme_options() {

		// Message about options override.
		// Attention! Not need esc_html() here, because this message put in wp_kses_data() below
		$msg_override = __( 'Attention! Some of these options can be overridden in the following sections (Blog, Plugins settings, etc.) or in the settings of individual pages. If you changed such parameter and nothing happened on the page, this option may be overridden in the corresponding section or in the Page Options of this page. These options are marked with an asterisk (*) in the title.', 'helion' );

		// Color schemes number: if < 2 - hide fields with selectors
		$hide_schemes = count( helion_storage_get( 'schemes' ) ) < 2;

		helion_storage_set(

			'options', array(

				// 'Logo & Site Identity'
				//---------------------------------------------
				'title_tagline'                 => array(
					'title'    => esc_html__( 'Logo & Site Identity', 'helion' ),
					'desc'     => '',
					'priority' => 10,
					'icon'     => 'icon-home-2',
					'type'     => 'section',
				),
				'logo_info'                     => array(
					'title'    => esc_html__( 'Logo Settings', 'helion' ),
					'desc'     => '',
					'priority' => 20,
					'qsetup'   => esc_html__( 'General', 'helion' ),
					'type'     => 'info',
				),
				'logo_text'                     => array(
					'title'    => esc_html__( 'Use Site Name as Logo', 'helion' ),
					'desc'     => wp_kses_data( __( 'Use the site title and tagline as a text logo if no image is selected', 'helion' ) ),
					'priority' => 30,
					'std'      => 1,
					'qsetup'   => esc_html__( 'General', 'helion' ),
					'type'     => HELION_THEME_FREE ? 'hidden' : 'switch',
				),
				'logo_zoom'                     => array(
					'title'      => esc_html__( 'Logo zoom', 'helion' ),
					'desc'       => wp_kses( __( 'Zoom the logo (set 1 to leave original size). For this parameter to affect images, their max-height should be specified in "em" instead of "px" when creating a header. In this case maximum size of logo depends on the actual size of the picture.', 'helion' ),'helion_kses_content' ),
					'std'        => 1,
					'min'        => 0.2,
					'max'        => 2,
					'step'       => 0.1,
					'refresh'    => false,
					'show_value' => true,
					'type'       => HELION_THEME_FREE ? 'hidden' : 'slider',
				),
				'logo_retina_enabled'           => array(
					'title'    => esc_html__( 'Allow retina display logo', 'helion' ),
					'desc'     => wp_kses_data( __( 'Show fields to select logo images for Retina display', 'helion' ) ),
					'priority' => 40,
					'refresh'  => false,
					'std'      => 0,
					'type'     => HELION_THEME_FREE ? 'hidden' : 'switch',
				),
				// Parameter 'logo' was replaced with standard WordPress 'custom_logo'
				'logo_retina'                   => array(
					'title'      => esc_html__( 'Logo for Retina', 'helion' ),
					'desc'       => wp_kses_data( __( 'Select or upload site logo used on Retina displays (if empty - use default logo from the field above)', 'helion' ) ),
					'priority'   => 70,
					'dependency' => array(
						'logo_retina_enabled' => array( 1 ),
					),
					'std'        => '',
					'type'       => HELION_THEME_FREE ? 'hidden' : 'image',
				),
				'logo_mobile_header'            => array(
					'title' => esc_html__( 'Logo for the mobile header', 'helion' ),
					'desc'  => wp_kses_data( __( 'Select or upload site logo to display it in the mobile header (if enabled in the section "Header - Header mobile"', 'helion' ) ),
					'std'   => '',
					'type'  => 'image',
				),
				'logo_mobile_header_retina'     => array(
					'title'      => esc_html__( 'Logo for the mobile header on Retina', 'helion' ),
					'desc'       => wp_kses_data( __( 'Select or upload site logo used on Retina displays (if empty - use default logo from the field above)', 'helion' ) ),
					'dependency' => array(
						'logo_retina_enabled' => array( 1 ),
					),
					'std'        => '',
					'type'       => HELION_THEME_FREE ? 'hidden' : 'image',
				),
				'logo_mobile'                   => array(
					'title' => esc_html__( 'Logo for the mobile menu', 'helion' ),
					'desc'  => wp_kses_data( __( 'Select or upload site logo to display it in the mobile menu', 'helion' ) ),
					'std'   => '',
					'type'  => 'image',
				),
				'logo_mobile_retina'            => array(
					'title'      => esc_html__( 'Logo mobile on Retina', 'helion' ),
					'desc'       => wp_kses_data( __( 'Select or upload site logo used on Retina displays (if empty - use default logo from the field above)', 'helion' ) ),
					'dependency' => array(
						'logo_retina_enabled' => array( 1 ),
					),
					'std'        => '',
					'type'       => HELION_THEME_FREE ? 'hidden' : 'image',
				),
				'logo_side'                     => array(
					'title' => esc_html__( 'Logo for the side menu', 'helion' ),
					'desc'  => wp_kses_data( __( 'Select or upload site logo (with vertical orientation) to display it in the side menu', 'helion' ) ),
					'std'   => '',
					'type'  => 'image',
				),
				'logo_side_retina'              => array(
					'title'      => esc_html__( 'Logo for the side menu on Retina', 'helion' ),
					'desc'       => wp_kses_data( __( 'Select or upload site logo (with vertical orientation) to display it in the side menu on Retina displays (if empty - use default logo from the field above)', 'helion' ) ),
					'dependency' => array(
						'logo_retina_enabled' => array( 1 ),
					),
					'std'        => '',
					'type'       => HELION_THEME_FREE ? 'hidden' : 'image',
				),



				// 'General settings'
				//---------------------------------------------
				'general'                       => array(
					'title'    => esc_html__( 'General', 'helion' ),
					'desc'     => wp_kses_data( $msg_override ),
					'priority' => 20,
					'icon'     => 'icon-settings',
					'type'     => 'section',
				),

				'general_layout_info'           => array(
					'title'  => esc_html__( 'Layout', 'helion' ),
					'desc'   => '',
					'qsetup' => esc_html__( 'General', 'helion' ),
					'type'   => 'info',
				),
				'body_style'                    => array(
					'title'    => esc_html__( 'Body style', 'helion' ),
					'desc'     => wp_kses_data( __( 'Select width of the body content', 'helion' ) ),
					'override' => array(
						'mode'    => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Content', 'helion' ),
					),
					'qsetup'   => esc_html__( 'General', 'helion' ),
					'refresh'  => false,
					'std'      => 'wide',
					'options'  => helion_get_list_body_styles( false ),
					'type'     => 'select',
				),
                'body_bg_image_switch'                => array(
                    'title'    => esc_html__( 'Body background image', 'helion' ),
                    'desc'     => wp_kses_data( __( 'Show background image in the body', 'helion' ) ),
                    'override' => array(
                        'mode'    => 'page',
                        'section' => esc_html__( 'Content', 'helion' ),
                    ),
                    'refresh'  => false,
                    'std'      => 1,
                    'type'     => 'switch',
                ),
                'body_bg_image'                => array(
                    'title'      => esc_html__( 'Add body background image', 'helion' ),
                    'desc'       => wp_kses_data( __( 'Select or upload image, used as background in the body', 'helion' ) ),
                    'dependency' => array(
                        'body_bg_image_switch' => array( 1 ),
                    ),
                    'override'   => array(
                        'mode'    => 'page',
                        'section' => esc_html__( 'Content', 'helion' ),
                    ),
                    'std'        => '',
                    'type'       => 'image',
                ),
				'page_width'                    => array(
					'title'      => esc_html__( 'Page width', 'helion' ),
					'desc'       => wp_kses_data( __( 'Total width of the site content and sidebar (in pixels). If empty - use default width', 'helion' ) ),
					'dependency' => array(
						'body_style' => array( 'boxed', 'wide' ),
					),
					'std'        => 1170,
					'min'        => 1000,
					'max'        => 1600,
					'step'       => 10,
					'show_value' => true,
					'units'      => 'px',
					'refresh'    => false,
					'customizer' => 'page',               // SASS variable's name to preview changes 'on fly'
					'type'       => HELION_THEME_FREE ? 'hidden' : 'slider',
				),
				'page_boxed_extra'             => array(
					'title'      => esc_html__( 'Boxed page extra spaces', 'helion' ),
					'desc'       => wp_kses_data( __( 'Width of the extra side space on boxed pages', 'helion' ) ),
					'dependency' => array(
						'body_style' => array( 'boxed' ),
					),
					'std'        => 60,
					'min'        => 0,
					'max'        => 150,
					'step'       => 10,
					'show_value' => true,
					'units'      => 'px',
					'refresh'    => false,
					'customizer' => 'page_boxed_extra',   // SASS variable's name to preview changes 'on fly'
					'type'       => HELION_THEME_FREE ? 'hidden' : 'slider',
				),
				'boxed_bg_image'                => array(
					'title'      => esc_html__( 'Boxed bg image', 'helion' ),
					'desc'       => wp_kses_data( __( 'Select or upload image, used as background in the boxed body', 'helion' ) ),
					'dependency' => array(
						'body_style' => array( 'boxed' ),
					),
					'override'   => array(
						'mode'    => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Content', 'helion' ),
					),
					'std'        => '',
					'qsetup'     => esc_html__( 'General', 'helion' ),
					'type'       => 'image',
				),
				'remove_margins'                => array(
					'title'    => esc_html__( 'Remove margins', 'helion' ),
					'desc'     => wp_kses_data( __( 'Remove margins above and below the content area', 'helion' ) ),
					'override' => array(
						'mode'    => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Content', 'helion' ),
					),
					'refresh'  => false,
					'std'      => 0,
					'type'     => 'switch',
				),

				'general_sidebar_info'          => array(
					'title' => esc_html__( 'Sidebar', 'helion' ),
					'desc'  => '',
					'type'  => 'info',
				),
				'sidebar_position'              => array(
					'title'    => esc_html__( 'Sidebar position', 'helion' ),
					'desc'     => wp_kses_data( __( 'Select position to show sidebar', 'helion' ) ),
					'override' => array(
						'mode'    => 'page',		// Override parameters for single posts moved to the 'sidebar_position_single'
						'section' => esc_html__( 'Widgets', 'helion' ),
					),
					'std'      => 'right',
					'qsetup'   => esc_html__( 'General', 'helion' ),
					'options'  => array(),
					'type'     => 'radio',
				),
				'sidebar_position_ss'       => array(
					'title'    => esc_html__( 'Sidebar position on the small screen', 'helion' ),
					'desc'     => wp_kses_data( __( "Select position to move sidebar (if it's not hidden) on the small screen - above or below the content", 'helion' ) ),
					'override' => array(
						'mode'    => 'page',		// Override parameters for single posts moved to the 'sidebar_position_ss_single'
						'section' => esc_html__( 'Widgets', 'helion' ),
					),
					'dependency' => array(
						'sidebar_position' => array( '^hide' ),
					),
					'std'      => 'below',
					'qsetup'   => esc_html__( 'General', 'helion' ),
					'options'  => array(),
					'type'     => 'radio',
				),
				'sidebar_type'              => array(
					'title'    => esc_html__( 'Sidebar style', 'helion' ),
					'desc'     => wp_kses_data( __( 'Choose whether to use the default sidebar or sidebar Layouts (available only if the ThemeREX Addons is activated)', 'helion' ) ),
					'override'   => array(
						'mode'    => 'page',
						'section' => esc_html__( 'Widgets', 'helion' ),
					),
					'dependency' => array(
						'sidebar_position' => array( '^hide' ),
					),
					'std'      => 'default',
					'options'  => helion_get_list_header_footer_types(),
					'type'     => HELION_THEME_FREE || ! helion_exists_trx_addons() ? 'hidden' : 'radio',
				),
				'sidebar_style'                 => array(
					'title'      => esc_html__( 'Select custom layout', 'helion' ),
					'desc'       => wp_kses( __( 'Select custom sidebar from Layouts Builder', 'helion' ),'helion_kses_content' ),
					'override'   => array(
						'mode'    => 'page',
						'section' => esc_html__( 'Widgets', 'helion' ),
					),
					'dependency' => array(
						'sidebar_position' => array( '^hide' ),
						'sidebar_type' => array( 'custom' ),
					),
					'std'        => 'sidebar-custom-sidebar',
					'options'    => array(),
					'type'       => 'select',
				),
				'sidebar_widgets'               => array(
					'title'      => esc_html__( 'Sidebar widgets', 'helion' ),
					'desc'       => wp_kses_data( __( 'Select default widgets to show in the sidebar', 'helion' ) ),
					'override'   => array(
						'mode'    => 'page',		// Override parameters for single posts moved to the 'sidebar_widgets_single'
						'section' => esc_html__( 'Widgets', 'helion' ),
					),
					'dependency' => array(
						'sidebar_position' => array( '^hide' ),
						'sidebar_type'     => array( 'default')
					),
					'std'        => 'sidebar_widgets',
					'options'    => array(),
					'qsetup'     => esc_html__( 'General', 'helion' ),
					'type'       => 'select',
				),
				'sidebar_width'                 => array(
					'title'      => esc_html__( 'Sidebar width', 'helion' ),
					'desc'       => wp_kses_data( __( 'Width of the sidebar (in pixels). If empty - use default width', 'helion' ) ),
					'std'        => 370,
					'min'        => 150,
					'max'        => 500,
					'step'       => 10,
					'show_value' => true,
					'units'      => 'px',
					'refresh'    => false,
					'customizer' => 'sidebar',      // SASS variable's name to preview changes 'on fly'
					'type'       => HELION_THEME_FREE ? 'hidden' : 'slider',
				),
				'sidebar_gap'                   => array(
					'title'      => esc_html__( 'Sidebar gap', 'helion' ),
					'desc'       => wp_kses_data( __( 'Gap between content and sidebar (in pixels). If empty - use default gap', 'helion' ) ),
					'std'        => 50,
					'min'        => 0,
					'max'        => 100,
					'step'       => 1,
					'show_value' => true,
					'units'      => 'px',
					'refresh'    => false,
					'customizer' => 'gap',          // SASS variable's name to preview changes 'on fly'
					'type'       => HELION_THEME_FREE ? 'hidden' : 'slider',
				),
				'expand_content'                => array(
                    'title'   => esc_html__( 'Expand content', 'helion' ),
                    'desc'    => wp_kses_data( __( 'Expand the content width if the sidebar is hidden', 'helion' ) ),
                    'refresh' => false,
                    'override'   => array(
                        'mode'    => 'page,post,product,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
                        'section' => esc_html__( 'Widgets', 'helion' ),
                    ),
                    'std'     => 1,
                    'type'    => 'switch',
                ),
				'general_widgets_info'          => array(
					'title' => esc_html__( 'Additional widgets', 'helion' ),
					'desc'  => '',
					'type'  => HELION_THEME_FREE ? 'hidden' : 'info',
				),
				'widgets_above_page'            => array(
					'title'    => esc_html__( 'Widgets at the top of the page', 'helion' ),
					'desc'     => wp_kses_data( __( 'Select widgets to show at the top of the page (above content and sidebar)', 'helion' ) ),
					'override' => array(
						'mode'    => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Widgets', 'helion' ),
					),
					'std'      => 'hide',
					'options'  => array(),
					'type'     => HELION_THEME_FREE ? 'hidden' : 'select',
				),
				'widgets_above_content'         => array(
					'title'    => esc_html__( 'Widgets above the content', 'helion' ),
					'desc'     => wp_kses_data( __( 'Select widgets to show at the beginning of the content area', 'helion' ) ),
					'override' => array(
						'mode'    => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Widgets', 'helion' ),
					),
					'std'      => 'hide',
					'options'  => array(),
					'type'     => HELION_THEME_FREE ? 'hidden' : 'select',
				),
				'widgets_below_content'         => array(
					'title'    => esc_html__( 'Widgets below the content', 'helion' ),
					'desc'     => wp_kses_data( __( 'Select widgets to show at the ending of the content area', 'helion' ) ),
					'override' => array(
						'mode'    => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Widgets', 'helion' ),
					),
					'std'      => 'hide',
					'options'  => array(),
					'type'     => HELION_THEME_FREE ? 'hidden' : 'select',
				),
				'widgets_below_page'            => array(
					'title'    => esc_html__( 'Widgets at the bottom of the page', 'helion' ),
					'desc'     => wp_kses_data( __( 'Select widgets to show at the bottom of the page (below content and sidebar)', 'helion' ) ),
					'override' => array(
						'mode'    => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Widgets', 'helion' ),
					),
					'std'      => 'hide',
					'options'  => array(),
					'type'     => HELION_THEME_FREE ? 'hidden' : 'select',
				),

				'general_effects_info'          => array(
					'title' => esc_html__( 'Design & Effects', 'helion' ),
					'desc'  => '',
					'type'  => 'info',
				),
				'border_radius'                 => array(
					'title'      => esc_html__( 'Border radius', 'helion' ),
					'desc'       => wp_kses_data( __( 'Specify the border radius of the form fields and buttons in pixels', 'helion' ) ),
					'std'        => 5,
					'min'        => 0,
					'max'        => 20,
					'step'       => 1,
					'show_value' => true,
					'units'      => 'px',
					'refresh'    => false,
					'customizer' => 'rad',      // SASS name to preview changes 'on fly'
					'type'       => HELION_THEME_FREE ? 'hidden' : 'slider',
				),

				'general_misc_info'             => array(
					'title' => esc_html__( 'Miscellaneous', 'helion' ),
					'desc'  => '',
					'type'  => HELION_THEME_FREE ? 'hidden' : 'info',
				),
				'seo_snippets'                  => array(
					'title' => esc_html__( 'SEO snippets', 'helion' ),
					'desc'  => wp_kses_data( __( 'Add structured data markup to the single posts and pages', 'helion' ) ),
					'std'   => 0,
					'type'  => HELION_THEME_FREE ? 'hidden' : 'switch',
				),
				'privacy_text' => array(
					"title" => esc_html__("Text with Privacy Policy link", 'helion'),
					"desc"  => wp_kses_data( __("Specify text with Privacy Policy link for the checkbox 'I agree ...'", 'helion') ),
					"std"   => wp_kses( __( 'I agree that my submitted data is being collected and stored.', 'helion'),'helion_kses_content' ),
					"type"  => "text"
				),



				// 'Header'
				//---------------------------------------------
				'header'                        => array(
					'title'    => esc_html__( 'Header', 'helion' ),
					'desc'     => wp_kses_data( $msg_override ),
					'priority' => 30,
					'icon'     => 'icon-header',
					'type'     => 'section',
				),

				'header_style_info'             => array(
					'title' => esc_html__( 'Header style', 'helion' ),
					'desc'  => '',
					'type'  => 'info',
				),
				'header_type'                   => array(
					'title'    => esc_html__( 'Header style', 'helion' ),
					'desc'     => wp_kses_data( __( 'Choose whether to use the default header or header Layouts (available only if the ThemeREX Addons is activated)', 'helion' ) ),
					'override' => array(
						'mode'    => 'page,post,product,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Header', 'helion' ),
					),
					'std'      => 'default',
					'options'  => helion_get_list_header_footer_types(),
					'type'     => HELION_THEME_FREE || ! helion_exists_trx_addons() ? 'hidden' : 'radio',
				),
				'header_style'                  => array(
					'title'      => esc_html__( 'Select custom layout', 'helion' ),
					'desc'       => wp_kses( __( 'Select custom header from Layouts Builder', 'helion' ),'helion_kses_content' ),
					'override'   => array(
						'mode'    => 'page,post,product,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Header', 'helion' ),
					),
					'dependency' => array(
						'header_type' => array( 'custom' ),
					),
					'std'        => 'header-custom-elementor-header-default',
					'options'    => array(),
					'type'       => 'select',
				),
				'header_position'               => array(
					'title'    => esc_html__( 'Header position', 'helion' ),
					'desc'     => wp_kses_data( __( 'Select position to display the site header', 'helion' ) ),
					'override' => array(
						'mode'    => 'page,post,product,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Header', 'helion' ),
					),
					'std'      => 'default',
					'options'  => array(),
					'type'     => HELION_THEME_FREE ? 'hidden' : 'radio',
				),
				'header_fullheight'             => array(
					'title'    => esc_html__( 'Header fullheight', 'helion' ),
					'desc'     => wp_kses_data( __( 'Enlarge header area to fill the whole screen. Used only if the header has a background image', 'helion' ) ),
					'override' => array(
						'mode'    => 'page,post,product,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Header', 'helion' ),
					),
					'std'      => 0,
					'type'     => HELION_THEME_FREE ? 'hidden' : 'switch',
				),
				'header_wide'                   => array(
					'title'      => esc_html__( 'Header fullwidth', 'helion' ),
					'desc'       => wp_kses_data( __( 'Do you want to stretch the header widgets area to the entire window width?', 'helion' ) ),
					'override'   => array(
						'mode'    => 'page,post,product,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Header', 'helion' ),
					),
					'dependency' => array(
						'header_type' => array( 'default' ),
					),
					'std'        => 1,
					'type'       => HELION_THEME_FREE ? 'hidden' : 'switch',
				),
				'header_zoom'                   => array(
					'title'   => esc_html__( 'Header zoom', 'helion' ),
					'desc'    => wp_kses_data( __( 'Zoom the header title. 1 - original size', 'helion' ) ),
					'std'     => 1,
					'min'     => 0.2,
					'max'     => 2,
					'step'    => 0.1,
					'show_value' => true,
					'refresh' => false,
					'type'    => HELION_THEME_FREE ? 'hidden' : 'slider',
				),

				'header_widgets_info'           => array(
					'title' => esc_html__( 'Header widgets', 'helion' ),
					'desc'  => wp_kses_data( __( 'Here you can place a widget slider, advertising banners, etc.', 'helion' ) ),
					'type'  => 'info',
				),
				'header_widgets'                => array(
					'title'    => esc_html__( 'Header widgets', 'helion' ),
					'desc'     => wp_kses_data( __( 'Select set of widgets to show in the header on each page', 'helion' ) ),
					'override' => array(
						'mode'    => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Header', 'helion' ),
						'desc'    => wp_kses_data( __( 'Select set of widgets to show in the header on this page', 'helion' ) ),
					),
					'std'      => 'hide',
					'options'  => array(),
					'type'     => 'select',
				),
				'header_columns'                => array(
					'title'      => esc_html__( 'Header columns', 'helion' ),
					'desc'       => wp_kses_data( __( 'Select number columns to show widgets in the Header. If 0 - autodetect by the widgets count', 'helion' ) ),
					'override'   => array(
						'mode'    => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Header', 'helion' ),
					),
					'dependency' => array(
						'header_widgets' => array( '^hide' ),
					),
					'std'        => 0,
					'options'    => helion_get_list_range( 0, 6 ),
					'type'       => 'select',
				),

				'menu_info'                     => array(
					'title' => esc_html__( 'Main menu', 'helion' ),
					'desc'  => wp_kses_data( __( 'Select main menu style, position and other parameters', 'helion' ) ),
					'type'  => HELION_THEME_FREE ? 'hidden' : 'info',
				),
				'menu_style'                    => array(
					'title'    => esc_html__( 'Menu position', 'helion' ),
					'desc'     => wp_kses_data( __( 'Select position of the main menu', 'helion' ) ),
					'override' => array(
						'mode'    => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Header', 'helion' ),
					),
					'std'      => 'top',
					'options'  => array(
						'top'   => esc_html__( 'Top', 'helion' ),
						'left'  => esc_html__( 'Left', 'helion' ),
						'right' => esc_html__( 'Right', 'helion' ),
                        'right_anchors'  => esc_html__( 'Right (only for anchors)', 'helion' ),

					),
					'type'     => HELION_THEME_FREE || ! helion_exists_trx_addons() ? 'hidden' : 'radio',
				),
				'menu_side_icons'               => array(
					'title'      => esc_html__( 'Iconed sidemenu', 'helion' ),
					'desc'       => wp_kses_data( __( 'Get icons from anchors and display it in the sidemenu or mark sidemenu items with simple dots', 'helion' ) ),
					'override'   => array(
						'mode'    => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Header', 'helion' ),
					),
					'dependency' => array(
						'menu_style' => array( 'left', 'right', 'right_anchors' ),
					),
					'std'        => 1,
					'type'       => HELION_THEME_FREE ? 'hidden' : 'switch',
				),
				'menu_side_stretch'             => array(
					'title'      => esc_html__( 'Stretch sidemenu', 'helion' ),
					'desc'       => wp_kses_data( __( 'Stretch sidemenu to window height (if menu items number >= 5)', 'helion' ) ),
					'override'   => array(
						'mode'    => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Header', 'helion' ),
					),
					'dependency' => array(
						'menu_style' => array( 'left', 'right' ),
						'menu_side_icons' => array( 1 )
					),
					'std'        => 0,
					'type'       => HELION_THEME_FREE ? 'hidden' : 'switch',
				),
				'menu_mobile_fullscreen'        => array(
					'title' => esc_html__( 'Mobile menu fullscreen', 'helion' ),
					'desc'  => wp_kses_data( __( 'Display mobile and side menus on full screen (if checked) or slide narrow menu from the left or from the right side (if not checked)', 'helion' ) ),
					'std'   => 1,
					'type'  => HELION_THEME_FREE ? 'hidden' : 'switch',
				),

                'widgets_menu_mobile_fullscreen'                => array(
                    'title'    => esc_html__( 'Mobile menu fullscreen widgets ', 'helion' ),
                    'desc'     => wp_kses_data( __( 'Show widgets on the Fullscreen Mobile Menu', 'helion' ) ),
                    'dependency' => array(
                        'menu_mobile_fullscreen' => array( 1 )
                    ),
                    'refresh'  => false,
                    'std'      => 1,
                    'type'     => 'switch',
                ),

                'widgets_additional_menu_mobile_fullscreen'            => array(
                    'title'    => esc_html__( 'Select mobile menu widgets', 'helion' ),
                    'desc'     => wp_kses_data( __( 'Select widgets to show on the Fullscreen Mobile Menu', 'helion' ) ),
                    'dependency' => array(
                        'menu_mobile_fullscreen' => array( 1 ),
                        'widgets_menu_mobile_fullscreen' => array( 1 )
                    ),
                    'std'      => 'hide',
                    'options'  => array(),
                    'type'     => HELION_THEME_FREE ? 'hidden' : 'select',
                ),


				'header_image_info'             => array(
					'title' => esc_html__( 'Header image', 'helion' ),
					'desc'  => '',
					'type'  => HELION_THEME_FREE ? 'hidden' : 'info',
				),
				'header_image_override'         => array(
					'title'    => esc_html__( 'Header image override', 'helion' ),
					'desc'     => wp_kses_data( __( "Allow override the header image with the page's/post's/product's/etc. featured image", 'helion' ) ),
					'override' => array(
						'mode'    => 'page,cpt_portfolio',
						'section' => esc_html__( 'Header', 'helion' ),
					),
					'std'      => 0,
					'type'     => HELION_THEME_FREE ? 'hidden' : 'switch',
				),

				'header_mobile_info'            => array(
					'title'      => esc_html__( 'Mobile header', 'helion' ),
					'desc'       => wp_kses_data( __( 'Configure the mobile version of the header', 'helion' ) ),
					'priority'   => 500,
					'dependency' => array(
						'header_type' => array( 'default' ),
					),
					'type'       => HELION_THEME_FREE ? 'hidden' : 'info',
				),
				'header_mobile_enabled'         => array(
					'title'      => esc_html__( 'Enable the mobile header', 'helion' ),
					'desc'       => wp_kses_data( __( 'Use the mobile version of the header (if checked) or relayout the current header on mobile devices', 'helion' ) ),
					'dependency' => array(
						'header_type' => array( 'default' ),
					),
					'std'        => 0,
					'type'       => HELION_THEME_FREE ? 'hidden' : 'switch',
				),
				'header_mobile_additional_info' => array(
					'title'      => esc_html__( 'Additional info', 'helion' ),
					'desc'       => wp_kses_data( __( 'Additional info to show at the top of the mobile header', 'helion' ) ),
					'std'        => '',
					'dependency' => array(
						'header_type'           => array( 'default' ),
						'header_mobile_enabled' => array( 1 ),
					),
					'refresh'    => false,
					'teeny'      => false,
					'rows'       => 20,
					'type'       => HELION_THEME_FREE ? 'hidden' : 'text_editor',
				),
				'header_mobile_hide_info'       => array(
					'title'      => esc_html__( 'Hide additional info', 'helion' ),
					'std'        => 0,
					'dependency' => array(
						'header_type'           => array( 'default' ),
						'header_mobile_enabled' => array( 1 ),
					),
					'type'       => HELION_THEME_FREE ? 'hidden' : 'switch',
				),
				'header_mobile_hide_logo'       => array(
					'title'      => esc_html__( 'Hide logo', 'helion' ),
					'std'        => 0,
					'dependency' => array(
						'header_type'           => array( 'default' ),
						'header_mobile_enabled' => array( 1 ),
					),
					'type'       => HELION_THEME_FREE ? 'hidden' : 'switch',
				),
				'header_mobile_hide_login'      => array(
					'title'      => esc_html__( 'Hide login/logout', 'helion' ),
					'std'        => 0,
					'dependency' => array(
						'header_type'           => array( 'default' ),
						'header_mobile_enabled' => array( 1 ),
					),
					'type'       => HELION_THEME_FREE ? 'hidden' : 'switch',
				),
				'header_mobile_hide_search'     => array(
					'title'      => esc_html__( 'Hide search', 'helion' ),
					'std'        => 0,
					'dependency' => array(
						'header_type'           => array( 'default' ),
						'header_mobile_enabled' => array( 1 ),
					),
					'type'       => HELION_THEME_FREE ? 'hidden' : 'switch',
				),
				'header_mobile_hide_cart'       => array(
					'title'      => esc_html__( 'Hide cart', 'helion' ),
					'std'        => 0,
					'dependency' => array(
						'header_type'           => array( 'default' ),
						'header_mobile_enabled' => array( 1 ),
					),
					'type'       => HELION_THEME_FREE ? 'hidden' : 'switch',
				),



				// 'Footer'
				//---------------------------------------------
				'footer'                        => array(
					'title'    => esc_html__( 'Footer', 'helion' ),
					'desc'     => wp_kses_data( $msg_override ),
					'priority' => 50,
					'icon'     => 'icon-footer',
					'type'     => 'section',
				),
				'footer_type'                   => array(
					'title'    => esc_html__( 'Footer style', 'helion' ),
					'desc'     => wp_kses_data( __( 'Choose whether to use the default footer or footer Layouts (available only if the ThemeREX Addons is activated)', 'helion' ) ),
					'override' => array(
						'mode'    => 'page,post,product,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Footer', 'helion' ),
					),
					'std'      => 'default',
					'options'  => helion_get_list_header_footer_types(),
					'type'     => HELION_THEME_FREE || ! helion_exists_trx_addons() ? 'hidden' : 'radio',
				),
				'footer_style'                  => array(
					'title'      => esc_html__( 'Select custom layout', 'helion' ),
					'desc'       => wp_kses( __( 'Select custom footer from Layouts Builder', 'helion' ),'helion_kses_content' ),
					'override'   => array(
						'mode'    => 'page,post,product,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Footer', 'helion' ),
					),
					'dependency' => array(
						'footer_type' => array( 'custom' ),
					),
					'std'        => 'footer-custom-elementor-footer-default',
					'options'    => array(),
					'type'       => 'select',
				),
				'footer_widgets'                => array(
					'title'      => esc_html__( 'Footer widgets', 'helion' ),
					'desc'       => wp_kses_data( __( 'Select set of widgets to show in the footer', 'helion' ) ),
					'override'   => array(
						'mode'    => 'page,post,product,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Footer', 'helion' ),
					),
					'dependency' => array(
						'footer_type' => array( 'default' ),
					),
					'std'        => 'footer_widgets',
					'options'    => array(),
					'type'       => 'select',
				),
				'footer_columns'                => array(
					'title'      => esc_html__( 'Footer columns', 'helion' ),
					'desc'       => wp_kses_data( __( 'Select number columns to show widgets in the footer. If 0 - autodetect by the widgets count', 'helion' ) ),
					'override'   => array(
						'mode'    => 'page,post,product,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Footer', 'helion' ),
					),
					'dependency' => array(
						'footer_type'    => array( 'default' ),
						'footer_widgets' => array( '^hide' ),
					),
					'std'        => 0,
					'options'    => helion_get_list_range( 0, 6 ),
					'type'       => 'select',
				),
				'footer_wide'                   => array(
					'title'      => esc_html__( 'Footer fullwidth', 'helion' ),
					'desc'       => wp_kses_data( __( 'Do you want to stretch the footer to the entire window width?', 'helion' ) ),
					'override'   => array(
						'mode'    => 'page,post,product,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Footer', 'helion' ),
					),
					'dependency' => array(
						'footer_type' => array( 'default' ),
					),
					'std'        => 0,
					'type'       => 'switch',
				),
				'logo_in_footer'                => array(
					'title'      => esc_html__( 'Show logo', 'helion' ),
					'desc'       => wp_kses_data( __( 'Show logo in the footer', 'helion' ) ),
					'refresh'    => false,
					'dependency' => array(
						'footer_type' => array( 'default' ),
					),
					'std'        => 0,
					'type'       => 'switch',
				),
				'logo_footer'                   => array(
					'title'      => esc_html__( 'Logo for footer', 'helion' ),
					'desc'       => wp_kses_data( __( 'Select or upload site logo to display it in the footer', 'helion' ) ),
					'dependency' => array(
						'footer_type'    => array( 'default' ),
						'logo_in_footer' => array( 1 ),
					),
					'std'        => '',
					'type'       => 'image',
				),
				'logo_footer_retina'            => array(
					'title'      => esc_html__( 'Logo for footer (Retina)', 'helion' ),
					'desc'       => wp_kses_data( __( 'Select or upload logo for the footer area used on Retina displays (if empty - use default logo from the field above)', 'helion' ) ),
					'dependency' => array(
						'footer_type'         => array( 'default' ),
						'logo_in_footer'      => array( 1 ),
						'logo_retina_enabled' => array( 1 ),
					),
					'std'        => '',
					'type'       => HELION_THEME_FREE ? 'hidden' : 'image',
				),
				'socials_in_footer'             => array(
					'title'      => esc_html__( 'Show social icons', 'helion' ),
					'desc'       => wp_kses_data( __( 'Show social icons in the footer (under logo or footer widgets)', 'helion' ) ),
					'dependency' => array(
						'footer_type' => array( 'default' ),
					),
					'std'        => 0,
					'type'       => ! helion_exists_trx_addons() ? 'hidden' : 'switch',
				),
				'copyright'                     => array(
					'title'      => esc_html__( 'Copyright', 'helion' ),
					'desc'       => wp_kses_data( __( 'Copyright text in the footer. Use {Y} to insert current year and press "Enter" to create a new line', 'helion' ) ),
					'translate'  => true,
					'std'        => esc_html__( 'AxiomThemes &copy; {Y}. All Rights Reserved.', 'helion' ),
					'dependency' => array(
						'footer_type' => array( 'default' ),
					),
					'refresh'    => false,
					'type'       => 'textarea',
				),



				// 'Mobile version'
				//---------------------------------------------
				'mobile'                        => array(
					'title'    => esc_html__( 'Mobile', 'helion' ),
					'desc'     => wp_kses_data( $msg_override ),
					'priority' => 55,
					'icon'     => 'icon-smartphone',
					'type'     => 'section',
				),

				'mobile_header_info'            => array(
					'title' => esc_html__( 'Header on the mobile device', 'helion' ),
					'desc'  => '',
					'type'  => 'info',
				),
				'header_type_mobile'            => array(
					'title'    => esc_html__( 'Header style', 'helion' ),
					'desc'     => wp_kses_data( __( 'Choose whether to use on mobile devices: the default header or header Layouts (available only if the ThemeREX Addons is activated)', 'helion' ) ),
					'std'      => 'inherit',
					'options'  => helion_get_list_header_footer_types( true ),
					'type'     => HELION_THEME_FREE || ! helion_exists_trx_addons() ? 'hidden' : 'radio',
				),
				'header_style_mobile'           => array(
					'title'      => esc_html__( 'Select custom layout', 'helion' ),
					'desc'       => wp_kses( __( 'Select custom header from Layouts Builder', 'helion' ),'helion_kses_content' ),
					'dependency' => array(
						'header_type_mobile' => array( 'custom' ),
					),
					'std'        => 'inherit',
					'options'    => array(),
					'type'       => 'select',
				),
				'header_position_mobile'        => array(
					'title'    => esc_html__( 'Header position', 'helion' ),
					'desc'     => wp_kses_data( __( 'Select position to display the site header', 'helion' ) ),
					'std'      => 'inherit',
					'options'  => array(),
					'type'     => HELION_THEME_FREE ? 'hidden' : 'radio',
				),

				'mobile_sidebar_info'           => array(
					'title' => esc_html__( 'Sidebar on the mobile device', 'helion' ),
					'desc'  => '',
					'type'  => 'info',
				),
				'sidebar_position_mobile'       => array(
					'title'    => esc_html__( 'Sidebar position on mobile', 'helion' ),
					'desc'     => wp_kses_data( __( 'Select position to show sidebar on mobile devices', 'helion' ) ),
					'std'      => 'inherit',
					'options'  => array(),
					'type'     => 'radio',
				),
				'sidebar_type_mobile'           => array(
					'title'    => esc_html__( 'Sidebar style', 'helion' ),
					'desc'     => wp_kses_data( __( 'Choose whether to use the default sidebar or sidebar Layouts (available only if the ThemeREX Addons is activated)', 'helion' ) ),
					'dependency' => array(
						'sidebar_position_mobile' => array( '^hide' ),
					),
					'std'      => 'inherit',
					'options'  => helion_get_list_header_footer_types( true ),
					'type'     => HELION_THEME_FREE || ! helion_exists_trx_addons() ? 'hidden' : 'radio',
				),
				'sidebar_style_mobile'          => array(
					'title'      => esc_html__( 'Select custom layout', 'helion' ),
					'desc'       => wp_kses( __( 'Select custom sidebar from Layouts Builder', 'helion' ),'helion_kses_content' ),
					'dependency' => array(
						'sidebar_position_mobile' => array( '^hide' ),
						'sidebar_type_mobile' => array( 'custom' ),
					),
					'std'        => 'inherit',
					'options'    => array(),
					'type'       => 'select',
				),
				'sidebar_widgets_mobile'        => array(
					'title'      => esc_html__( 'Sidebar widgets', 'helion' ),
					'desc'       => wp_kses_data( __( 'Select default widgets to show in the sidebar on mobile devices', 'helion' ) ),
					'dependency' => array(
						'sidebar_position_mobile' => array( '^hide' ),
						'sidebar_type_mobile' => array( 'default' )
					),
					'std'        => 'sidebar_widgets',
					'options'    => array(),
					'type'       => 'select',
				),
				'expand_content_mobile'         => array(
					'title'   => esc_html__( 'Expand content', 'helion' ),
					'desc'    => wp_kses_data( __( 'Expand the content width if the sidebar is hidden on mobile devices', 'helion' ) ),
					'refresh' => false,
					'dependency' => array(
						'sidebar_position_mobile' => array( 'hide', 'inherit' ),
					),
					'std'     => 'inherit',
					'options'  => helion_get_list_checkbox_values( true ),
					'type'     => HELION_THEME_FREE ? 'hidden' : 'radio',
				),

				'mobile_footer_info'           => array(
					'title' => esc_html__( 'Footer on the mobile device', 'helion' ),
					'desc'  => '',
					'type'  => 'info',
				),
				'footer_type_mobile'           => array(
					'title'    => esc_html__( 'Footer style', 'helion' ),
					'desc'     => wp_kses_data( __( 'Choose whether to use on mobile devices: the default footer or footer Layouts (available only if the ThemeREX Addons is activated)', 'helion' ) ),
					'std'      => 'inherit',
					'options'  => helion_get_list_header_footer_types( true ),
					'type'     => HELION_THEME_FREE || ! helion_exists_trx_addons() ? 'hidden' : 'radio',
				),
				'footer_style_mobile'          => array(
					'title'      => esc_html__( 'Select custom layout', 'helion' ),
					'desc'       => wp_kses( __( 'Select custom footer from Layouts Builder', 'helion' ),'helion_kses_content' ),
					'dependency' => array(
						'footer_type_mobile' => array( 'custom' ),
					),
					'std'        => 'inherit',
					'options'    => array(),
					'type'       => 'select',
				),
				'footer_widgets_mobile'        => array(
					'title'      => esc_html__( 'Footer widgets', 'helion' ),
					'desc'       => wp_kses_data( __( 'Select set of widgets to show in the footer', 'helion' ) ),
					'dependency' => array(
						'footer_type_mobile' => array( 'default' ),
					),
					'std'        => 'footer_widgets',
					'options'    => array(),
					'type'       => 'select',
				),
				'footer_columns_mobile'        => array(
					'title'      => esc_html__( 'Footer columns', 'helion' ),
					'desc'       => wp_kses_data( __( 'Select number columns to show widgets in the footer. If 0 - autodetect by the widgets count', 'helion' ) ),
					'dependency' => array(
						'footer_type_mobile'    => array( 'default' ),
						'footer_widgets_mobile' => array( '^hide' ),
					),
					'std'        => 0,
					'options'    => helion_get_list_range( 0, 6 ),
					'type'       => 'select',
				),



				// 'Blog'
				//---------------------------------------------
				'blog'                          => array(
					'title'    => esc_html__( 'Blog', 'helion' ),
					'desc'     => wp_kses_data( __( 'Options of the the blog archive', 'helion' ) ),
					'priority' => 70,
					'icon'     => 'icon-page',
					'type'     => 'panel',
				),


				// Blog - Posts page
				//---------------------------------------------
				'blog_general'                  => array(
					'title' => esc_html__( 'Posts page', 'helion' ),
					'desc'  => wp_kses_data( __( 'Style and components of the blog archive', 'helion' ) ),
					'type'  => 'section',
				),
				'blog_general_info'             => array(
					'title'  => esc_html__( 'Posts page settings', 'helion' ),
					'desc'   => '',
					'qsetup' => esc_html__( 'General', 'helion' ),
					'type'   => 'info',
				),
				'blog_style'                    => array(
					'title'      => esc_html__( 'Blog style', 'helion' ),
					'desc'       => '',
					'override'   => array(
						'mode'    => 'page',
						'section' => esc_html__( 'Content', 'helion' ),
					),
					'dependency' => array(
						'compare' => 'or',
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					'std'        => 'excerpt',
					'qsetup'     => esc_html__( 'General', 'helion' ),
					'options'    => array(),
					'type'       => 'select',
				),
				'first_post_large'              => array(
					'title'      => esc_html__( 'First post large', 'helion' ),
					'desc'       => wp_kses_data( __( 'Make your first post stand out by making it bigger', 'helion' ) ),
					'override'   => array(
						'mode'    => 'page',
						'section' => esc_html__( 'Content', 'helion' ),
					),
					'dependency' => array(
						'compare' => 'or',
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
						'blog_style' => array( 'classic', 'masonry' ),
					),
					'std'        => 0,
					'type'       => 'switch',
				),
				'blog_content'                  => array(
					'title'      => esc_html__( 'Posts content', 'helion' ),
					'desc'       => wp_kses_data( __( 'Display either post excerpts or the full post content', 'helion' ) ),
					'std'        => 'excerpt',
					'dependency' => array(
						'blog_style' => array( 'excerpt' ),
					),
					'options'    => array(
						'excerpt'  => esc_html__( 'Excerpt', 'helion' ),
						'fullpost' => esc_html__( 'Full post', 'helion' ),
					),
					'type'       => 'radio',
				),
				'excerpt_length'                => array(
					'title'      => esc_html__( 'Excerpt length', 'helion' ),
					'desc'       => wp_kses_data( __( 'Length (in words) to generate excerpt from the post content. Attention! If the post excerpt is explicitly specified - it appears unchanged', 'helion' ) ),
					'dependency' => array(
						'blog_style'   => array( 'excerpt' ),
						'blog_content' => array( 'excerpt' ),
					),
					'std'        => 22,
					'type'       => 'text',
				),
				'blog_columns'                  => array(
					'title'   => esc_html__( 'Blog columns', 'helion' ),
					'desc'    => wp_kses_data( __( 'How many columns should be used in the blog archive (from 2 to 4)?', 'helion' ) ),
					'std'     => 2,
					'options' => helion_get_list_range( 2, 4 ),
					'type'    => 'hidden',      // This options is available and must be overriden only for some modes (for example, 'shop')
				),
				'post_type'                     => array(
					'title'      => esc_html__( 'Post type', 'helion' ),
					'desc'       => wp_kses_data( __( 'Select post type to show in the blog archive', 'helion' ) ),
					'override'   => array(
						'mode'    => 'page',
						'section' => esc_html__( 'Content', 'helion' ),
					),
					'dependency' => array(
						'compare' => 'or',
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					'linked'     => 'parent_cat',
					'refresh'    => false,
					'hidden'     => true,
					'std'        => 'post',
					'options'    => array(),
					'type'       => 'select',
				),
				'parent_cat'                    => array(
					'title'      => esc_html__( 'Category to show', 'helion' ),
					'desc'       => wp_kses_data( __( 'Select category to show in the blog archive', 'helion' ) ),
					'override'   => array(
						'mode'    => 'page',
						'section' => esc_html__( 'Content', 'helion' ),
					),
					'dependency' => array(
						'compare' => 'or',
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					'refresh'    => false,
					'hidden'     => true,
					'std'        => '0',
					'options'    => array(),
					'type'       => 'select',
				),
				'posts_per_page'                => array(
					'title'      => esc_html__( 'Posts per page', 'helion' ),
					'desc'       => wp_kses_data( __( 'How many posts will be displayed on this page', 'helion' ) ),
					'override'   => array(
						'mode'    => 'page',
						'section' => esc_html__( 'Content', 'helion' ),
					),
					'dependency' => array(
						'compare' => 'or',
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					'hidden'     => true,
					'std'        => '',
					'type'       => 'text',
				),
				'blog_pagination'               => array(
					'title'      => esc_html__( 'Pagination style', 'helion' ),
					'desc'       => wp_kses_data( __( 'Show Older/Newest posts or Page numbers below the posts list', 'helion' ) ),
					'override'   => array(
						'mode'    => 'page',
						'section' => esc_html__( 'Content', 'helion' ),
					),
					'std'        => 'pages',
					'qsetup'     => esc_html__( 'General', 'helion' ),
					'dependency' => array(
						'compare' => 'or',
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					'options'    => array(
						'pages'    => esc_html__( 'Page numbers', 'helion' ),
						'links'    => esc_html__( 'Older/Newest', 'helion' ),
						'more'     => esc_html__( 'Load more', 'helion' ),
						'infinite' => esc_html__( 'Infinite scroll', 'helion' ),
					),
					'type'       => 'select',
				),
				'blog_animation'                => array(
					'title'      => esc_html__( 'Post animation', 'helion' ),
					'desc'       => wp_kses_data( __( 'Select animation to show posts in the blog. Attention! Do not use any animation on pages with the "wheel to the anchor" behaviour (like a "Chess 2 columns")!', 'helion' ) ),
					'override'   => array(
						'mode'    => 'page',
						'section' => esc_html__( 'Content', 'helion' ),
					),
					'dependency' => array(
						'compare'                                  => 'or',
						'#page_template'                           => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					'std'        => 'none',
					'options'    => array(),
					'type'       => HELION_THEME_FREE ? 'hidden' : 'select',
				),
				'disable_animation_on_mobile'   => array(
					'title'      => esc_html__( 'Disable animation on mobile', 'helion' ),
					'desc'       => wp_kses_data( __( 'Disable any posts animation on mobile devices', 'helion' ) ),
					'std'        => 1,
					'type'       => HELION_THEME_FREE ? 'hidden' : 'switch',
				),
				'show_filters'                  => array(
					'title'      => esc_html__( 'Show filters', 'helion' ),
					'desc'       => wp_kses_data( __( 'Show categories as tabs to filter posts', 'helion' ) ),
					'override'   => array(
						'mode'    => 'page',
						'section' => esc_html__( 'Content', 'helion' ),
					),
					'dependency' => array(
						'compare'                                  => 'or',
						'#page_template'                           => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
						'blog_style'                               => array( 'portfolio', 'gallery' ),
					),
					'hidden'     => true,
					'std'        => 0,
					'type'       => HELION_THEME_FREE ? 'hidden' : 'switch',
				),
				'video_in_popup'                => array(
					'title'      => esc_html__( 'Open video in the popup on a blog archive', 'helion' ),
					'desc'       => wp_kses_data( __( 'Open the video from posts in the popup (if plugin "ThemeREX Addons" is installed) or play the video instead the cover image', 'helion' ) ),
					'std'        => 0,
					'override'   => array(
						'mode'    => 'page',
						'section' => esc_html__( 'Content', 'helion' ),
					),
					'dependency' => array(
						'compare'                                  => 'or',
						'#page_template'                           => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					'type'       => 'switch',
				),
				'open_full_post_in_blog'        => array(
					'title'      => esc_html__( 'Open full post in blog', 'helion' ),
					'desc'       => wp_kses_data( __( 'Allow to open the full version of the post directly in the blog feed. Attention! Applies only to 1 column layouts!', 'helion' ) ),
					'override'   => array(
						'mode'    => 'page',
						'section' => esc_html__( 'Content', 'helion' ),
					),
					'std'        => 0,
					'type'       => 'switch',
				),
				'open_full_post_hide_author'    => array(
					'title'      => esc_html__( 'Hide author bio', 'helion' ),
					'desc'       => wp_kses_data( __( "Hide author bio after post content when open the full version of the post directly in the blog feed.", 'helion' ) ),
					'override'   => array(
						'mode'    => 'page',
						'section' => esc_html__( 'Content', 'helion' ),
					),
					'dependency' => array(
						'open_full_post_in_blog' => array( 1 ),
					),
					'std'        => 1,
					'type'       => HELION_THEME_FREE ? 'hidden' : 'switch',
				),
				'open_full_post_hide_related'   => array(
					'title'      => esc_html__( 'Hide related posts', 'helion' ),
					'desc'       => wp_kses_data( __( "Hide related posts after post content when open the full version of the post directly in the blog feed.", 'helion' ) ),
					'override'   => array(
						'mode'    => 'page',
						'section' => esc_html__( 'Content', 'helion' ),
					),
					'dependency' => array(
						'open_full_post_in_blog' => array( 1 ),
					),
					'std'        => 1,
					'type'       => HELION_THEME_FREE ? 'hidden' : 'switch',
				),

				'blog_header_info'              => array(
					'title' => esc_html__( 'Header', 'helion' ),
					'desc'  => '',
					'type'  => 'info',
				),
				'header_type_blog'              => array(
					'title'    => esc_html__( 'Header style', 'helion' ),
					'desc'     => wp_kses_data( __( 'Choose whether to use the default header or header Layouts (available only if the ThemeREX Addons is activated)', 'helion' ) ),
					'std'      => 'inherit',
					'options'  => helion_get_list_header_footer_types( true ),
					'type'     => HELION_THEME_FREE || ! helion_exists_trx_addons() ? 'hidden' : 'radio',
				),
				'header_style_blog'             => array(
					'title'      => esc_html__( 'Select custom layout', 'helion' ),
					'desc'       => wp_kses( __( 'Select custom header from Layouts Builder', 'helion' ),'helion_kses_content' ),
					'dependency' => array(
						'header_type_blog' => array( 'custom' ),
					),
					'std'        => 'inherit',
					'options'    => array(),
					'type'       => 'select',
				),
				'header_position_blog'          => array(
					'title'    => esc_html__( 'Header position', 'helion' ),
					'desc'     => wp_kses_data( __( 'Select position to display the site header', 'helion' ) ),
					'std'      => 'inherit',
					'options'  => array(),
					'type'     => HELION_THEME_FREE ? 'hidden' : 'radio',
				),
				'header_fullheight_blog'        => array(
					'title'    => esc_html__( 'Header fullheight', 'helion' ),
					'desc'     => wp_kses_data( __( 'Enlarge header area to fill whole screen. Used only if header have a background image', 'helion' ) ),
					'std'      => 'inherit',
					'options'  => helion_get_list_checkbox_values( true ),
					'type'     => HELION_THEME_FREE ? 'hidden' : 'radio',
				),
				'header_wide_blog'              => array(
					'title'      => esc_html__( 'Header fullwidth', 'helion' ),
					'desc'       => wp_kses_data( __( 'Do you want to stretch the header widgets area to the entire window width?', 'helion' ) ),
					'dependency' => array(
						'header_type_blog' => array( 'default' ),
					),
					'std'      => 'inherit',
					'options'  => helion_get_list_checkbox_values( true ),
					'type'     => HELION_THEME_FREE ? 'hidden' : 'radio',
				),

				'blog_sidebar_info'             => array(
					'title' => esc_html__( 'Sidebar', 'helion' ),
					'desc'  => '',
					'type'  => 'info',
				),
				'sidebar_position_blog'         => array(
					'title'   => esc_html__( 'Sidebar position', 'helion' ),
					'desc'    => wp_kses_data( __( 'Select position to show sidebar', 'helion' ) ),
					'std'     => 'inherit',
					'options' => array(),
					'qsetup'     => esc_html__( 'General', 'helion' ),
					'type'    => 'radio',
				),
				'sidebar_position_ss_blog'  => array(
					'title'    => esc_html__( 'Sidebar position on the small screen', 'helion' ),
					'desc'     => wp_kses_data( __( 'Select position to move sidebar on the small screen - above or below the content', 'helion' ) ),
					'dependency' => array(
						'sidebar_position_blog' => array( '^hide' ),
					),
					'std'      => 'inherit',
					'qsetup'   => esc_html__( 'General', 'helion' ),
					'options'  => array(),
					'type'     => 'radio',
				),
				'sidebar_type_blog'           => array(
					'title'    => esc_html__( 'Sidebar style', 'helion' ),
					'desc'     => wp_kses_data( __( 'Choose whether to use the default sidebar or sidebar Layouts (available only if the ThemeREX Addons is activated)', 'helion' ) ),
					'dependency' => array(
						'sidebar_position_blog' => array( '^hide' ),
					),
					'std'      => 'default',
					'options'  => helion_get_list_header_footer_types(),
					'type'     => HELION_THEME_FREE || ! helion_exists_trx_addons() ? 'hidden' : 'radio',
				),
				'sidebar_style_blog'            => array(
					'title'      => esc_html__( 'Select custom layout', 'helion' ),
					'desc'       => wp_kses( __( 'Select custom sidebar from Layouts Builder', 'helion' ),'helion_kses_content' ),
					'dependency' => array(
						'sidebar_position_blog' => array( '^hide' ),
						'sidebar_type_blog'     => array( 'custom' ),
					),
					'std'        => 'sidebar-custom-sidebar',
					'options'    => array(),
					'type'       => 'select',
				),
				'sidebar_widgets_blog'          => array(
					'title'      => esc_html__( 'Sidebar widgets', 'helion' ),
					'desc'       => wp_kses_data( __( 'Select default widgets to show in the sidebar', 'helion' ) ),
					'dependency' => array(
						'sidebar_position_blog' => array( '^hide' ),
						'sidebar_type_blog'     => array( 'default' ),
					),
					'std'        => 'sidebar_widgets',
					'options'    => array(),
					'qsetup'     => esc_html__( 'General', 'helion' ),
					'type'       => 'select',
				),
				'expand_content_blog'           => array(
					'title'   => esc_html__( 'Expand content', 'helion' ),
					'desc'    => wp_kses_data( __( 'Expand the content width if the sidebar is hidden', 'helion' ) ),
					'refresh' => false,
					'std'     => 'inherit',
					'options'  => helion_get_list_checkbox_values( true ),
					'type'     => HELION_THEME_FREE ? 'hidden' : 'radio',
				),

				'blog_widgets_info'             => array(
					'title' => esc_html__( 'Additional widgets', 'helion' ),
					'desc'  => '',
					'type'  => HELION_THEME_FREE ? 'hidden' : 'info',
				),
				'widgets_above_page_blog'       => array(
					'title'   => esc_html__( 'Widgets at the top of the page', 'helion' ),
					'desc'    => wp_kses_data( __( 'Select widgets to show at the top of the page (above content and sidebar)', 'helion' ) ),
					'std'     => 'hide',
					'options' => array(),
					'type'    => HELION_THEME_FREE ? 'hidden' : 'select',
				),
				'widgets_above_content_blog'    => array(
					'title'   => esc_html__( 'Widgets above the content', 'helion' ),
					'desc'    => wp_kses_data( __( 'Select widgets to show at the beginning of the content area', 'helion' ) ),
					'std'     => 'hide',
					'options' => array(),
					'type'    => HELION_THEME_FREE ? 'hidden' : 'select',
				),
				'widgets_below_content_blog'    => array(
					'title'   => esc_html__( 'Widgets below the content', 'helion' ),
					'desc'    => wp_kses_data( __( 'Select widgets to show at the ending of the content area', 'helion' ) ),
					'std'     => 'hide',
					'options' => array(),
					'type'    => HELION_THEME_FREE ? 'hidden' : 'select',
				),
				'widgets_below_page_blog'       => array(
					'title'   => esc_html__( 'Widgets at the bottom of the page', 'helion' ),
					'desc'    => wp_kses_data( __( 'Select widgets to show at the bottom of the page (below content and sidebar)', 'helion' ) ),
					'std'     => 'hide',
					'options' => array(),
					'type'    => HELION_THEME_FREE ? 'hidden' : 'select',
				),

				'blog_advanced_info'            => array(
					'title' => esc_html__( 'Advanced settings', 'helion' ),
					'desc'  => '',
					'type'  => 'info',
				),
				'no_image'                      => array(
					'title' => esc_html__( 'Image placeholder', 'helion' ),
					'desc'  => wp_kses_data( __( "Select or upload an image used as placeholder for posts without a featured image. Placeholder is used on the blog stream page only (no placeholder in single post), and only in those styles of it where non-using featured image doesn't seem appropriate.", 'helion' ) ),
					'std'   => '',
					'type'  => 'image',
				),
				'time_diff_before'              => array(
					'title' => esc_html__( 'Easy Readable Date Format', 'helion' ),
					'desc'  => wp_kses_data( __( "For how many days to show the easy-readable date format (e.g. '3 days ago') instead of the standard publication date", 'helion' ) ),
					'std'   => 5,
					'type'  => 'text',
				),
				'sticky_style'                  => array(
					'title'   => esc_html__( 'Sticky posts style', 'helion' ),
					'desc'    => wp_kses_data( __( 'Select style of the sticky posts output', 'helion' ) ),
					'std'     => 'inherit',
					'options' => array(
						'inherit' => esc_html__( 'Decorated posts', 'helion' ),
						'columns' => esc_html__( 'Mini-cards', 'helion' ),
					),
					'type'    => HELION_THEME_FREE ? 'hidden' : 'select',
				),
				'meta_parts'                    => array(
					'title'      => esc_html__( 'Post meta', 'helion' ),
					'desc'       => wp_kses_data( __( "If your blog page is created using the 'Blog archive' page template, set up the 'Post Meta' settings in the 'Theme Options' section of that page. Post counters and Share Links are available only if plugin ThemeREX Addons is active", 'helion' ) )
								. '<br>'
								. wp_kses_data( __( '<b>Tip:</b> Drag items to change their order.', 'helion' ) ),
					'override'   => array(
						'mode'    => 'page',
						'section' => esc_html__( 'Content', 'helion' ),
					),
					'dependency' => array(
						'compare' => 'or',
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					'dir'        => 'vertical',
					'sortable'   => true,
					'std'        => 'categories=1|date=1|views=0|likes=0|comments=0|author=0|share=0|edit=0',
					'options'    => helion_get_list_meta_parts(),
					'type'       => HELION_THEME_FREE ? 'hidden' : 'checklist',
				),


				// Blog - Single posts
				//---------------------------------------------
				'blog_single'                   => array(
					'title' => esc_html__( 'Single posts', 'helion' ),
					'desc'  => wp_kses_data( __( 'Settings of the single post', 'helion' ) ),
					'type'  => 'section',
				),

				'blog_single_header_info'       => array(
					'title' => esc_html__( 'Header', 'helion' ),
					'desc'  => '',
					'type'  => 'info',
				),
				'header_type_single'            => array(
					'title'    => esc_html__( 'Header style', 'helion' ),
					'desc'     => wp_kses_data( __( 'Choose whether to use the default header or header Layouts (available only if the ThemeREX Addons is activated)', 'helion' ) ),
					'std'      => 'inherit',
					'options'  => helion_get_list_header_footer_types( true ),
					'type'     => HELION_THEME_FREE || ! helion_exists_trx_addons() ? 'hidden' : 'radio',
				),
				'header_style_single'           => array(
					'title'      => esc_html__( 'Select custom layout', 'helion' ),
					'desc'       => wp_kses( __( 'Select custom header from Layouts Builder', 'helion' ),'helion_kses_content' ),
					'dependency' => array(
						'header_type_single' => array( 'custom' ),
					),
					'std'        => 'inherit',
					'options'    => array(),
					'type'       => 'select',
				),
				'header_position_single'        => array(
					'title'    => esc_html__( 'Header position', 'helion' ),
					'desc'     => wp_kses_data( __( 'Select position to display the site header', 'helion' ) ),
					'std'      => 'inherit',
					'options'  => array(),
					'type'     => HELION_THEME_FREE ? 'hidden' : 'radio',
				),
				'header_fullheight_single'      => array(
					'title'    => esc_html__( 'Header fullheight', 'helion' ),
					'desc'     => wp_kses_data( __( 'Enlarge header area to fill whole screen. Used only if header have a background image', 'helion' ) ),
					'std'      => 'inherit',
					'options'  => helion_get_list_checkbox_values( true ),
					'type'     => HELION_THEME_FREE ? 'hidden' : 'radio',
				),
				'header_wide_single'            => array(
					'title'      => esc_html__( 'Header fullwidth', 'helion' ),
					'desc'       => wp_kses_data( __( 'Do you want to stretch the header widgets area to the entire window width?', 'helion' ) ),
					'dependency' => array(
						'header_type_single' => array( 'default' ),
					),
					'std'      => 'inherit',
					'options'  => helion_get_list_checkbox_values( true ),
					'type'     => HELION_THEME_FREE ? 'hidden' : 'radio',
				),

				'blog_single_sidebar_info'      => array(
					'title' => esc_html__( 'Sidebar', 'helion' ),
					'desc'  => '',
					'type'  => 'info',
				),
				'sidebar_position_single'       => array(
					'title'   => esc_html__( 'Sidebar position', 'helion' ),
					'desc'    => wp_kses_data( __( 'Select position to show sidebar on the single posts', 'helion' ) ),
					'std'     => 'inherit',
					'override'   => array(
						'mode'    => 'post,product,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Widgets', 'helion' ),
					),
					'options' => array(),
					'type'    => 'radio',
				),
				'sidebar_position_ss_single'    => array(
					'title'    => esc_html__( 'Sidebar position on the small screen', 'helion' ),
					'desc'     => wp_kses_data( __( 'Select position to move sidebar on the single posts on the small screen - above or below the content', 'helion' ) ),
					'override' => array(
						'mode'    => 'post,product,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Widgets', 'helion' ),
					),
					'dependency' => array(
						'sidebar_position_single' => array( '^hide' ),
					),
					'std'      => 'below',
					'options'  => array(),
					'type'     => 'radio',
				),
				'sidebar_type_single'           => array(
					'title'    => esc_html__( 'Sidebar style', 'helion' ),
					'desc'     => wp_kses_data( __( 'Choose whether to use the default sidebar or sidebar Layouts (available only if the ThemeREX Addons is activated)', 'helion' ) ),
					'override'   => array(
						'mode'    => 'post,product,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Widgets', 'helion' ),
					),
					'dependency' => array(
						'sidebar_position_single' => array( '^hide' ),
					),
					'std'      => 'default',
					'options'  => helion_get_list_header_footer_types(),
					'type'     => HELION_THEME_FREE || ! helion_exists_trx_addons() ? 'hidden' : 'radio',
				),
				'sidebar_style_single'            => array(
					'title'      => esc_html__( 'Select custom layout', 'helion' ),
					'desc'       => wp_kses( __( 'Select custom sidebar from Layouts Builder', 'helion' ),'helion_kses_content' ),
					'override'   => array(
						'mode'    => 'post,product,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Widgets', 'helion' ),
					),
					'dependency' => array(
						'sidebar_position_single' => array( '^hide' ),
						'sidebar_type_single'     => array( 'custom' ),
					),
					'std'        => 'sidebar-custom-sidebar',
					'options'    => array(),
					'type'       => 'select',
				),
				'sidebar_widgets_single'        => array(
					'title'      => esc_html__( 'Sidebar widgets', 'helion' ),
					'desc'       => wp_kses_data( __( 'Select default widgets to show in the sidebar on the single posts', 'helion' ) ),
					'override'   => array(
						'mode'    => 'post,product,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Widgets', 'helion' ),
					),
					'dependency' => array(
						'sidebar_position_single' => array( '^hide' ),
						'sidebar_type_single'     => array( 'default' ),
					),
					'std'        => 'sidebar_widgets',
					'options'    => array(),
					'type'       => 'select',
				),
				'expand_content_single'         => array(
					'title'   => esc_html__( 'Expand content', 'helion' ),
					'desc'    => wp_kses_data( __( 'Expand the content width on the single posts if the sidebar is hidden', 'helion' ) ),
					'refresh' => false,
					'std'     => 'inherit',
					'options'  => helion_get_list_checkbox_values( true ),
					'type'     => HELION_THEME_FREE ? 'hidden' : 'radio',
				),

				'blog_single_title_info'        => array(
					'title' => esc_html__( 'Featured image and title', 'helion' ),
					'desc'  => '',
					'type'  => 'info',
				),
				'single_style'                  => array(
					'title'      => esc_html__( 'Single style', 'helion' ),
					'desc'       => '',
					'override'   => array(
						'mode'    => 'post',
						'section' => esc_html__( 'Content', 'helion' ),
					),
					'std'        => 'in-below',
					'qsetup'     => esc_html__( 'General', 'helion' ),
					'options'    => array(),
					'type'       => 'select',
				),
				'post_subtitle'                 => array(
					'title' => esc_html__( 'Post subtitle', 'helion' ),
					'desc'  => wp_kses_data( __( "Specify post subtitle to display it under the post title.", 'helion' ) ),
					'override' => array(
						'mode'    => 'post',
						'section' => esc_html__( 'Content', 'helion' ),
					),
					'std'   => '',
					'hidden' => true,
					'type'  => 'text',
				),
				'show_post_meta'                => array(
					'title' => esc_html__( 'Show post meta', 'helion' ),
					'desc'  => wp_kses_data( __( "Display block with post's meta: date, categories, counters, etc.", 'helion' ) ),
					'std'   => 1,
					'type'  => 'switch',
				),
				'meta_parts_single'             => array(
					'title'      => esc_html__( 'Post meta', 'helion' ),
					'desc'       => wp_kses_data( __( 'Meta parts for single posts. Post counters and Share Links are available only if plugin ThemeREX Addons is active', 'helion' ) )
								. '<br>'
								. wp_kses_data( __( '<b>Tip:</b> Drag items to change their order.', 'helion' ) ),
					'dependency' => array(
						'show_post_meta' => array( 1 ),
					),
					'dir'        => 'vertical',
					'sortable'   => true,
					'std'        => 'categories=1|date=1|views=0|likes=0|comments=0|author=0|share=0|edit=0',
					'options'    => helion_get_list_meta_parts(),
					'type'       => HELION_THEME_FREE ? 'hidden' : 'checklist',
				),
				'show_share_links'              => array(
					'title' => esc_html__( 'Show share links', 'helion' ),
					'desc'  => wp_kses_data( __( 'Display share links on the single post', 'helion' ) ),
					'std'   => 1,
					'type'  => ! helion_exists_trx_addons() ? 'hidden' : 'switch',
				),
				'show_author_info'              => array(
					'title' => esc_html__( 'Show author info', 'helion' ),
					'desc'  => wp_kses_data( __( "Display block with information about post's author", 'helion' ) ),
					'std'   => 1,
					'type'  => 'switch',
				),

				'blog_single_related_info'      => array(
					'title' => esc_html__( 'Related posts', 'helion' ),
					'desc'  => '',
					'type'  => 'info',
				),
				'show_related_posts'            => array(
					'title'    => esc_html__( 'Show related posts', 'helion' ),
					'desc'     => wp_kses_data( __( "Show section 'Related posts' on the single post's pages", 'helion' ) ),
					'override' => array(
						'mode'    => 'post,cpt_portfolio',
						'section' => esc_html__( 'Content', 'helion' ),
					),
					'std'      => 1,
					'type'     => 'switch',
				),
				'related_style'                 => array(
					'title'      => esc_html__( 'Related posts style', 'helion' ),
					'desc'       => wp_kses_data( __( 'Select style of the related posts output', 'helion' ) ),
					'override' => array(
						'mode'    => 'post',
						'section' => esc_html__( 'Content', 'helion' ),
					),
					'dependency' => array(
						'show_related_posts' => array( 1 ),
					),
					'std'        => 'classic',
					'options'    => array(
						'modern'  => esc_html__( 'Modern', 'helion' ),
						'classic' => esc_html__( 'Classic', 'helion' ),
						'wide'    => esc_html__( 'Wide', 'helion' ),
						'list'    => esc_html__( 'List', 'helion' ),
						'short'   => esc_html__( 'Short', 'helion' ),
					),
					'type'       => HELION_THEME_FREE ? 'hidden' : 'radio',
				),
				'related_position'              => array(
					'title'      => esc_html__( 'Related posts position', 'helion' ),
					'desc'       => wp_kses_data( __( 'Select position to display the related posts', 'helion' ) ),
					'override' => array(
						'mode'    => 'post',
						'section' => esc_html__( 'Content', 'helion' ),
					),
					'dependency' => array(
						'show_related_posts' => array( 1 ),
					),
					'std'        => 'below_content',
					'options'    => array (
						'inside'        => esc_html__( 'Inside the content (fullwidth)', 'helion' ),
						'inside_left'   => esc_html__( 'At left side of the content', 'helion' ),
						'inside_right'  => esc_html__( 'At right side of the content', 'helion' ),
						'below_content' => esc_html__( 'After the content', 'helion' ),
						'below_page'    => esc_html__( 'After the content & sidebar', 'helion' ),
					),
					'type'       => HELION_THEME_FREE ? 'hidden' : 'select',
				),
				'related_position_inside'       => array(
					'title'      => esc_html__( 'Before # paragraph', 'helion' ),
					'desc'       => wp_kses_data( __( 'Before what paragraph should related posts appear? If 0 - randomly.', 'helion' ) ),
					'override' => array(
						'mode'    => 'post',
						'section' => esc_html__( 'Content', 'helion' ),
					),
					'dependency' => array(
						'show_related_posts' => array( 1 ),
						'related_position' => array( 'inside', 'inside_left', 'inside_right' ),
					),
					'std'        => 2,
					'options'    => helion_get_list_range( 0, 9 ),
					'type'       => HELION_THEME_FREE ? 'hidden' : 'select',
				),
				'related_posts'                 => array(
					'title'      => esc_html__( 'Related posts', 'helion' ),
					'desc'       => wp_kses_data( __( 'How many related posts should be displayed in the single post? If 0 - no related posts are shown.', 'helion' ) ),
					'override' => array(
						'mode'    => 'post',
						'section' => esc_html__( 'Content', 'helion' ),
					),
					'dependency' => array(
						'show_related_posts' => array( 1 ),
					),
					'std'        => 2,
					'min'        => 1,
					'max'        => 9,
					'show_value' => true,
					'type'       => HELION_THEME_FREE ? 'hidden' : 'slider',
				),
				'related_columns'               => array(
					'title'      => esc_html__( 'Related columns', 'helion' ),
					'desc'       => wp_kses_data( __( 'How many columns should be used to output related posts in the single page?', 'helion' ) ),
					'override' => array(
						'mode'    => 'post',
						'section' => esc_html__( 'Content', 'helion' ),
					),
					'dependency' => array(
						'show_related_posts' => array( 1 ),
						'related_position' => array( 'inside', 'below_content', 'below_page' ),
					),
					'std'        => 2,
					'options'    => helion_get_list_range( 1, 6 ),
					'type'       => HELION_THEME_FREE ? 'hidden' : 'radio',
				),
				'related_slider'                => array(
					'title'      => esc_html__( 'Use slider layout', 'helion' ),
					'desc'       => wp_kses_data( __( 'Use slider layout in case related posts count is more than columns count', 'helion' ) ),
					'override' => array(
						'mode'    => 'post',
						'section' => esc_html__( 'Content', 'helion' ),
					),
					'dependency' => array(
						'show_related_posts' => array( 1 ),
					),
					'std'        => 0,
					'type'       => HELION_THEME_FREE ? 'hidden' : 'switch',
				),
				'related_slider_controls'       => array(
					'title'      => esc_html__( 'Slider controls', 'helion' ),
					'desc'       => wp_kses_data( __( 'Show arrows in the slider', 'helion' ) ),
					'override' => array(
						'mode'    => 'post',
						'section' => esc_html__( 'Content', 'helion' ),
					),
					'dependency' => array(
						'show_related_posts' => array( 1 ),
						'related_slider' => array( 1 ),
					),
					'std'        => 'none',
					'options'    => array(
						'none'    => esc_html__('None', 'helion'),
						'side'    => esc_html__('Side', 'helion'),
						'outside' => esc_html__('Outside', 'helion'),
						'top'     => esc_html__('Top', 'helion'),
						'bottom'  => esc_html__('Bottom', 'helion')
					),
					'type'       => HELION_THEME_FREE ? 'hidden' : 'select',
				),
				'related_slider_pagination'       => array(
					'title'      => esc_html__( 'Slider pagination', 'helion' ),
					'desc'       => wp_kses_data( __( 'Show bullets after the slider', 'helion' ) ),
					'override' => array(
						'mode'    => 'post',
						'section' => esc_html__( 'Content', 'helion' ),
					),
					'dependency' => array(
						'show_related_posts' => array( 1 ),
						'related_slider' => array( 1 ),
					),
					'std'        => 'bottom',
					'options'    => array(
						'none'    => esc_html__('None', 'helion'),
						'bottom'  => esc_html__('Bottom', 'helion')
					),
					'type'       => HELION_THEME_FREE ? 'hidden' : 'radio',
				),
				'related_slider_space'          => array(
					'title'      => esc_html__( 'Space', 'helion' ),
					'desc'       => wp_kses_data( __( 'Space between slides', 'helion' ) ),
					'override' => array(
						'mode'    => 'post',
						'section' => esc_html__( 'Content', 'helion' ),
					),
					'dependency' => array(
						'show_related_posts' => array( 1 ),
						'related_slider' => array( 1 ),
					),
					'std'        => 30,
					'type'       => HELION_THEME_FREE ? 'hidden' : 'text',
				),
				'posts_navigation_info'      => array(
					'title' => esc_html__( 'Posts navigation', 'helion' ),
					'desc'  => '',
					'type'  => 'info',
				),
				'posts_navigation'           => array(
					'title'   => esc_html__( 'Show posts navigation', 'helion' ),
					'desc'    => wp_kses_data( __( "Show posts navigation on the single post's pages", 'helion' ) ),
					'std'     => 'links',
					'options' => array(
						'none'   => esc_html__('None', 'helion'),
						'links'  => esc_html__('Prev/Next links', 'helion'),
						'scroll' => esc_html__('Infinite scroll', 'helion')
					),
					'type'    => HELION_THEME_FREE ? 'hidden' : 'radio',
				),
				'posts_navigation_fixed'     => array(
					'title'      => esc_html__( 'Fixed posts navigation', 'helion' ),
					'desc'       => wp_kses_data( __( "Make posts navigation fixed position. Display it when the content of the article is inside the window.", 'helion' ) ),
					'dependency' => array(
						'posts_navigation' => array( 'links' ),
					),
					'std'        => 0,
					'type'       => HELION_THEME_FREE ? 'hidden' : 'switch',
				),
				'posts_navigation_scroll_hide_author'  => array(
					'title'      => esc_html__( 'Hide author bio', 'helion' ),
					'desc'       => wp_kses_data( __( "Hide author bio after post content when infinite scroll is used.", 'helion' ) ),
					'dependency' => array(
						'posts_navigation' => array( 'scroll' ),
					),
					'std'        => 0,
					'type'       => HELION_THEME_FREE ? 'hidden' : 'switch',
				),
				'posts_navigation_scroll_hide_related'  => array(
					'title'      => esc_html__( 'Hide related posts', 'helion' ),
					'desc'       => wp_kses_data( __( "Hide related posts after post content when infinite scroll is used.", 'helion' ) ),
					'dependency' => array(
						'posts_navigation' => array( 'scroll' ),
					),
					'std'        => 0,
					'type'       => HELION_THEME_FREE ? 'hidden' : 'switch',
				),
				'posts_navigation_scroll_hide_comments' => array(
					'title'      => esc_html__( 'Hide comments', 'helion' ),
					'desc'       => wp_kses_data( __( "Hide comments after post content when infinite scroll is used.", 'helion' ) ),
					'dependency' => array(
						'posts_navigation' => array( 'scroll' ),
					),
					'std'        => 1,
					'type'       => HELION_THEME_FREE ? 'hidden' : 'switch',
				),
				'posts_banners_info'      => array(
					'title' => esc_html__( 'Posts banners', 'helion' ),
					'desc'  => '',
					'type'  => 'info',
				),
				'header_banner_link'     => array(
					'title' => esc_html__( 'Header banner link', 'helion' ),
					'desc'  => wp_kses_data( __( 'Insert URL of the banner', 'helion' ) ),
					'override'   => array(
						'mode'    => 'post',
						'section' => esc_html__( 'Banners', 'helion' ),
					),
					'std'   => '',
					'type'  => 'text',
				),
				'header_banner_img'     => array(
					'title' => esc_html__( 'Header banner image', 'helion' ),
					'desc'  => wp_kses_data( __( 'Select image to display at the backgound', 'helion' ) ),
					'override'   => array(
						'mode'    => 'post',
						'section' => esc_html__( 'Banners', 'helion' ),
					),
					'std'        => '',
					'type'       => 'image',
				),
				'header_banner_height'  => array(
					'title' => esc_html__( 'Header banner height', 'helion' ),
					'desc'  => wp_kses_data( __( 'Specify minimal height of the banner (in "px" or "em"). For example: 15em', 'helion' ) ),
					'override'   => array(
						'mode'    => 'post',
						'section' => esc_html__( 'Banners', 'helion' ),
					),
					'std'        => '',
					'type'       => 'text',
				),
				'header_banner_code'     => array(
					'title'      => esc_html__( 'Header banner code', 'helion' ),
					'desc'       => wp_kses_data( __( 'Embed html code', 'helion' ) ),
					'override'   => array(
						'mode'    => 'post',
						'section' => esc_html__( 'Banners', 'helion' ),
					),
					'std'        => '',
					'allow_html' => true,
					'type'       => 'textarea',
				),
				'footer_banner_link'     => array(
					'title' => esc_html__( 'Footer banner link', 'helion' ),
					'desc'  => wp_kses_data( __( 'Insert URL of the banner', 'helion' ) ),
					'override'   => array(
						'mode'    => 'post',
						'section' => esc_html__( 'Banners', 'helion' ),
					),
					'std'   => '',
					'type'  => 'text',
				),
				'footer_banner_img'     => array(
					'title' => esc_html__( 'Footer banner image', 'helion' ),
					'desc'  => wp_kses_data( __( 'Select image to display at the backgound', 'helion' ) ),
					'override'   => array(
						'mode'    => 'post',
						'section' => esc_html__( 'Banners', 'helion' ),
					),
					'std'        => '',
					'type'       => 'image',
				),
				'footer_banner_height'  => array(
					'title' => esc_html__( 'Footer banner height', 'helion' ),
					'desc'  => wp_kses_data( __( 'Specify minimal height of the banner (in "px" or "em"). For example: 15em', 'helion' ) ),
					'override'   => array(
						'mode'    => 'post',
						'section' => esc_html__( 'Banners', 'helion' ),
					),
					'std'        => '',
					'type'       => 'text',
				),
				'footer_banner_code'     => array(
					'title'      => esc_html__( 'Footer banner code', 'helion' ),
					'desc'       => wp_kses_data( __( 'Embed html code', 'helion' ) ),
					'override'   => array(
						'mode'    => 'post',
						'section' => esc_html__( 'Banners', 'helion' ),
					),
					'std'        => '',
					'allow_html' => true,
					'type'       => 'textarea',
				),
				'sidebar_banner_link'     => array(
					'title' => esc_html__( 'Sidebar banner link', 'helion' ),
					'desc'  => wp_kses_data( __( 'Insert URL of the banner', 'helion' ) ),
					'override'   => array(
						'mode'    => 'post',
						'section' => esc_html__( 'Banners', 'helion' ),
					),
					'std'   => '',
					'type'  => 'text',
				),
				'sidebar_banner_img'     => array(
					'title' => esc_html__( 'Sidebar banner image', 'helion' ),
					'desc'  => wp_kses_data( __( 'Select image to display at the backgound', 'helion' ) ),
					'override'   => array(
						'mode'    => 'post',
						'section' => esc_html__( 'Banners', 'helion' ),
					),
					'std'        => '',
					'type'       => 'image',
				),
				'sidebar_banner_code'     => array(
					'title'      => esc_html__( 'Sidebar banner code', 'helion' ),
					'desc'       => wp_kses_data( __( 'Embed html code', 'helion' ) ),
					'override'   => array(
						'mode'    => 'post',
						'section' => esc_html__( 'Banners', 'helion' ),
					),
					'std'        => '',
					'allow_html' => true,
					'type'       => 'textarea',
				),
				'background_banner_link'     => array(
					'title' => esc_html__( "Post's background banner link", 'helion' ),
					'desc'  => wp_kses_data( __( 'Insert URL of the banner', 'helion' ) ),
					'override'   => array(
						'mode'    => 'post',
						'section' => esc_html__( 'Banners', 'helion' ),
					),
					'std'   => '',
					'type'  => 'text',
				),
				'background_banner_img'     => array(
					'title' => esc_html__( "Post's background banner image", 'helion' ),
					'desc'  => wp_kses_data( __( 'Select image to display at the backgound', 'helion' ) ),
					'override'   => array(
						'mode'    => 'post',
						'section' => esc_html__( 'Banners', 'helion' ),
					),
					'std'        => '',
					'type'       => 'image',
				),
				'background_banner_code'     => array(
					'title'      => esc_html__( "Post's background banner code", 'helion' ),
					'desc'       => wp_kses_data( __( 'Embed html code', 'helion' ) ),
					'override'   => array(
						'mode'    => 'post',
						'section' => esc_html__( 'Banners', 'helion' ),
					),
					'std'        => '',
					'allow_html' => true,
					'type'       => 'textarea',
				),
				'blog_end'                      => array(
					'type' => 'panel_end',
				),



				// 'Colors'
				//---------------------------------------------
				'panel_colors'                  => array(
					'title'    => esc_html__( 'Colors', 'helion' ),
					'desc'     => '',
					'priority' => 300,
					'icon'     => 'icon-customizer',
					'type'     => 'section',
				),

				'color_schemes_info'            => array(
					'title'  => esc_html__( 'Color schemes', 'helion' ),
					'desc'   => wp_kses_data( __( 'Color schemes for various parts of the site. "Inherit" means that this block is used the Site color scheme (the first parameter)', 'helion' ) ),
					'hidden' => $hide_schemes,
					'type'   => 'info',
				),
				'color_scheme'                  => array(
					'title'    => esc_html__( 'Site Color Scheme', 'helion' ),
					'desc'     => '',
					'override' => array(
						'mode'    => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Colors', 'helion' ),
					),
					'std'      => 'default',
					'options'  => array(),
					'refresh'  => false,
					'type'     => $hide_schemes ? 'hidden' : 'radio',
				),
				'header_scheme'                 => array(
					'title'    => esc_html__( 'Header Color Scheme', 'helion' ),
					'desc'     => '',
					'override' => array(
						'mode'    => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Colors', 'helion' ),
					),
					'std'      => 'inherit',
					'options'  => array(),
					'refresh'  => false,
					'type'     => $hide_schemes ? 'hidden' : 'radio',
				),
				'menu_scheme'                   => array(
					'title'    => esc_html__( 'Sidemenu Color Scheme', 'helion' ),
					'desc'     => '',
					'override' => array(
						'mode'    => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Colors', 'helion' ),
					),
					'std'      => 'inherit',
					'options'  => array(),
					'refresh'  => false,
					'type'     => $hide_schemes || HELION_THEME_FREE ? 'hidden' : 'radio',
				),
				'sidebar_scheme'                => array(
					'title'    => esc_html__( 'Sidebar Color Scheme', 'helion' ),
					'desc'     => '',
					'override' => array(
						'mode'    => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Colors', 'helion' ),
					),
					'std'      => 'inherit',
					'options'  => array(),
					'refresh'  => false,
					'type'     => $hide_schemes ? 'hidden' : 'radio',
				),
				'footer_scheme'                 => array(
					'title'    => esc_html__( 'Footer Color Scheme', 'helion' ),
					'desc'     => '',
					'override' => array(
						'mode'    => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
						'section' => esc_html__( 'Colors', 'helion' ),
					),
					'std'      => 'dark',
					'options'  => array(),
					'refresh'  => false,
					'type'     => $hide_schemes ? 'hidden' : 'radio',
				),

				'color_scheme_editor_info'      => array(
					'title' => esc_html__( 'Color scheme editor', 'helion' ),
					'desc'  => wp_kses_data( __( 'Select color scheme to modify. Attention! Only those sections in the site will be changed which this scheme was assigned to', 'helion' ) ),
					'type'  => 'info',
				),
				'scheme_storage'                => array(
					'title'       => esc_html__( 'Color scheme editor', 'helion' ),
					'desc'        => '',
					'std'         => '$helion_get_scheme_storage',
					'refresh'     => false,
					'colorpicker' => 'spectrum', //'tiny',
					'type'        => 'scheme_editor',
				),

				// Internal options.
				// Attention! Don't change any options in the section below!
				// Use huge priority to call render this elements after all options!
				'reset_options'                 => array(
					'title'    => '',
					'desc'     => '',
					'std'      => '0',
					'priority' => 10000,
					'type'     => 'hidden',
				),

				'last_option'                   => array(     // Need to manually call action to include Tiny MCE scripts
					'title' => '',
					'desc'  => '',
					'std'   => 1,
					'type'  => 'hidden',
				),

			)
		);



		// Prepare panel 'Fonts'
		// -------------------------------------------------------------
		$fonts = array(

			// 'Fonts'
			//---------------------------------------------
			'fonts'             => array(
				'title'    => esc_html__( 'Typography', 'helion' ),
				'desc'     => '',
				'priority' => 200,
				'icon'     => 'icon-text',
				'type'     => 'panel',
			),

			// Fonts - Load_fonts
			'load_fonts'        => array(
				'title' => esc_html__( 'Load fonts', 'helion' ),
				'desc'  => wp_kses_data( __( 'Specify fonts to load when theme start. You can use them in the base theme elements: headers, text, menu, links, input fields, etc.', 'helion' ) )
						. wp_kses_data( __( 'Press "Refresh" button to reload preview area after the all fonts are changed', 'helion' ) ),
				'type'  => 'section',
			),
			'load_fonts_info'   => array(
				'title' => esc_html__( 'Load fonts', 'helion' ),
				'desc'  => '',
				'type'  => 'info',
			),
			'load_fonts_subset' => array(
				'title'   => esc_html__( 'Google fonts subsets', 'helion' ),
				'desc'    => wp_kses_data( __( 'Specify comma separated list of the subsets which will be load from Google fonts', 'helion' ) )
						. wp_kses_data( __( 'Available subsets are: latin,latin-ext,cyrillic,cyrillic-ext,greek,greek-ext,vietnamese', 'helion' ) ),
				'class'   => 'helion_column-1_3 helion_new_row',
				'refresh' => false,
				'std'     => '$helion_get_load_fonts_subset',
				'type'    => 'text',
			),
		);

		for ( $i = 1; $i <= helion_get_theme_setting( 'max_load_fonts' ); $i++ ) {
			if ( helion_get_value_gp( 'page' ) != 'theme_options' ) {
				$fonts[ "load_fonts-{$i}-info" ] = array(
					// Translators: Add font's number - 'Font 1', 'Font 2', etc
					'title' => esc_html( sprintf( __( 'Font %s', 'helion' ), $i ) ),
					'desc'  => '',
					'type'  => 'info',
				);
			}
			$fonts[ "load_fonts-{$i}-name" ]   = array(
				'title'   => esc_html__( 'Font name', 'helion' ),
				'desc'    => '',
				'class'   => 'helion_column-1_3 helion_new_row',
				'refresh' => false,
				'std'     => '$helion_get_load_fonts_option',
				'type'    => 'text',
			);
			$fonts[ "load_fonts-{$i}-family" ] = array(
				'title'   => esc_html__( 'Font family', 'helion' ),
				'desc'    => 1 == $i
							? wp_kses_data( __( 'Select font family to use it if font above is not available', 'helion' ) )
							: '',
				'class'   => 'helion_column-1_3',
				'refresh' => false,
				'std'     => '$helion_get_load_fonts_option',
				'options' => array(
					'inherit'    => esc_html__( 'Inherit', 'helion' ),
					'serif'      => esc_html__( 'serif', 'helion' ),
					'sans-serif' => esc_html__( 'sans-serif', 'helion' ),
					'monospace'  => esc_html__( 'monospace', 'helion' ),
					'cursive'    => esc_html__( 'cursive', 'helion' ),
					'fantasy'    => esc_html__( 'fantasy', 'helion' ),
				),
				'type'    => 'select',
			);
			$fonts[ "load_fonts-{$i}-styles" ] = array(
				'title'   => esc_html__( 'Font styles', 'helion' ),
				'desc'    => 1 == $i
							? wp_kses_data( __( 'Font styles used only for the Google fonts. This is a comma separated list of the font weight and styles. For example: 400,400italic,700', 'helion' ) )
								. '<br>'
								. wp_kses_data( __( 'Attention! Each weight and style increase download size! Specify only used weights and styles.', 'helion' ) )
							: '',
				'class'   => 'helion_column-1_3',
				'refresh' => false,
				'std'     => '$helion_get_load_fonts_option',
				'type'    => 'text',
			);
		}
		$fonts['load_fonts_end'] = array(
			'type' => 'section_end',
		);

		// Fonts - H1..6, P, Info, Menu, etc.
		$theme_fonts = helion_get_theme_fonts();
		foreach ( $theme_fonts as $tag => $v ) {
			$fonts[ "{$tag}_section" ] = array(
				'title' => ! empty( $v['title'] )
								? $v['title']
								// Translators: Add tag's name to make title 'H1 settings', 'P settings', etc.
								: esc_html( sprintf( __( '%s settings', 'helion' ), $tag ) ),
				'desc'  => ! empty( $v['description'] )
								? $v['description']
								// Translators: Add tag's name to make description
								: wp_kses( sprintf( __( 'Font settings of the "%s" tag.', 'helion' ), $tag ),'helion_kses_content' ),
				'type'  => 'section',
			);
			$fonts[ "{$tag}_info" ] = array(
				'title' => ! empty( $v['title'] )
								? $v['title']
								// Translators: Add tag's name to make title 'H1 settings', 'P settings', etc.
								: esc_html( sprintf( __( '%s settings', 'helion' ), $tag ) ),
				'desc'  => '',
				'type'  => 'info',
			);
			foreach ( $v as $css_prop => $css_value ) {
				if ( in_array( $css_prop, array( 'title', 'description' ) ) ) {
					continue;
				}
				// Skip property 'text-decoration' for the main text
				if ( 'text-decoration' == $css_prop && 'p' == $tag ) {
					continue;
				}

				$options    = '';
				$type       = 'text';
				$load_order = 1;
				$title      = ucfirst( str_replace( '-', ' ', $css_prop ) );
				if ( 'font-family' == $css_prop ) {
					$type       = 'select';
					$options    = array();
					$load_order = 2;        // Load this option's value after all options are loaded (use option 'load_fonts' to build fonts list)
				} elseif ( 'font-weight' == $css_prop ) {
					$type    = 'select';
					$options = array(
						'inherit' => esc_html__( 'Inherit', 'helion' ),
						'100'     => esc_html__( '100 (Light)', 'helion' ),
						'200'     => esc_html__( '200 (Light)', 'helion' ),
						'300'     => esc_html__( '300 (Thin)', 'helion' ),
						'400'     => esc_html__( '400 (Normal)', 'helion' ),
						'500'     => esc_html__( '500 (Semibold)', 'helion' ),
						'600'     => esc_html__( '600 (Semibold)', 'helion' ),
						'700'     => esc_html__( '700 (Bold)', 'helion' ),
						'800'     => esc_html__( '800 (Black)', 'helion' ),
						'900'     => esc_html__( '900 (Black)', 'helion' ),
					);
				} elseif ( 'font-style' == $css_prop ) {
					$type    = 'select';
					$options = array(
						'inherit' => esc_html__( 'Inherit', 'helion' ),
						'normal'  => esc_html__( 'Normal', 'helion' ),
						'italic'  => esc_html__( 'Italic', 'helion' ),
					);
				} elseif ( 'text-decoration' == $css_prop ) {
					$type    = 'select';
					$options = array(
						'inherit'      => esc_html__( 'Inherit', 'helion' ),
						'none'         => esc_html__( 'None', 'helion' ),
						'underline'    => esc_html__( 'Underline', 'helion' ),
						'overline'     => esc_html__( 'Overline', 'helion' ),
						'line-through' => esc_html__( 'Line-through', 'helion' ),
					);
				} elseif ( 'text-transform' == $css_prop ) {
					$type    = 'select';
					$options = array(
						'inherit'    => esc_html__( 'Inherit', 'helion' ),
						'none'       => esc_html__( 'None', 'helion' ),
						'uppercase'  => esc_html__( 'Uppercase', 'helion' ),
						'lowercase'  => esc_html__( 'Lowercase', 'helion' ),
						'capitalize' => esc_html__( 'Capitalize', 'helion' ),
					);
				}
				$fonts[ "{$tag}_{$css_prop}" ] = array(
					'title'      => $title,
					'desc'       => '',
					'class'      => 'helion_column-1_5',
					'refresh'    => false,
					'load_order' => $load_order,
					'std'        => '$helion_get_theme_fonts_option',
					'options'    => $options,
					'type'       => $type,
				);
			}

			$fonts[ "{$tag}_section_end" ] = array(
				'type' => 'section_end',
			);
		}

		$fonts['fonts_end'] = array(
			'type' => 'panel_end',
		);

		// Add fonts parameters to Theme Options
		helion_storage_set_array_before( 'options', 'panel_colors', $fonts );

		// Add Header Video if WP version < 4.7
		// -----------------------------------------------------
		if ( ! function_exists( 'get_header_video_url' ) ) {
			helion_storage_set_array_after(
				'options', 'header_image_override', 'header_video', array(
					'title'    => esc_html__( 'Header video', 'helion' ),
					'desc'     => wp_kses_data( __( 'Select video to use it as background for the header', 'helion' ) ),
					'override' => array(
						'mode'    => 'page',
						'section' => esc_html__( 'Header', 'helion' ),
					),
					'std'      => '',
					'type'     => 'video',
				)
			);
		}

		// Add option 'logo' if WP version < 4.5
		// or 'custom_logo' if current page is not 'Customize'
		// ------------------------------------------------------
		if ( ! function_exists( 'the_custom_logo' ) || ! helion_check_url( 'customize.php' ) ) {
			helion_storage_set_array_before(
				'options', 'logo_retina', function_exists( 'the_custom_logo' ) ? 'custom_logo' : 'logo', array(
					'title'    => esc_html__( 'Logo', 'helion' ),
					'desc'     => wp_kses_data( __( 'Select or upload the site logo', 'helion' ) ),
					'priority' => 60,
					'std'      => '',
					'qsetup'   => esc_html__( 'General', 'helion' ),
					'type'     => 'image',
				)
			);
		}

	}
}


// Returns a list of options that can be overridden for CPT
if ( ! function_exists( 'helion_options_get_list_cpt_options' ) ) {
	function helion_options_get_list_cpt_options( $cpt, $title = '' ) {
		if ( empty( $title ) ) {
			$title = ucfirst( $cpt );
		}
		return array(
			"content_info_{$cpt}"           => array(
				'title' => esc_html__( 'Content', 'helion' ),
				'desc'  => '',
				'type'  => 'info',
			),
			"body_style_{$cpt}"             => array(
				'title'    => esc_html__( 'Body style', 'helion' ),
				'desc'     => wp_kses_data( __( 'Select width of the body content', 'helion' ) ),
				'std'      => 'inherit',
				'options'  => helion_get_list_body_styles( true ),
				'type'     => 'select',
			),
			"boxed_bg_image_{$cpt}"         => array(
				'title'      => esc_html__( 'Boxed bg image', 'helion' ),
				'desc'       => wp_kses_data( __( 'Select or upload image, used as background in the boxed body', 'helion' ) ),
				'dependency' => array(
					"body_style_{$cpt}" => array( 'boxed' ),
				),
				'std'        => 'inherit',
				'type'       => 'image',
			),
			"header_info_{$cpt}"            => array(
				'title' => esc_html__( 'Header', 'helion' ),
				'desc'  => '',
				'type'  => 'info',
			),
			"header_type_{$cpt}"            => array(
				'title'   => esc_html__( 'Header style', 'helion' ),
				'desc'    => wp_kses_data( __( 'Choose whether to use the default header or header Layouts (available only if the ThemeREX Addons is activated)', 'helion' ) ),
				'std'     => 'inherit',
				'options' => helion_get_list_header_footer_types( true ),
				'type'    => HELION_THEME_FREE ? 'hidden' : 'radio',
			),
			"header_style_{$cpt}"           => array(
				'title'      => esc_html__( 'Select custom layout', 'helion' ),
				// Translators: Add CPT name to the description
				'desc'       => wp_kses_data( sprintf( __( 'Select custom layout to display the site header on the %s pages', 'helion' ), $title ) ),
				'dependency' => array(
					"header_type_{$cpt}" => array( 'custom' ),
				),
				'std'        => 'inherit',
				'options'    => array(),
				'type'       => HELION_THEME_FREE ? 'hidden' : 'select',
			),
			"header_position_{$cpt}"        => array(
				'title'   => esc_html__( 'Header position', 'helion' ),
				// Translators: Add CPT name to the description
				'desc'    => wp_kses_data( sprintf( __( 'Select position to display the site header on the %s pages', 'helion' ), $title ) ),
				'std'     => 'inherit',
				'options' => array(),
				'type'    => HELION_THEME_FREE ? 'hidden' : 'radio',
			),
			"header_image_override_{$cpt}"  => array(
				'title'   => esc_html__( 'Header image override', 'helion' ),
				'desc'    => wp_kses_data( __( "Allow override the header image with the post's featured image", 'helion' ) ),
				'std'     => 'inherit',
				'options' => array(
					'inherit' => esc_html__( 'Inherit', 'helion' ),
					1         => esc_html__( 'Yes', 'helion' ),
					0         => esc_html__( 'No', 'helion' ),
				),
				'type'    => HELION_THEME_FREE ? 'hidden' : 'radio',
			),
			"header_widgets_{$cpt}"         => array(
				'title'   => esc_html__( 'Header widgets', 'helion' ),
				// Translators: Add CPT name to the description
				'desc'    => wp_kses_data( sprintf( __( 'Select set of widgets to show in the header on the %s pages', 'helion' ), $title ) ),
				'std'     => 'hide',
				'options' => array(),
				'type'    => 'select',
			),

			"sidebar_info_{$cpt}"           => array(
				'title' => esc_html__( 'Sidebar', 'helion' ),
				'desc'  => '',
				'type'  => 'info',
			),
			"sidebar_position_{$cpt}"       => array(
				// Translators: Add CPT name to the title
				'title'   => sprintf( __( 'Sidebar position on the %s list', 'helion' ), $title ),
				// Translators: Add CPT name to the description
				'desc'    => wp_kses_data( sprintf( __( 'Select position to show sidebar on the %s list', 'helion' ), $title ) ),
				'std'     => 'left',
				'options' => array(),
				'type'    => 'radio',
			),
			"sidebar_position_ss_{$cpt}"    => array(
				// Translators: Add CPT name to the title
				'title'    => sprintf( __( 'Sidebar position on the %s list on the small screen', 'helion' ), $title ),
				'desc'     => wp_kses_data( __( 'Select position to move sidebar on the small screen - above or below the content', 'helion' ) ),
				'std'      => 'below',
				'dependency' => array(
					"sidebar_position_{$cpt}" => array( '^hide' ),
				),
				'options'  => array(),
				'type'     => 'radio',
			),
			"sidebar_type_{$cpt}"           => array(
				// Translators: Add CPT name to the title
				'title'    => sprintf( __( 'Sidebar style on the %s list', 'helion' ), $title ),
				'desc'     => wp_kses_data( __( 'Choose whether to use the default sidebar or sidebar Layouts (available only if the ThemeREX Addons is activated)', 'helion' ) ),
				'dependency' => array(
					"sidebar_position_{$cpt}" => array( '^hide' ),
				),
				'std'      => 'default',
				'options'  => helion_get_list_header_footer_types(),
				'type'     => HELION_THEME_FREE || ! helion_exists_trx_addons() ? 'hidden' : 'radio',
			),
			"sidebar_style_{$cpt}"          => array(
				'title'      => esc_html__( 'Select custom layout', 'helion' ),
				'desc'       => wp_kses( __( 'Select custom sidebar from Layouts Builder', 'helion' ),'helion_kses_content' ),
				'dependency' => array(
					"sidebar_position_{$cpt}" => array( '^hide' ),
					"sidebar_type_{$cpt}"     => array( 'custom' ),
				),
				'std'        => 'sidebar-custom-sidebar',
				'options'    => array(),
				'type'       => 'select',
			),
			"sidebar_widgets_{$cpt}"        => array(
				// Translators: Add CPT name to the title
				'title'      => sprintf( __( 'Sidebar widgets on the %s list', 'helion' ), $title ),
				// Translators: Add CPT name to the description
				'desc'       => wp_kses_data( sprintf( __( 'Select sidebar to show on the %s list', 'helion' ), $title ) ),
				'dependency' => array(
					"sidebar_position_{$cpt}" => array( '^hide' ),
					"sidebar_type_{$cpt}"     => array( 'default' ),
				),
				'std'        => 'hide',
				'options'    => array(),
				'type'       => 'select',
			),
			"sidebar_position_single_{$cpt}"       => array(
				'title'   => esc_html__( 'Sidebar position on the single post', 'helion' ),
				// Translators: Add CPT name to the description
				'desc'    => wp_kses_data( sprintf( __( 'Select position to show sidebar on the single posts of the %s', 'helion' ), $title ) ),
				'std'     => 'left',
				'options' => array(),
				'type'    => 'radio',
			),
			"sidebar_position_ss_single_{$cpt}"    => array(
				'title'    => esc_html__( 'Sidebar position on the single post on the small screen', 'helion' ),
				'desc'     => wp_kses_data( __( 'Select position to move sidebar on the small screen - above or below the content', 'helion' ) ),
				'dependency' => array(
					"sidebar_position_single_{$cpt}" => array( '^hide' ),
				),
				'std'      => 'below',
				'options'  => array(),
				'type'     => 'radio',
			),
			"sidebar_type_single_{$cpt}"           => array(
				// Translators: Add CPT name to the title
				'title'    => esc_html__( 'Sidebar style on the single post', 'helion' ),
				'desc'     => wp_kses_data( __( 'Choose whether to use the default sidebar or sidebar Layouts (available only if the ThemeREX Addons is activated)', 'helion' ) ),
				'dependency' => array(
					"sidebar_position_single_{$cpt}" => array( '^hide' ),
				),
				'std'      => 'default',
				'options'  => helion_get_list_header_footer_types(),
				'type'     => HELION_THEME_FREE || ! helion_exists_trx_addons() ? 'hidden' : 'radio',
			),
			"sidebar_style_single_{$cpt}"          => array(
				'title'      => esc_html__( 'Select custom layout', 'helion' ),
				'desc'       => wp_kses( __( 'Select custom sidebar from Layouts Builder', 'helion' ),'helion_kses_content' ),
				'dependency' => array(
					"sidebar_position_single_{$cpt}" => array( '^hide' ),
					"sidebar_type_single_{$cpt}"     => array( 'custom' ),
				),
				'std'        => 'sidebar-custom-sidebar',
				'options'    => array(),
				'type'       => 'select',
			),
			"sidebar_widgets_single_{$cpt}"        => array(
				'title'      => esc_html__( 'Sidebar widgets on the single post', 'helion' ),
				// Translators: Add CPT name to the description
				'desc'       => wp_kses_data( sprintf( __( 'Select widgets to show in the sidebar on the single posts of the %s', 'helion' ), $title ) ),
				'dependency' => array(
					"sidebar_position_single_{$cpt}" => array( '^hide' ),
					"sidebar_type_single_{$cpt}"     => array( 'default' ),
				),
				'std'        => 'hide',
				'options'    => array(),
				'type'       => 'select',
			),
			"expand_content_{$cpt}"         => array(
				'title'   => esc_html__( 'Expand content', 'helion' ),
				'desc'    => wp_kses_data( __( 'Expand the content width if the sidebar is hidden', 'helion' ) ),
				'refresh' => false,
				'std'     => 'inherit',
				'options'  => helion_get_list_checkbox_values( true ),
				'type'     => HELION_THEME_FREE ? 'hidden' : 'radio',
			),
			"expand_content_single_{$cpt}"         => array(
				'title'   => esc_html__( 'Expand content on the single post', 'helion' ),
				'desc'    => wp_kses_data( __( 'Expand the content width on the single post if the sidebar is hidden', 'helion' ) ),
				'refresh' => false,
				'std'     => 'inherit',
				'options'  => helion_get_list_checkbox_values( true ),
				'type'     => HELION_THEME_FREE ? 'hidden' : 'radio',
			),

			"footer_info_{$cpt}"            => array(
				'title' => esc_html__( 'Footer', 'helion' ),
				'desc'  => '',
				'type'  => 'info',
			),
			"footer_type_{$cpt}"            => array(
				'title'   => esc_html__( 'Footer style', 'helion' ),
				'desc'    => wp_kses_data( __( 'Choose whether to use the default footer or footer Layouts (available only if the ThemeREX Addons is activated)', 'helion' ) ),
				'std'     => 'inherit',
				'options' => helion_get_list_header_footer_types( true ),
				'type'    => HELION_THEME_FREE ? 'hidden' : 'radio',
			),
			"footer_style_{$cpt}"           => array(
				'title'      => esc_html__( 'Select custom layout', 'helion' ),
				'desc'       => wp_kses_data( __( 'Select custom layout to display the site footer', 'helion' ) ),
				'std'        => 'inherit',
				'dependency' => array(
					"footer_type_{$cpt}" => array( 'custom' ),
				),
				'options'    => array(),
				'type'       => HELION_THEME_FREE ? 'hidden' : 'select',
			),
			"footer_widgets_{$cpt}"         => array(
				'title'      => esc_html__( 'Footer widgets', 'helion' ),
				'desc'       => wp_kses_data( __( 'Select set of widgets to show in the footer', 'helion' ) ),
				'dependency' => array(
					"footer_type_{$cpt}" => array( 'default' ),
				),
				'std'        => 'footer_widgets',
				'options'    => array(),
				'type'       => 'select',
			),
			"footer_columns_{$cpt}"         => array(
				'title'      => esc_html__( 'Footer columns', 'helion' ),
				'desc'       => wp_kses_data( __( 'Select number columns to show widgets in the footer. If 0 - autodetect by the widgets count', 'helion' ) ),
				'dependency' => array(
					"footer_type_{$cpt}"    => array( 'default' ),
					"footer_widgets_{$cpt}" => array( '^hide' ),
				),
				'std'        => 0,
				'options'    => helion_get_list_range( 0, 6 ),
				'type'       => 'select',
			),
			"footer_wide_{$cpt}"            => array(
				'title'      => esc_html__( 'Footer fullwidth', 'helion' ),
				'desc'       => wp_kses_data( __( 'Do you want to stretch the footer to the entire window width?', 'helion' ) ),
				'dependency' => array(
					"footer_type_{$cpt}" => array( 'default' ),
				),
				'std'        => 0,
				'type'       => 'switch',
			),

			"widgets_info_{$cpt}"           => array(
				'title' => esc_html__( 'Additional panels', 'helion' ),
				'desc'  => '',
				'type'  => HELION_THEME_FREE ? 'hidden' : 'info',
			),
			"widgets_above_page_{$cpt}"     => array(
				'title'   => esc_html__( 'Widgets at the top of the page', 'helion' ),
				'desc'    => wp_kses_data( __( 'Select widgets to show at the top of the page (above content and sidebar)', 'helion' ) ),
				'std'     => 'hide',
				'options' => array(),
				'type'    => HELION_THEME_FREE ? 'hidden' : 'select',
			),
			"widgets_above_content_{$cpt}"  => array(
				'title'   => esc_html__( 'Widgets above the content', 'helion' ),
				'desc'    => wp_kses_data( __( 'Select widgets to show at the beginning of the content area', 'helion' ) ),
				'std'     => 'hide',
				'options' => array(),
				'type'    => HELION_THEME_FREE ? 'hidden' : 'select',
			),
			"widgets_below_content_{$cpt}"  => array(
				'title'   => esc_html__( 'Widgets below the content', 'helion' ),
				'desc'    => wp_kses_data( __( 'Select widgets to show at the ending of the content area', 'helion' ) ),
				'std'     => 'hide',
				'options' => array(),
				'type'    => HELION_THEME_FREE ? 'hidden' : 'select',
			),
			"widgets_below_page_{$cpt}"     => array(
				'title'   => esc_html__( 'Widgets at the bottom of the page', 'helion' ),
				'desc'    => wp_kses_data( __( 'Select widgets to show at the bottom of the page (below content and sidebar)', 'helion' ) ),
				'std'     => 'hide',
				'options' => array(),
				'type'    => HELION_THEME_FREE ? 'hidden' : 'select',
			),
		);
	}
}


// Return lists with choises when its need in the admin mode
if ( ! function_exists( 'helion_options_get_list_choises' ) ) {
	add_filter( 'helion_filter_options_get_list_choises', 'helion_options_get_list_choises', 10, 2 );
	function helion_options_get_list_choises( $list, $id ) {
		if ( is_array( $list ) && count( $list ) == 0 ) {
			if ( strpos( $id, 'header_style' ) === 0 ) {
				$list = helion_get_list_header_styles( strpos( $id, 'header_style_' ) === 0 );
			} elseif ( strpos( $id, 'header_position' ) === 0 ) {
				$list = helion_get_list_header_positions( strpos( $id, 'header_position_' ) === 0 );
			} elseif ( strpos( $id, 'header_widgets' ) === 0 ) {
				$list = helion_get_list_sidebars( strpos( $id, 'header_widgets_' ) === 0, true );
			} elseif ( strpos( $id, '_scheme' ) > 0 ) {
				$list = helion_get_list_schemes( 'color_scheme' != $id );
			} else if ( strpos( $id, 'sidebar_style' ) === 0 ) {
				$list = helion_get_list_sidebar_styles( strpos( $id, 'sidebar_style_' ) === 0 );
			} elseif ( strpos( $id, 'sidebar_widgets' ) === 0 ) {
				$list = helion_get_list_sidebars( 'sidebar_widgets_single' != $id && ( strpos( $id, 'sidebar_widgets_' ) === 0 || strpos( $id, 'sidebar_widgets_single_' ) === 0 ), true );
			} elseif ( strpos( $id, 'sidebar_position_ss' ) === 0 ) {
				$list = helion_get_list_sidebars_positions_ss( strpos( $id, 'sidebar_position_ss_' ) === 0 );
			} elseif ( strpos( $id, 'sidebar_position' ) === 0 ) {
				$list = helion_get_list_sidebars_positions( strpos( $id, 'sidebar_position_' ) === 0 );
			} elseif ( strpos( $id, 'widgets_above_page' ) === 0 ) {
				$list = helion_get_list_sidebars( strpos( $id, 'widgets_above_page_' ) === 0, true );
			} elseif ( strpos( $id, 'widgets_above_content' ) === 0 ) {
				$list = helion_get_list_sidebars( strpos( $id, 'widgets_above_content_' ) === 0, true );
			} elseif ( strpos( $id, 'widgets_below_page' ) === 0 ) {
				$list = helion_get_list_sidebars( strpos( $id, 'widgets_below_page_' ) === 0, true );
			} elseif ( strpos( $id, 'widgets_below_content' ) === 0 ) {
                $list = helion_get_list_sidebars( strpos( $id, 'widgets_additional_menu_mobile_fullscreen_' ) === 0, true );
            } elseif ( strpos( $id, 'widgets_additional_menu_mobile_fullscreen' ) === 0 ) {
				$list = helion_get_list_sidebars( strpos( $id, 'widgets_below_content_' ) === 0, true );
			} elseif ( strpos( $id, 'footer_style' ) === 0 ) {
				$list = helion_get_list_footer_styles( strpos( $id, 'footer_style_' ) === 0 );
			} elseif ( strpos( $id, 'footer_widgets' ) === 0 ) {
				$list = helion_get_list_sidebars( strpos( $id, 'footer_widgets_' ) === 0, true );
			} elseif ( strpos( $id, 'blog_style' ) === 0 ) {
				$list = helion_get_list_blog_styles( strpos( $id, 'blog_style_' ) === 0 );
			} elseif ( strpos( $id, 'single_style' ) === 0 ) {
				$list = helion_get_list_single_styles( strpos( $id, 'single_style_' ) === 0 );
			} elseif ( strpos( $id, 'post_type' ) === 0 ) {
				$list = helion_get_list_posts_types();
			} elseif ( strpos( $id, 'parent_cat' ) === 0 ) {
				$list = helion_array_merge( array( 0 => esc_html__( '- Select category -', 'helion' ) ), helion_get_list_categories() );
			} elseif ( strpos( $id, 'blog_animation' ) === 0 ) {
				$list = helion_get_list_animations_in();
			} elseif ( 'color_scheme_editor' == $id ) {
				$list = helion_get_list_schemes();
			} elseif ( strpos( $id, '_font-family' ) > 0 ) {
				$list = helion_get_list_load_fonts( true );
			}
		}
		return $list;
	}
}
