<?php
/**
 * The style "Extra" of the Search form
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.6.08
 */

$args = get_query_var('trx_addons_args_sc_layouts_search');

?><div<?php if (!empty($args['id'])) echo ' id="'.esc_attr($args['id']).'"'; ?> class="sc_layouts_search<?php
		trx_addons_cpt_layouts_sc_add_classes($args);
	?>"<?php
	if (!empty($args['css'])) echo ' style="'.esc_attr($args['css']).'"'; ?>><?php

	$args['class'] = ( !empty($args['class']) ? ' ' : '' ) . 'layouts_search'; ?>

    <div class="search_extra">
        <span class="search_submit trx_addons_icon-search"></span>
        <span class="search_close trx_addons_icon-delete"></span>
        <div class="search_wrap_extra">
            <div class="search_form_wrap_extra">
                <form role="search" method="get" class="search_form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <input type="text" class="search_field" placeholder="<?php esc_attr_e( 'Type words and hit enter', 'helion' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
                    <button type="submit" class="search_submit icon-search"></button>
                </form>
            </div>
        </div>
        <div class="search_overlay"></div>
    </div>


</div><!-- /.sc_layouts_search --><?php

trx_addons_sc_layouts_showed('search', true);
