<?php
/**
 * Theme functions: init, enqueue scripts and styles, include required files and widgets
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0
 */

if ( ! defined( 'HELION_THEME_DIR' ) ) {
	define( 'HELION_THEME_DIR', trailingslashit( get_template_directory() ) );
}
if ( ! defined( 'HELION_THEME_URL' ) ) {
	define( 'HELION_THEME_URL', trailingslashit( get_template_directory_uri() ) );
}
if ( ! defined( 'HELION_CHILD_DIR' ) ) {
	define( 'HELION_CHILD_DIR', trailingslashit( get_stylesheet_directory() ) );
}
if ( ! defined( 'HELION_CHILD_URL' ) ) {
	define( 'HELION_CHILD_URL', trailingslashit( get_stylesheet_directory_uri() ) );
}

//-------------------------------------------------------
//-- Theme init
//-------------------------------------------------------

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

if ( ! function_exists( 'helion_theme_setup1' ) ) {
	add_action( 'after_setup_theme', 'helion_theme_setup1', 1 );
	function helion_theme_setup1() {
		// Make theme available for translation
		// Translations can be filed in the /languages directory
		// Attention! Translations must be loaded before first call any translation functions!
		load_theme_textdomain( 'helion', HELION_THEME_DIR . 'languages' );
	}
}

