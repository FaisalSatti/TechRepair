<?php
/**
 * The template to display single post
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0
 */

// Full post loading
$full_post_loading        = helion_get_value_gp( 'action' ) == 'full_post_loading';

// Prev post loading
$prev_post_loading        = helion_get_value_gp( 'action' ) == 'prev_post_loading';

// Position of the related posts
$helion_related_position = helion_get_theme_option( 'related_position' );

// Type of the prev/next posts navigation
$helion_posts_navigation = helion_get_theme_option( 'posts_navigation' );
$helion_prev_post        = false;

// Rewrite style of the single post if current post loading via AJAX and featured image and title is not in the content
if ( ( $full_post_loading || $prev_post_loading ) && ! in_array( helion_get_theme_option( 'single_style' ), array( 'in-above', 'in-below', 'in-over', 'in-sticky' ) ) ) {
	helion_storage_set_array( 'options_meta', 'single_style', 'in-below' );
}

get_header();

while ( have_posts() ) {
	the_post();

	// Type of the prev/next posts navigation
	if ( 'scroll' == $helion_posts_navigation ) {
		$helion_prev_post = get_previous_post( true );         // Get post from same category
		if ( ! $helion_prev_post ) {
			$helion_prev_post = get_previous_post( false );    // Get post from any category
			if ( ! $helion_prev_post ) {
				$helion_posts_navigation = 'links';
			}
		}
	}

	// Override some theme options to display featured image, title and post meta in the dynamic loaded posts
	if ( $full_post_loading || ( $prev_post_loading && $helion_prev_post ) ) {
		helion_sc_layouts_showed( 'featured', false );
		helion_sc_layouts_showed( 'title', false );
		helion_sc_layouts_showed( 'postmeta', false );
	}

	// If related posts should be inside the content
	if ( strpos( $helion_related_position, 'inside' ) === 0 ) {
		ob_start();
	}

	// Display post's content
	get_template_part( apply_filters( 'helion_filter_get_template_part', 'content', 'single-' . helion_get_theme_option( 'single_style' ) ), 'single-' . helion_get_theme_option( 'single_style' ) );

	// If related posts should be inside the content
	if ( strpos( $helion_related_position, 'inside' ) === 0 ) {
		$helion_content = ob_get_contents();
		ob_end_clean();

		ob_start();
		do_action( 'helion_action_related_posts' );
		$helion_related_content = ob_get_contents();
		ob_end_clean();

		$helion_related_position_inside = max( 0, min( 9, helion_get_theme_option( 'related_position_inside' ) ) );
		if ( 0 == $helion_related_position_inside ) {
			$helion_related_position_inside = mt_rand( 1, 9 );
		}

		$helion_p_number = 0;
		$helion_related_inserted = false;
		for ( $i = 0; $i < strlen( $helion_content ) - 3; $i++ ) {
			if ( '<' == $helion_content[ $i ] && 'p' == $helion_content[ $i + 1 ] && in_array( $helion_content[ $i + 2 ], array( '>', ' ' ) ) ) {
				$helion_p_number++;
				if ( $helion_related_position_inside == $helion_p_number ) {
					$helion_related_inserted = true;
					$helion_content = ( $i > 0 ? substr( $helion_content, 0, $i ) : '' )
										. $helion_related_content
										. substr( $helion_content, $i );
				}
			}
		}
		if ( ! $helion_related_inserted ) {
			$helion_content .= $helion_related_content;
		}

		helion_show_layout( $helion_content );
	}

	// Author bio
	if ( helion_get_theme_option( 'show_author_info' ) == 1
		&& ! is_attachment()
		&& get_the_author_meta( 'description' )
		&& ( 'scroll' != $helion_posts_navigation || helion_get_theme_option( 'posts_navigation_scroll_hide_author' ) == 0 )
		&& ( ! $full_post_loading || helion_get_theme_option( 'open_full_post_hide_author' ) == 0 )
	) {
		do_action( 'helion_action_before_post_author' );
		get_template_part( apply_filters( 'helion_filter_get_template_part', 'templates/author-bio' ) );
		do_action( 'helion_action_after_post_author' );
	}

	// Previous/next post navigation.
	if ( 'links' == $helion_posts_navigation && ! $full_post_loading ) {
		do_action( 'helion_action_before_post_navigation' );
		?>
		<div class="nav-links-single<?php
			if ( ! helion_is_off( helion_get_theme_option( 'posts_navigation_fixed' ) ) ) {
				echo ' nav-links-fixed fixed';
			}
		?>">
			<?php
			the_post_navigation(
				array(
					'next_text' => '<span class="nav-arrow"></span>'
						. '<span class="screen-reader-text">' . esc_html__( 'Next', 'helion' ) . '</span> '
						. '<h6 class="post-title">%title</h6>'
						. '<span class="post_date">%date</span>',
					'prev_text' => '<span class="nav-arrow"></span>'
						. '<span class="screen-reader-text">' . esc_html__( 'Prev', 'helion' ) . '</span> '
						. '<h6 class="post-title">%title</h6>'
						. '<span class="post_date">%date</span>',
				)
			);
			?>
		</div>
		<?php
		do_action( 'helion_action_after_post_navigation' );
	}

	// Related posts
	if ( 'below_content' == $helion_related_position
		&& ( 'scroll' != $helion_posts_navigation || helion_get_theme_option( 'posts_navigation_scroll_hide_related' ) == 0 )
		&& ( ! $full_post_loading || helion_get_theme_option( 'open_full_post_hide_related' ) == 0 )
	) {
		do_action( 'helion_action_related_posts' );
	}

	// If comments are open or we have at least one comment, load up the comment template.
	$helion_comments_number = get_comments_number();
	if ( comments_open() || $helion_comments_number > 0 ) {
		if ( helion_get_value_gp( 'show_comments' ) == 1 || ( ! $full_post_loading && ( 'scroll' != $helion_posts_navigation || helion_get_theme_option( 'posts_navigation_scroll_hide_comments' ) == 0 || helion_check_url( '#comment' ) ) ) ) {
			do_action( 'helion_action_before_comments' );
			comments_template();
			do_action( 'helion_action_after_comments' );
		} else {
			?>
			<div class="show_comments_single">
				<a href="<?php echo esc_url( add_query_arg( array( 'show_comments' => 1 ), get_comments_link() ) ); ?>" class="theme_button show_comments_button">
					<?php
					if ( $helion_comments_number > 0 ) {
						echo esc_html( sprintf( _n( 'Show comment', 'Show comments ( %d )', $helion_comments_number, 'helion' ), $helion_comments_number ) );
					} else {
						esc_html_e( 'Leave a comment', 'helion' );
					}
					?>
				</a>
			</div>
			<?php
		}
	}

	if ( 'scroll' == $helion_posts_navigation && ! $full_post_loading ) {
		?>
		<div class="nav-links-single-scroll"
			data-post-id="<?php echo esc_attr( get_the_ID( $helion_prev_post ) ); ?>"
			data-post-link="<?php echo esc_attr( get_permalink( $helion_prev_post ) ); ?>"
			data-post-title="<?php the_title_attribute( array( 'post' => $helion_prev_post ) ); ?>">
		</div>
		<?php
	}
}

get_footer();
