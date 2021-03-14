<?php
/**
 * The template to show mobile menu
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0
 */

$helion_show_widgets = helion_get_theme_option( 'widgets_menu_mobile_fullscreen' );
?>
<div class="menu_mobile_overlay"></div>
<div class="menu_mobile menu_mobile_<?php echo esc_attr( helion_get_theme_option( 'menu_mobile_fullscreen' ) > 0 ? 'fullscreen' : 'narrow' ); ?> scheme_dark">
     <div class="menu_mobile_inner<?php echo esc_attr( $helion_show_widgets == 1  ? ' with_widgets' : '' ); ?>">

		<a class="menu_mobile_close theme_button_close"><span class="theme_button_close_icon"></span></a>
         <div class="menu_mobile_wrapper">
            <div class="menu_mobile_elements_area">
            <?php

            // Logo
            set_query_var( 'helion_logo_args', array( 'type' => 'mobile' ) );
            get_template_part( apply_filters( 'helion_filter_get_template_part', 'templates/header-logo' ) );
            set_query_var( 'helion_logo_args', array() );

            // Mobile menu
            $helion_menu_mobile = helion_get_nav_menu( 'menu_mobile' );
            if ( empty( $helion_menu_mobile ) ) {
                $helion_menu_mobile = apply_filters( 'helion_filter_get_mobile_menu', '' );
                if ( empty( $helion_menu_mobile ) ) {
                    $helion_menu_mobile = helion_get_nav_menu( 'menu_main' );
                    if ( empty( $helion_menu_mobile ) ) {
                        $helion_menu_mobile = helion_get_nav_menu();
                    }
                }
            }
            if ( ! empty( $helion_menu_mobile ) ) {
                $helion_menu_mobile = str_replace(
                    array( 'menu_main',   'id="menu-',        'sc_layouts_menu_nav', 'sc_layouts_menu ', 'sc_layouts_hide_on_mobile', 'hide_on_mobile' ),
                    array( 'menu_mobile', 'id="menu_mobile-', '',                    ' ',                '',                          '' ),
                    $helion_menu_mobile
                );
                if ( strpos( $helion_menu_mobile, '<nav ' ) === false ) {
                    $helion_menu_mobile = sprintf( '<nav class="menu_mobile_nav_area" itemscope="itemscope" itemtype="' . esc_attr( helion_get_protocol( true ) ) . '//schema.org/SiteNavigationElement">%s</nav>', $helion_menu_mobile );
                }
                helion_show_layout( apply_filters( 'helion_filter_menu_mobile_layout', $helion_menu_mobile ) );
            }

            // Social icons
            helion_show_layout( helion_get_socials_links(), '<div class="socials_mobile">', '</div>' );

            ?></div><?php

            if ( $helion_show_widgets == 1 )  {
                 ?><div class="menu_mobile_widgets_area"><?php
                     // Create Widgets Area
                     helion_create_widgets_area( 'widgets_additional_menu_mobile_fullscreen' );
                 ?></div><?php
            }
            ?>
         </div>
     </div>
</div>
