<?php
/**
 * The template to display the Author bio
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0
 */
?>

<div class="author_info author vcard" itemprop="author" itemscope="itemscope" itemtype="<?php echo esc_attr( helion_get_protocol( true ) ); ?>//schema.org/Person">

	<div class="author_avatar" itemprop="image">
		<?php
		$helion_mult = helion_get_retina_multiplier();
		echo get_avatar( get_the_author_meta( 'user_email' ), 240 * $helion_mult );
		?>
	</div><!-- .author_avatar -->

	<div class="author_description">
		<h5 class="author_title" itemprop="name">
		<?php
			// Translators: Add the author's name in the <span>
			echo wp_kses_data( sprintf( __( 'About %s', 'helion' ), '<span class="fn">' . get_the_author() . '</span>' ) );
		?>
		</h5>

		<div class="author_bio" itemprop="description">
			<?php echo wp_kses( wpautop( get_the_author_meta( 'description' ) ),'helion_kses_content' ); ?>
            <a class="author_link" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author"><span class="more_text_icon"></span><span class="more_text"><?php esc_html_e('Read More', 'helion');?></span></a>
			<?php do_action( 'helion_action_user_meta' ); ?>
		</div><!-- .author_bio -->

	</div><!-- .author_description -->

</div><!-- .author_info -->
