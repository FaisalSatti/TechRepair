<?php
/**
 * The template to display the side menu
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0
 */
?>
<div class="menu_side_wrap
<?php
$helion_menu_style = helion_get_theme_option( 'menu_style' );
echo ' menu_side_' . esc_attr( helion_get_theme_option( 'menu_side_icons' ) > 0 ? 'icons' : 'dots' );
$helion_menu_scheme = helion_get_theme_option( 'menu_scheme' );
$helion_header_scheme = helion_get_theme_option( 'header_scheme' );

if (!empty($helion_menu_scheme) && !helion_is_inherit($helion_menu_scheme)) {
    echo ' scheme_' . esc_attr($helion_menu_scheme);
} elseif (!empty($helion_header_scheme) && !helion_is_inherit($helion_header_scheme)) {
    echo ' scheme_' . esc_attr($helion_header_scheme);
}

?>
				">
    <?php if ($helion_menu_style != 'right_anchors') { ?>
	    <span class="menu_side_button icon-menu-2"></span>
    <?php } ?>

	<div class="menu_side_inner">
		<?php
        if ($helion_menu_style != 'right_anchors') {
            // Logo
                              set_query_var( 'helion_logo_args', array( 'type' => 'side' ) );
                              get_template_part( apply_filters( 'helion_filter_get_template_part', 'templates/header-logo' ) );
                              set_query_var( 'helion_logo_args', array() );
                              // Main menu button
                              ?>
            <div class="toc_menu_item">
                <a href="#" class="toc_menu_description menu_mobile_description"><span class="toc_menu_description_title"><?php esc_html_e( 'Main menu', 'helion' ); ?></span></a>
                <a class="menu_mobile_button toc_menu_icon icon-menu-2" href="#"><span class="toc_menu_burger"></span></a>
            </div>
        <?php } ?>
	</div>

</div><!-- /.menu_side_wrap -->
