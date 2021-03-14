<?php
/**
 * The template for homepage posts with "Portfolio" style
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0
 */

helion_storage_set( 'blog_archive', true );

get_header();

if ( have_posts() ) {

	helion_blog_archive_start();

	$helion_stickies   = is_home() ? get_option( 'sticky_posts' ) : false;
	$helion_sticky_out = helion_get_theme_option( 'sticky_style' ) == 'columns'
							&& is_array( $helion_stickies ) && count( $helion_stickies ) > 0 && get_query_var( 'paged' ) < 1;

	// Show filters
	$helion_cat          = helion_get_theme_option( 'parent_cat' );
	$helion_post_type    = helion_get_theme_option( 'post_type' );
	$helion_taxonomy     = helion_get_post_type_taxonomy( $helion_post_type );
	$helion_show_filters = helion_get_theme_option( 'show_filters' );
	$helion_tabs         = array();
	if ( ! helion_is_off( $helion_show_filters ) ) {
		$helion_args           = array(
			'type'         => $helion_post_type,
			'child_of'     => $helion_cat,
			'orderby'      => 'name',
			'order'        => 'ASC',
			'hide_empty'   => 1,
			'hierarchical' => 0,
			'taxonomy'     => $helion_taxonomy,
			'pad_counts'   => false,
		);
		$helion_portfolio_list = get_terms( $helion_args );
		if ( is_array( $helion_portfolio_list ) && count( $helion_portfolio_list ) > 0 ) {
			$helion_tabs[ $helion_cat ] = esc_html__( 'All', 'helion' );
			foreach ( $helion_portfolio_list as $helion_term ) {
				if ( isset( $helion_term->term_id ) ) {
					$helion_tabs[ $helion_term->term_id ] = $helion_term->name;
				}
			}
		}
	}
	if ( count( $helion_tabs ) > 0 ) {
		$helion_portfolio_filters_ajax   = true;
		$helion_portfolio_filters_active = $helion_cat;
		$helion_portfolio_filters_id     = 'portfolio_filters';
		?>
		<div class="portfolio_filters helion_tabs helion_tabs_ajax">
			<ul class="portfolio_titles helion_tabs_titles">
				<?php
				foreach ( $helion_tabs as $helion_id => $helion_title ) {
					?>
					<li><a href="<?php echo esc_url( helion_get_hash_link( sprintf( '#%s_%s_content', $helion_portfolio_filters_id, $helion_id ) ) ); ?>" data-tab="<?php echo esc_attr( $helion_id ); ?>"><?php echo esc_html( $helion_title ); ?></a></li>
					<?php
				}
				?>
			</ul>
			<?php
			$helion_ppp = helion_get_theme_option( 'posts_per_page' );
			if ( helion_is_inherit( $helion_ppp ) ) {
				$helion_ppp = '';
			}
			foreach ( $helion_tabs as $helion_id => $helion_title ) {
				$helion_portfolio_need_content = $helion_id == $helion_portfolio_filters_active || ! $helion_portfolio_filters_ajax;
				?>
				<div id="<?php echo esc_attr( sprintf( '%s_%s_content', $helion_portfolio_filters_id, $helion_id ) ); ?>"
					class="portfolio_content helion_tabs_content"
					data-blog-template="<?php echo esc_attr( helion_storage_get( 'blog_template' ) ); ?>"
					data-blog-style="<?php echo esc_attr( helion_get_theme_option( 'blog_style' ) ); ?>"
					data-posts-per-page="<?php echo esc_attr( $helion_ppp ); ?>"
					data-post-type="<?php echo esc_attr( $helion_post_type ); ?>"
					data-taxonomy="<?php echo esc_attr( $helion_taxonomy ); ?>"
					data-cat="<?php echo esc_attr( $helion_id ); ?>"
					data-parent-cat="<?php echo esc_attr( $helion_cat ); ?>"
					data-need-content="<?php echo ( false === $helion_portfolio_need_content ? 'true' : 'false' ); ?>"
				>
					<?php
					if ( $helion_portfolio_need_content ) {
						helion_show_portfolio_posts(
							array(
								'cat'        => $helion_id,
								'parent_cat' => $helion_cat,
								'taxonomy'   => $helion_taxonomy,
								'post_type'  => $helion_post_type,
								'page'       => 1,
								'sticky'     => $helion_sticky_out,
							)
						);
					}
					?>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	} else {
		helion_show_portfolio_posts(
			array(
				'cat'        => $helion_cat,
				'parent_cat' => $helion_cat,
				'taxonomy'   => $helion_taxonomy,
				'post_type'  => $helion_post_type,
				'page'       => 1,
				'sticky'     => $helion_sticky_out,
			)
		);
	}

	helion_blog_archive_end();

} else {

	if ( is_search() ) {
		get_template_part( apply_filters( 'helion_filter_get_template_part', 'content', 'none-search' ), 'none-search' );
	} else {
		get_template_part( apply_filters( 'helion_filter_get_template_part', 'content', 'none-archive' ), 'none-archive' );
	}
}

get_footer();