if ( ! function_exists( 'helion_theme_setup' ) ) {
	add_action( 'after_setup_theme', 'helion_theme_setup' );
	function helion_theme_setup() {

		// Set theme content width
		$GLOBALS['content_width'] = apply_filters( 'helion_filter_content_width', helion_get_theme_option( 'page_width' ) );

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		// Custom header setup
		add_theme_support( 'custom-header',
			array(
				'header-text' => false,
				'video'       => true,
			)
		);

		// Custom logo
		add_theme_support( 'custom-logo',
			array(
				'width'       => 250,
				'height'      => 60,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
		// Custom backgrounds setup
		add_theme_support( 'custom-background', array() );

		// Partial refresh support in the Customize
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Supported posts formats
		add_theme_support( 'post-formats', array( 'gallery', 'video', 'audio', 'link', 'quote', 'image', 'status', 'aside', 'chat' ) );

		// Autogenerate title tag
		add_theme_support( 'title-tag' );

		// Add theme menus
		add_theme_support( 'nav-menus' );

		// Switch default markup for search form, comment form, and comments to output valid HTML5.
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

		// Register navigation menu
		register_nav_menus(
			array(
				'menu_main'   => esc_html__( 'Main Menu', 'helion' ),
				'menu_mobile' => esc_html__( 'Mobile Menu', 'helion' ),
			)
		);

		// Register theme-specific thumb sizes
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 370, 0, false );
		$thumb_sizes = helion_storage_get( 'theme_thumbs' );
		$mult        = helion_get_theme_option( 'retina_ready', 1 );
		if ( $mult > 1 ) {
			$GLOBALS['content_width'] = apply_filters( 'helion_filter_content_width', 1170 * $mult );
		}
		foreach ( $thumb_sizes as $k => $v ) {
			add_image_size( $k, $v['size'][0], $v['size'][1], $v['size'][2] );
			if ( $mult > 1 ) {
				add_image_size( $k . '-@retina', $v['size'][0] * $mult, $v['size'][1] * $mult, $v['size'][2] );
			}
		}
		// Add new thumb names
		add_filter( 'image_size_names_choose', 'helion_theme_thumbs_sizes' );

		// Excerpt filters
		add_filter( 'excerpt_length', 'helion_excerpt_length' );
		add_filter( 'excerpt_more', 'helion_excerpt_more' );

		// Comment form
		add_filter( 'comment_form_fields', 'helion_comment_form_fields' );
		add_filter( 'comment_form_fields', 'helion_comment_form_agree', 11 );

		// Add required meta tags in the head
		add_action( 'wp_head', 'helion_wp_head', 0 );

		// Load current page/post customization (if present)
		add_action( 'wp_footer', 'helion_wp_footer' );
		add_action( 'admin_footer', 'helion_wp_footer' );

		// Enqueue scripts and styles for the frontend
		add_action( 'wp_enqueue_scripts', 'helion_wp_styles', 1000 );              // priority 1000 - load main theme styles
		add_action( 'wp_enqueue_scripts', 'helion_wp_styles_plugins', 1100 );      // priority 1100 - load styles of the supported plugins
		add_action( 'wp_enqueue_scripts', 'helion_wp_styles_custom', 1200 );       // priority 1200 - load styles with custom fonts and colors
		add_action( 'wp_enqueue_scripts', 'helion_wp_styles_child', 1500 );        // priority 1500 - load styles of the child theme
		add_action( 'wp_enqueue_scripts', 'helion_wp_styles_responsive', 2000 );   // priority 2000 - load responsive styles after all other styles

		// Enqueue scripts for the frontend
		add_action( 'wp_enqueue_scripts', 'helion_wp_scripts', 1000 );             // priority 1000 - load main theme scripts
		add_action( 'wp_footer', 'helion_localize_scripts' );

		// Add body classes
		add_filter( 'body_class', 'helion_add_body_classes' );

		// Register sidebars
		add_action( 'widgets_init', 'helion_register_sidebars' );
	}
}


//-------------------------------------------------------
//-- Theme styles
//-------------------------------------------------------

// Load frontend styles
if ( ! function_exists( 'helion_wp_styles' ) ) {
	
	function helion_wp_styles() {

		// Links to selected fonts
		$links = helion_theme_fonts_links();
		if ( count( $links ) > 0 ) {
			foreach ( $links as $slug => $link ) {
				wp_enqueue_style( sprintf( 'helion-font-%s', $slug ), $link, array(), null );
			}
		}

		// Font icons styles must be loaded before main stylesheet
		// This style NEED the theme prefix, because style 'fontello' in some plugin contain different set of characters
		// and can't be used instead this style!
		wp_enqueue_style( 'fontello-icons', helion_get_file_url( 'css/font-icons/css/fontello.css' ), array(), null );

		// Load main stylesheet
		$main_stylesheet = HELION_THEME_URL . 'style.css';
		wp_enqueue_style( 'helion-main', $main_stylesheet, array(), null );

		// Add custom bg image
		$bg_image = helion_remove_protocol_from_url( helion_get_theme_option( 'front_page_bg_image' ), false );
		if ( is_front_page() && ! empty( $bg_image ) && helion_is_on( helion_get_theme_option( 'front_page_enabled' ) ) ) {
			// Add custom bg image for the Front page
			wp_add_inline_style( 'helion-main', 'body.frontpage, body.home-page { background-image:url(' . esc_url( $bg_image ) . ') !important }' );
		} else {
			// Add custom bg image for the body_style == 'boxed'
			$bg_image = helion_get_theme_option( 'boxed_bg_image' );
			if ( helion_get_theme_option( 'body_style' ) == 'boxed' && ! empty( $bg_image ) ) {
				wp_add_inline_style( 'helion-main', '.body_style_boxed { background-image:url(' . esc_url( $bg_image ) . ') !important }' );
			}
		}

		// Add post nav background
		helion_add_bg_in_post_nav();
	}
}

// Load styles of the supported plugins
if ( ! function_exists( 'helion_wp_styles_plugins' ) ) {
	
	function helion_wp_styles_plugins() {
		if ( helion_is_off( helion_get_theme_option( 'debug_mode' ) ) ) {
			wp_enqueue_style( 'helion-plugins', helion_get_file_url( 'css/__plugins.css' ), array(), null );
		}
	}
}

// Load styles with custom fonts and colors
if ( ! function_exists( 'helion_wp_styles_custom' ) ) {
	
	function helion_wp_styles_custom() {
		if ( ! is_customize_preview() && ! isset( $_GET['color_scheme'] ) && helion_is_off( helion_get_theme_option( 'debug_mode' ) ) ) {
			wp_enqueue_style( 'helion-custom', helion_get_file_url( 'css/__custom.css' ), array(), null );
			if ( helion_get_theme_setting( 'separate_schemes' ) ) {
				$schemes = helion_get_sorted_schemes();
				if ( is_array( $schemes ) ) {
					foreach ( $schemes as $scheme => $data ) {
						wp_enqueue_style( "helion-color-{$scheme}", helion_get_file_url( "css/__colors-{$scheme}.css" ), array(), null );
					}
				}
			}
		} else {
			wp_enqueue_style( 'helion-custom', helion_get_file_url( 'css/__custom-inline.css' ), array(), null );
			wp_add_inline_style( 'helion-custom', helion_customizer_get_css() );
		}
	}
}

// Load child-theme stylesheet (if different) after all theme styles
if ( ! function_exists( 'helion_wp_styles_child' ) ) {
	
	function helion_wp_styles_child() {
		$main_stylesheet  = HELION_THEME_URL . 'style.css';
		$child_stylesheet = HELION_CHILD_URL . 'style.css';
		if ( $child_stylesheet != $main_stylesheet ) {
			wp_enqueue_style( 'helion-child', $child_stylesheet, array( 'helion-main' ), null );
		}
	}
}

// Load responsive styles (priority 2000 - load it after main styles and plugins custom styles)
if ( ! function_exists( 'helion_wp_styles_responsive' ) ) {
	
	function helion_wp_styles_responsive() {
		if ( helion_is_off( helion_get_theme_option( 'debug_mode' ) ) ) {
			wp_enqueue_style( 'helion-responsive', helion_get_file_url( 'css/__responsive.css' ), array(), null );
		} else {
			wp_enqueue_style( 'helion-responsive', helion_get_file_url( 'css/responsive.css' ), array(), null );
		}
	}
}


//-------------------------------------------------------
//-- Theme scripts
//-------------------------------------------------------

// Load frontend scripts
if ( ! function_exists( 'helion_wp_scripts' ) ) {
	
	function helion_wp_scripts() {
		$blog_archive = helion_storage_get( 'blog_archive' ) === true || is_home();
		$blog_style   = helion_get_theme_option( 'blog_style' );
		if ( strpos( $blog_style, 'blog-custom-' ) === 0 ) {
			$blog_id   = helion_get_custom_blog_id( $blog_style );
			$blog_meta = helion_get_custom_layout_meta( $blog_id );
			if ( ! empty( $blog_meta['scripts_required'] ) && ! helion_is_off( $blog_meta['scripts_required'] ) ) {
				$blog_style = $blog_meta['scripts_required'];
			}
		}
		$need_masonry = ( $blog_archive 
							&& in_array( substr( $blog_style, 0, 7 ), array( 'gallery', 'portfol', 'masonry' ) ) )
						|| ( is_single()
							&& str_replace( 'post-format-', '', get_post_format() ) == 'gallery' );

		// Modernizr will load in head before other scripts and styles
		if ( $need_masonry ) {
			wp_enqueue_script( 'modernizr', helion_get_file_url( 'js/theme-gallery/modernizr.min.js' ), array(), null, false );
		}

		// Superfish Menu
		// Attention! To prevent duplicate this script in the plugin and in the menu, don't merge it!
		wp_enqueue_script( 'superfish', helion_get_file_url( 'js/superfish/superfish.min.js' ), array( 'jquery' ), null, true );

		// Merged scripts
		if ( helion_is_off( helion_get_theme_option( 'debug_mode' ) ) ) {
			wp_enqueue_script( 'helion-init', helion_get_file_url( 'js/__scripts.js' ), array( 'jquery' ), null, true );
		} else {
			// Skip link focus
			wp_enqueue_script( 'skip-link-focus-fix', helion_get_file_url( 'js/skip-link-focus-fix.js' ), null, true );
			// Background video
			$header_video = helion_get_header_video();
			if ( ! empty( $header_video ) && ! helion_is_inherit( $header_video ) ) {
				if ( helion_is_youtube_url( $header_video ) ) {
					wp_enqueue_script( 'tubular', helion_get_file_url( 'js/jquery.tubular.js' ), array( 'jquery' ), null, true );
				} else {
					wp_enqueue_script( 'bideo', helion_get_file_url( 'js/bideo.js' ), array(), null, true );
				}
			}
			// Theme scripts
			wp_enqueue_script( 'helion-utils', helion_get_file_url( 'js/theme-utils.js' ), array( 'jquery' ), null, true );
			wp_enqueue_script( 'helion-init', helion_get_file_url( 'js/theme-init.js' ), array( 'jquery' ), null, true );
		}
		// Load scripts for 'Masonry' layout
		if ( $need_masonry ) {
			helion_load_masonry_scripts();
		}
		// Load scripts for 'Portfolio' layout
		if ( $blog_archive
				&& in_array( substr( $blog_style, 0, 7 ), array( 'gallery', 'portfol' ) )
				&& ! is_customize_preview() ) {
			wp_enqueue_script( 'jquery-ui-tabs', false, array( 'jquery', 'jquery-ui-core' ), null, true );
		}

		// Comments
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Media elements library
		if ( helion_get_theme_setting( 'use_mediaelements' ) ) {
			wp_enqueue_style( 'mediaelement' );
			wp_enqueue_style( 'wp-mediaelement' );
			wp_enqueue_script( 'mediaelement' );
			wp_enqueue_script( 'wp-mediaelement' );
		}
	}
}


// Add variables to the scripts in the frontend
if ( ! function_exists( 'helion_localize_scripts' ) ) {
	
	function helion_localize_scripts() {

		$video = helion_get_header_video();

		wp_localize_script(
			'helion-init', 'HELION_STORAGE', apply_filters(
				'helion_filter_localize_script', array(
					// AJAX parameters
					'ajax_url'            => esc_url( admin_url( 'admin-ajax.php' ) ),
					'ajax_nonce'          => esc_attr( wp_create_nonce( admin_url( 'admin-ajax.php' ) ) ),

					// Site base url
					'site_url'            => get_home_url(),
					'theme_url'           => HELION_THEME_URL,

					// Site color scheme
					'site_scheme'         => sprintf( 'scheme_%s', helion_get_theme_option( 'color_scheme' ) ),

					// User logged in
					'user_logged_in'      => is_user_logged_in() ? true : false,

					// Window width to switch the site header to the mobile layout
					'mobile_layout_width' => 767,
					'mobile_device'       => wp_is_mobile(),

					// Sidemenu options
					'menu_side_stretch'   => helion_get_theme_option( 'menu_side_stretch' ) > 0,
					'menu_side_icons'     => helion_get_theme_option( 'menu_side_icons' ) > 0,

					// Video background
					'background_video'    => helion_is_from_uploads( $video ) ? $video : '',

					// Video and Audio tag wrapper
					'use_mediaelements'   => helion_get_theme_setting( 'use_mediaelements' ) ? true : false,

					// Allow open full post in the blog
					'open_full_post'      => helion_get_theme_option( 'open_full_post_in_blog' ) > 0,

					// Current mode
					'admin_mode'          => false,

					// Strings for translation
					'msg_ajax_error'      => esc_html__( 'Invalid server answer!', 'helion' ),
				)
			)
		);
	}
}

// Enqueue masonry, portfolio and gallery-specific scripts
if ( ! function_exists( 'helion_load_masonry_scripts' ) ) {
	function helion_load_masonry_scripts() {
		wp_enqueue_script( 'modernizr', helion_get_file_url( 'js/theme-gallery/modernizr.min.js' ), array(), null, false );
		wp_enqueue_script( 'imagesloaded' );
		wp_enqueue_script( 'masonry' );
		wp_enqueue_script( 'classie', helion_get_file_url( 'js/theme-gallery/classie.min.js' ), array(), null, true );
		wp_enqueue_script( 'helion-gallery-script', helion_get_file_url( 'js/theme-gallery/theme-gallery.js' ), array(), null, true );
	}
}


//-------------------------------------------------------
//-- Head, body and footer
//-------------------------------------------------------

//  Add meta tags in the header for frontend
if ( ! function_exists( 'helion_wp_head' ) ) {
	
	function helion_wp_head() {
		?>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="format-detection" content="telephone=no">
		<link rel="profile" href="//gmpg.org/xfn/11">
		<?php
		if ( is_singular() && pings_open() ) {
			?>
			<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
			<?php
		}	
	}
}

// Add theme specified classes to the body
if ( ! function_exists( 'helion_add_body_classes' ) ) {
	
	function helion_add_body_classes( $classes ) {
		$classes[] = 'body_tag';    // Need for the .scheme_self
		$classes[] = 'scheme_' . esc_attr( helion_get_theme_option( 'color_scheme' ) );

		$blog_mode = helion_storage_get( 'blog_mode' );
		$classes[] = 'blog_mode_' . esc_attr( $blog_mode );
		$classes[] = 'body_style_' . esc_attr( helion_get_theme_option( 'body_style' ) );

		if ( in_array( $blog_mode, array( 'post', 'page' ) ) ) {
			$classes[] = 'is_single';
		} else {
			$classes[] = ' is_stream';
			$classes[] = 'blog_style_' . esc_attr( helion_get_theme_option( 'blog_style' ) );
			if ( helion_storage_get( 'blog_template' ) > 0 ) {
				$classes[] = 'blog_template';
			}
		}

		if ( is_singular( 'post' ) || is_singular( 'attachment' ) ) {
			$classes[] = 'single_style_' . esc_attr( helion_get_theme_option( 'single_style' ) );
		}

		if ( helion_sidebar_present() ) {
			$classes[] = 'sidebar_show sidebar_' . esc_attr( helion_get_theme_option( 'sidebar_position' ) );
			$classes[] = 'sidebar_small_screen_' . esc_attr( helion_get_theme_option( 'sidebar_position_ss' ) );
		} else {
			$classes[] = 'sidebar_hide';
			if ( helion_is_on( helion_get_theme_option( 'expand_content' ) ) ) {
				$classes[] = 'expand_content';
			}
		}

		if ( helion_is_on( helion_get_theme_option( 'remove_margins' ) ) ) {
			$classes[] = 'remove_margins';
		}

		$bg_image = helion_get_theme_option( 'front_page_bg_image' );
		if ( is_front_page() && helion_is_on( helion_get_theme_option( 'front_page_enabled' ) ) && ! empty( $bg_image ) ) {
			$classes[] = 'with_bg_image';
		}

		$classes[] = 'trx_addons_' . esc_attr( helion_exists_trx_addons() ? 'present' : 'absent' );

		$classes[] = 'header_type_' . esc_attr( helion_get_theme_option( 'header_type' ) );
		$classes[] = 'header_style_' . esc_attr( 'default' == helion_get_theme_option( 'header_type' ) ? 'header-default' : helion_get_theme_option( 'header_style' ) );
		$classes[] = 'header_position_' . esc_attr( helion_get_theme_option( 'header_position' ) );

		$menu_style = helion_get_theme_option( 'menu_style' );
		$classes[]  = 'menu_style_' . esc_attr( $menu_style ) . ( in_array( $menu_style, array( 'left', 'right', 'right_anchors' ) ) ? ' menu_style_side' : '' );
		$classes[]  = 'no_layout';

		return $classes;
	}
}

// Load current page/post customization (if present)
if ( ! function_exists( 'helion_wp_footer' ) ) {
	
	//and add_action('admin_footer', 'helion_wp_footer');
	function helion_wp_footer() {
		// Add header zoom
		$header_zoom = max( 0.2, min( 2, (float) helion_get_theme_option( 'header_zoom' ) ) );
		if ( 1 != $header_zoom ) {
			helion_add_inline_css( ".sc_layouts_title_title{font-size:{$header_zoom}em}" );
		}
		// Add logo zoom
		$logo_zoom = max( 0.2, min( 2, (float) helion_get_theme_option( 'logo_zoom' ) ) );
		if ( 1 != $logo_zoom ) {
			helion_add_inline_css( ".custom-logo-link,.sc_layouts_logo{font-size:{$logo_zoom}em}" );
		}
		// Put inline styles to the output
		$css = helion_get_inline_css();
		if ( ! empty( $css ) ) {
			wp_enqueue_style( 'helion-inline-styles', helion_get_file_url( 'css/__inline.css' ), array(), null );
			wp_add_inline_style( 'helion-inline-styles', $css );
		}
	}
}


//-------------------------------------------------------
//-- Sidebars and widgets
//-------------------------------------------------------

// Register widgetized areas
if ( ! function_exists( 'helion_register_sidebars' ) ) {
	
	function helion_register_sidebars() {
		$sidebars = helion_get_sidebars();
		if ( is_array( $sidebars ) && count( $sidebars ) > 0 ) {
			$cnt = 0;
			foreach ( $sidebars as $id => $sb ) {
				$cnt++;
				register_sidebar(
					apply_filters( 'helion_filter_register_sidebar',
						array(
							'name'          => $sb['name'],
							'description'   => $sb['description'],
							// Translators: Add the sidebar number to the id
							'id'            => ! empty( $id ) ? $id : sprintf( 'theme_sidebar_%d', $cnt),
							'before_widget' => '<aside id="%1$s" class="widget %2$s">',
							'after_widget'  => '</aside>',
							'before_title'  => '<h5 class="widget_title">',
							'after_title'   => '</h5>',
						)
					)
				);
			}
		}
	}
}

// Return theme specific widgetized areas
if ( ! function_exists( 'helion_get_sidebars' ) ) {
	function helion_get_sidebars() {
		$list = apply_filters(
			'helion_filter_list_sidebars', array(
				'sidebar_widgets'       => array(
					'name'        => esc_html__( 'Sidebar Widgets', 'helion' ),
					'description' => esc_html__( 'Widgets to be shown on the main sidebar', 'helion' ),
				),
				'header_widgets'        => array(
					'name'        => esc_html__( 'Header Widgets', 'helion' ),
					'description' => esc_html__( 'Widgets to be shown at the top of the page (in the page header area)', 'helion' ),
				),
				'above_page_widgets'    => array(
					'name'        => esc_html__( 'Top Page Widgets', 'helion' ),
					'description' => esc_html__( 'Widgets to be shown below the header, but above the content and sidebar', 'helion' ),
				),
				'above_content_widgets' => array(
					'name'        => esc_html__( 'Above Content Widgets', 'helion' ),
					'description' => esc_html__( 'Widgets to be shown above the content, near the sidebar', 'helion' ),
				),
				'below_content_widgets' => array(
					'name'        => esc_html__( 'Below Content Widgets', 'helion' ),
					'description' => esc_html__( 'Widgets to be shown below the content, near the sidebar', 'helion' ),
				),
				'below_page_widgets'    => array(
					'name'        => esc_html__( 'Bottom Page Widgets', 'helion' ),
					'description' => esc_html__( 'Widgets to be shown below the content and sidebar, but above the footer', 'helion' ),
				),
				'footer_widgets'        => array(
					'name'        => esc_html__( 'Footer Widgets', 'helion' ),
					'description' => esc_html__( 'Widgets to be shown at the bottom of the page (in the page footer area)', 'helion' ),
				),
			)
		);
		return $list;
	}
}


//-------------------------------------------------------
//-- Theme fonts
//-------------------------------------------------------

// Return links for all theme fonts
if ( ! function_exists( 'helion_theme_fonts_links' ) ) {
	function helion_theme_fonts_links() {
		$links = array();

		/*
		Translators: If there are characters in your language that are not supported
		by chosen font(s), translate this to 'off'. Do not translate into your own language.
		*/
		$google_fonts_enabled = ( 'off' !== esc_html_x( 'on', 'Google fonts: on or off', 'helion' ) );
		$custom_fonts_enabled = ( 'off' !== esc_html_x( 'on', 'Custom fonts (included in the theme): on or off', 'helion' ) );

		if ( ( $google_fonts_enabled || $custom_fonts_enabled ) && ! helion_storage_empty( 'load_fonts' ) ) {
			$load_fonts = helion_storage_get( 'load_fonts' );
			if ( count( $load_fonts ) > 0 ) {
				$google_fonts = '';
				foreach ( $load_fonts as $font ) {
					$url = '';
					if ( $custom_fonts_enabled && empty( $font['styles'] ) ) {
						$slug = helion_get_load_fonts_slug( $font['name'] );
						$url  = helion_get_file_url( "css/font-face/{$slug}/stylesheet.css" );
						if ( ! empty( $url ) ) {
							$links[ $slug ] = $url;
						}
					}
					if ( $google_fonts_enabled && empty( $url ) ) {
						// Attention! Using '%7C' instead '|' damage loading second+ fonts
						$google_fonts .= ( $google_fonts ? '|' : '' )
										. str_replace( ' ', '+', $font['name'] )
										. ':'
										. ( empty( $font['styles'] ) ? '400,400italic,700,700italic' : $font['styles'] );
					}
				}
				if ( $google_fonts_enabled && ! empty( $google_fonts ) ) {
					$google_fonts_subset = helion_get_theme_option( 'load_fonts_subset' );
					$links['google_fonts'] = esc_url( "https://fonts.googleapis.com/css?family={$google_fonts}&subset={$google_fonts_subset}" );
				}
			}
		}
		return $links;
	}
}

// Return links for WP Editor
if ( ! function_exists( 'helion_theme_fonts_for_editor' ) ) {
	function helion_theme_fonts_for_editor() {
		$links = array_values( helion_theme_fonts_links() );
		if ( is_array( $links ) && count( $links ) > 0 ) {
			for ( $i = 0; $i < count( $links ); $i++ ) {
				$links[ $i ] = str_replace( ',', '%2C', $links[ $i ] );
			}
		}
		return $links;
	}
}


//-------------------------------------------------------
//-- The Excerpt
//-------------------------------------------------------
if ( ! function_exists( 'helion_excerpt_length' ) ) {
	
	function helion_excerpt_length( $length ) {
		$blog_style = explode( '_', helion_get_theme_option( 'blog_style' ) );
		return max( 0, round( helion_get_theme_option( 'excerpt_length' ) / ( in_array( $blog_style[0], array( 'classic', 'masonry', 'portfolio' ) ) ? 2 : 1 ) ) );
	}
}

if ( ! function_exists( 'helion_excerpt_more' ) ) {
	
	function helion_excerpt_more( $more ) {
		return '&hellip;';
	}
}


//-------------------------------------------------------
//-- Comments
//-------------------------------------------------------

// Comment form fields order
if ( ! function_exists( 'helion_comment_form_fields' ) ) {
	
	function helion_comment_form_fields( $comment_fields ) {
		if ( helion_get_theme_setting( 'comment_after_name' ) ) {
			$keys = array_keys( $comment_fields );
			if ( 'comment' == $keys[0] ) {
				$comment_fields['comment'] = array_shift( $comment_fields );
			}
		}
		return $comment_fields;
	}
}

// Add checkbox with "I agree ..."
if ( ! function_exists( 'helion_comment_form_agree' ) ) {
	
	function helion_comment_form_agree( $comment_fields ) {
		$privacy_text = helion_get_privacy_text();
		if ( ! empty( $privacy_text )
			&& ( ! function_exists( 'helion_exists_gdpr_framework' ) || ! helion_exists_gdpr_framework() )
			&& ( ! function_exists( 'helion_exists_wp_gdpr_compliance' ) || ! helion_exists_wp_gdpr_compliance() )
		) {
			$comment_fields['i_agree_privacy_policy'] = helion_single_comments_field(
				array(
					'form_style'        => 'default',
					'field_type'        => 'checkbox',
					'field_req'         => '',
					'field_icon'        => '',
					'field_value'       => '1',
					'field_name'        => 'i_agree_privacy_policy',
					'field_title'       => $privacy_text,
				)
			);
		}
		return $comment_fields;
	}
}



//-------------------------------------------------------
//-- Thumb sizes
//-------------------------------------------------------
if ( ! function_exists( 'helion_theme_thumbs_sizes' ) ) {
	
	function helion_theme_thumbs_sizes( $sizes ) {
		$thumb_sizes = helion_storage_get( 'theme_thumbs' );
		$mult        = helion_get_theme_option( 'retina_ready', 1 );
		foreach ( $thumb_sizes as $k => $v ) {
			$sizes[ $k ] = $v['title'];
			if ( $mult > 1 ) {
				$sizes[ $k . '-@retina' ] = $v['title'] . ' ' . esc_html__( '@2x', 'helion' );
			}
		}
		return $sizes;
	}
}



//-------------------------------------------------------
//-- Include theme (or child) PHP-files
//-------------------------------------------------------

require_once HELION_THEME_DIR . 'includes/utils.php';
require_once HELION_THEME_DIR . 'includes/storage.php';

require_once HELION_THEME_DIR . 'includes/lists.php';
require_once HELION_THEME_DIR . 'includes/wp.php';

if ( is_admin() ) {
	require_once HELION_THEME_DIR . 'includes/tgmpa/class-tgm-plugin-activation.php';
	require_once HELION_THEME_DIR . 'includes/admin.php';
}

require_once HELION_THEME_DIR . 'theme-options/theme-customizer.php';

require_once HELION_THEME_DIR . 'front-page/front-page-options.php';

require_once HELION_THEME_DIR . 'theme-specific/theme-tags.php';
require_once HELION_THEME_DIR . 'theme-specific/theme-hovers/theme-hovers.php';
require_once HELION_THEME_DIR . 'theme-specific/theme-about/theme-about.php';

// Free themes support
if ( HELION_THEME_FREE ) {
	require_once HELION_THEME_DIR . 'theme-specific/theme-about/theme-upgrade.php';
}

// Plugins support
$helion_required_plugins = helion_storage_get( 'required_plugins' );
if ( is_array( $helion_required_plugins ) ) {
	foreach ( $helion_required_plugins as $plugin_slug => $plugin_data ) {
		$plugin_slug = helion_esc( $plugin_slug );
		$plugin_path = HELION_THEME_DIR . sprintf( 'plugins/%1$s/%1$s.php', $plugin_slug );
		if ( file_exists( $plugin_path ) ) {
			require_once $plugin_path;
		}
	}
}

// Theme skins support
if ( defined( 'HELION_ALLOW_SKINS' ) && HELION_ALLOW_SKINS && file_exists( HELION_THEME_DIR . 'skins/skins.php' ) ) {
	require_once HELION_THEME_DIR . 'skins/skins.php';
}
