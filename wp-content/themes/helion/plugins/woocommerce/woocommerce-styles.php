<?php
// Add plugin-specific colors and fonts to the custom CSS
if ( ! function_exists( 'helion_woocommerce_get_css' ) ) {
	add_filter( 'helion_filter_get_css', 'helion_woocommerce_get_css', 10, 2 );
	function helion_woocommerce_get_css( $css, $args ) {

		if ( isset( $css['fonts'] ) && isset( $args['fonts'] ) ) {
			$fonts         = $args['fonts'];
			$css['fonts'] .= <<<CSS

.woocommerce .checkout table.shop_table .product-name .variation,
.woocommerce .shop_table.order_details td.product-name .variation {
	{$fonts['p_font-family']}
}

.woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price,
.woocommerce ul.products li.product .post_header, .woocommerce-page ul.products li.product .post_header,
.woocommerce .shop_table th,
.woocommerce span.onsale,
.woocommerce div.product p.price, .woocommerce div.product span.price,
.woocommerce div.product .summary .stock,
.woocommerce #reviews #comments ol.commentlist li .comment-text p.meta strong,
.woocommerce-page #reviews #comments ol.commentlist li .comment-text p.meta strong,
.woocommerce table.cart td.product-name a, .woocommerce-page table.cart td.product-name a, 
.woocommerce #content table.cart td.product-name a, .woocommerce-page #content table.cart td.product-name a,
.woocommerce .checkout table.shop_table .product-name,
.woocommerce .shop_table.order_details td.product-name,
.woocommerce .order_details li strong,
.woocommerce-MyAccount-navigation,
.woocommerce-MyAccount-content .woocommerce-Address-title a {
	{$fonts['h5_font-family']}
}
.woocommerce ul.products li.product .button, .woocommerce div.product form.cart .button,
.woocommerce .woocommerce-message .button,
.woocommerce #review_form #respond p.form-submit input[type="submit"],
.woocommerce-page #review_form #respond p.form-submit input[type="submit"],
.woocommerce table.my_account_orders .order-actions .button,
.woocommerce .button, .woocommerce-page .button,
.woocommerce a.button,
.woocommerce button.button,
.woocommerce input.button
.woocommerce #respond input#submit,
.woocommerce input[type="button"], .woocommerce-page input[type="button"],
.woocommerce input[type="submit"], .woocommerce-page input[type="submit"] {
	{$fonts['button_font-family']}
	{$fonts['button_font-size']}
	{$fonts['button_font-weight']}
	{$fonts['button_font-style']}
	{$fonts['button_line-height']}
	{$fonts['button_text-decoration']}
	{$fonts['button_text-transform']}
	{$fonts['button_letter-spacing']}
}

.woocommerce .select2-container,
.woocommerce-page .select2-container,
.woocommerce table.cart td.actions .coupon .input-text,
.woocommerce #content table.cart td.actions .coupon .input-text,
.woocommerce-page table.cart td.actions .coupon .input-text,
.woocommerce-page #content table.cart td.actions .coupon .input-text {
	{$fonts['input_font-family']}
	{$fonts['input_font-size']}
	{$fonts['input_font-weight']}
	{$fonts['input_font-style']}
	{$fonts['input_line-height']}
	{$fonts['input_text-decoration']}
	{$fonts['input_text-transform']}
	{$fonts['input_letter-spacing']}
}
.woocommerce ul.products li.product .post_header .post_tags,
.woocommerce div.product .product_meta span > a, .woocommerce div.product .product_meta span > span,
.woocommerce div.product form.cart .reset_variations,
.woocommerce #reviews #comments ol.commentlist li .comment-text p.meta time, .woocommerce-page #reviews #comments ol.commentlist li .comment-text p.meta time {
	{$fonts['info_font-family']}
}

CSS;
		}

		if ( isset( $css['vars'] ) && isset( $args['vars'] ) ) {
			$vars         = $args['vars'];
			$css['vars'] .= <<<CSS

.product_list_widget li a > img,
.wishlist_table .add_to_cart.button,
.yith-wcwl-add-button a.add_to_wishlist,
.yith-wcwl-popup-button a.add_to_wishlist,
.wishlist_table a.ask-an-estimate-button,
.wishlist-title a.show-title-form,
.hidden-title-form a.hide-title-form,
.woocommerce .yith-wcwl-wishlist-new button,
.wishlist_manage_table a.create-new-wishlist,
.wishlist_manage_table button.submit-wishlist-changes,
.yith-wcwl-wishlist-search-form button.wishlist-search-button,
.woocommerce .button, .woocommerce-page .button,
.woocommerce a.button,
.woocommerce button.button,
.woocommerce input.button
.woocommerce #respond input#submit,
.woocommerce input[type="button"], .woocommerce-page input[type="button"],
.woocommerce input[type="submit"], .woocommerce-page input[type="submit"],
.woocommerce .woocommerce-message .button,
.woocommerce ul.products li.product .button,
.woocommerce div.product form.cart .button,
.woocommerce #review_form #respond p.form-submit input[type="submit"],
.woocommerce-page #review_form #respond p.form-submit input[type="submit"],
.woocommerce table.my_account_orders .order-actions .button,
.woocommerce div.product div.images .flex-viewport,
.woocommerce div.product div.images > .woocommerce-product-gallery__wrapper,
.woocommerce div.product div.images img,
.yith-woocompare-widget a.clear-all,
.single-product div.product .woocommerce-tabs .wc-tabs li a,
.widget.WOOCS_SELECTOR .woocommerce-currency-switcher-form .chosen-container-single .chosen-single {
	-webkit-border-radius: {$vars['rad']};
	    -ms-border-radius: {$vars['rad']};
			border-radius: {$vars['rad']};
}
.woocommerce div.product form.cart div.quantity span.q_inc, .woocommerce-page div.product form.cart div.quantity span.q_inc,
.woocommerce .shop_table.cart div.quantity span.q_inc, .woocommerce-page .shop_table.cart div.quantity span.q_inc {
	-webkit-border-radius: 0 {$vars['rad']} 0 0;
	    -ms-border-radius: 0 {$vars['rad']} 0 0;
			border-radius: 0 {$vars['rad']} 0 0;
}
.woocommerce div.product form.cart div.quantity span.q_dec, .woocommerce-page div.product form.cart div.quantity span.q_dec,
.woocommerce .shop_table.cart div.quantity span.q_dec, .woocommerce-page .shop_table.cart div.quantity span.q_dec {
	-webkit-border-radius: 0 0 {$vars['rad']} 0;
	    -ms-border-radius: 0 0 {$vars['rad']} 0;
			border-radius: 0 0 {$vars['rad']} 0;
}

.trx_addons_attrib_item.trx_addons_attrib_color > span,
.woocommerce ul.products li.product .yith_buttons_wrap,
.woocommerce ul.products li.product .yith_buttons_wrap a > .tooltip {
	-webkit-border-radius: {$vars['rad3']};
	    -ms-border-radius: {$vars['rad3']};
			border-radius: {$vars['rad3']};
}

.woocommerce .product div:not(.yith_buttons_wrap) > .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse.show,
.woocommerce .product div:not(.yith_buttons_wrap) > .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse.show,
.woocommerce form.checkout_coupon, .woocommerce form.login, .woocommerce form.register,
.woocommerce .wishlist_table tr td.product-thumbnail a img,
#add_payment_method table.cart img, .woocommerce-cart table.cart img,
.woocommerce-checkout table.cart img {
	-webkit-border-radius: {$vars['rad5']};
	    -ms-border-radius: {$vars['rad5']};
			border-radius: {$vars['rad5']};
}

.woocommerce .yith-wcwl-share li a {
	-webkit-border-radius: {$vars['rad50']};
	    -ms-border-radius: {$vars['rad50']};
			border-radius: {$vars['rad50']};
}

.woocommerce .widget_price_filter .price_slider_wrapper .ui-widget-content,
.woocommerce .widget_price_filter .ui-slider .ui-slider-handle {
    -webkit-border-radius: 0;
        -ms-border-radius: 0;
            border-radius: 0;
}

CSS;
		}

		if ( isset( $css['colors'] ) && isset( $args['colors'] ) ) {
			$colors         = $args['colors'];
			$css['colors'] .= <<<CSS

/* Page header */
.woocommerce .woocommerce-breadcrumb {
	color: {$colors['text_dark']};
}
.woocommerce .woocommerce-breadcrumb a {
	color: {$colors['text_dark']};
}
.woocommerce .woocommerce-breadcrumb a:hover {
	color: {$colors['text_hover']};
}
.woocommerce .widget_price_filter .ui-slider .ui-slider-range,
.woocommerce .widget_price_filter .ui-slider .ui-slider-handle {
	background-color: {$colors['text_link']};
}

/* Out of Stock */
.woocommerce ul.products li.product .outofstock_label {
	color: {$colors['inverse_link']};
	background-color: {$colors['text_link']};
}
/* List and Single product */
.single_product_layout_stretched .page_content_wrap {
	background-color: {$colors['alter_bg_color_04']};
}
.woocommerce.single_product_layout_stretched #content div.product div.summary, .woocommerce-page.single_product_layout_stretched #content div.product div.summary,
.woocommerce.single_product_layout_stretched div.product div.summary, .woocommerce-page.single_product_layout_stretched div.product div.summary {
	background-color: {$colors['bg_color']};
}
.woocommerce.single_product_layout_stretched.sidebar_show #content div.product .woocommerce-tabs, .woocommerce-page.single_product_layout_stretched.sidebar_show #content div.product .woocommerce-tabs,
.woocommerce.single_product_layout_stretched.sidebar_show div.product .woocommerce-tabs, .woocommerce-page.single_product_layout_stretched.sidebar_show div.product .woocommerce-tabs {
	background-color: {$colors['bg_color']};
}

.woocommerce .woocommerce-ordering select {
	border-color: {$colors['input_bd_color']};
}
.woocommerce .woocommerce-ordering select:focus{
	border-color: {$colors['input_bd_hover']};
}

.woocommerce span.onsale {
	color: {$colors['inverse_link']};
	background-color: {$colors['text_link']};
}
.woocommerce .shop_mode_thumbs ul.products li.product .post_item,
.woocommerce-page .shop_mode_thumbs ul.products li.product .post_item {
	background-color: {$colors['bg_color']};
}

.woocommerce ul.products li.product .post_featured {
	border-color: {$colors['bd_color']};
	background-color: {$colors['bg_color']};
}

.woocommerce ul.products li.product .post_header a {
	color: {$colors['alter_dark']};
}
.woocommerce ul.products li.product .post_header a:hover {
	color: {$colors['alter_text']};
}
.woocommerce ul.products li.product .post_header .post_tags,
.woocommerce ul.products li.product .post_header .post_tags a {
	color: {$colors['alter_light']};
}
.woocommerce ul.products li.product .post_header .post_tags a:hover {
	color: {$colors['alter_dark']};
}

.woocommerce ul.products li.product .yith_buttons_wrap {
	border-color: {$colors['extra_bd_color']};
	background-color: {$colors['extra_bg_color']};
}
.woocommerce ul.products li.product .yith_buttons_wrap > :nth-child(n+1) {
	border-color: {$colors['extra_bd_color']};
}
.woocommerce ul.products li.product .yith_buttons_wrap a {
	color: {$colors['extra_link']};
}
.woocommerce ul.products li.product .yith_buttons_wrap a:hover {
	color: {$colors['extra_hover']};
}
.woocommerce ul.products li.product .yith_buttons_wrap a > .tooltip {
	color: {$colors['extra_text']};
	background-color: {$colors['extra_bg_color']};
	border-color: {$colors['extra_bg_color']};
}
.woocommerce.single-product ul.products li.product .yith_buttons_wrap {
	border-color: {$colors['alter_bd_color']};
	background-color: {$colors['alter_bg_color']};
}
.woocommerce.single-product ul.products li.product .yith_buttons_wrap > :nth-child(n+1) {
	border-color: {$colors['alter_bd_color']};
}
.woocommerce.single-product ul.products li.product .yith_buttons_wrap a {
	color: {$colors['alter_link']};
}
.woocommerce.single-product ul.products li.product .yith_buttons_wrap a:hover {
	color: {$colors['alter_hover']};
}
.woocommerce.single-product ul.products li.product .yith_buttons_wrap a > .tooltip {
	color: {$colors['alter_text']};
	background-color: {$colors['alter_bg_color']};
	border-color: {$colors['alter_bg_color']};
}

.woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price,
.woocommerce ul.products li.product .price ins, .woocommerce-page ul.products li.product .price ins {
	color: {$colors['alter_link']};
}
.woocommerce ul.products li.product .price del, .woocommerce-page ul.products li.product .price del {
	color: {$colors['alter_light']};
}

.woocommerce div.product p.price, .woocommerce div.product span.price,
.woocommerce span.amount, .woocommerce-page span.amount {
	color: {$colors['text_link']};
}
.woocommerce table.shop_table td span.amount {
	color: {$colors['text']};
}
.woocommerce .cart_totals  table.shop_table td span.amount {
	color: {$colors['text_dark']};
}

aside.woocommerce del,
.woocommerce del, .woocommerce del > span.amount, 
.woocommerce-page del, .woocommerce-page del > span.amount {
	color: {$colors['text_light']} !important;
}
.woocommerce .price del:before {
	background-color: {$colors['text_light']};
}
.woocommerce.widget_shopping_cart .quantity, .woocommerce .widget_shopping_cart .quantity,
.woocommerce-page.widget_shopping_cart .quantity, .woocommerce-page .widget_shopping_cart .quantity {
    color: {$colors['text']};
}
.woocommerce div.product form.cart div.quantity span, .woocommerce-page div.product form.cart div.quantity span,
.woocommerce .shop_table.cart div.quantity span, .woocommerce-page .shop_table.cart div.quantity span {
	color: {$colors['text_light']};
	background-color: transparent;
}
.woocommerce div.product form.cart div.quantity span:hover, .woocommerce-page div.product form.cart div.quantity span:hover,
.woocommerce .shop_table.cart div.quantity span:hover, .woocommerce-page .shop_table.cart div.quantity span:hover {
	color: {$colors['text_dark']};
	background-color: transparent;
}
.woocommerce div.product form.cart div.quantity input[type="number"], .woocommerce-page div.product form.cart div.quantity input[type="number"],
.woocommerce .shop_table.cart input[type="number"], .woocommerce-page .shop_table.cart div.quantity input[type="number"] {
	border-color: {$colors['input_bd_color']};
}
.woocommerce div.product form.cart div.quantity input[type="number"]:focus, .woocommerce-page div.product form.cart div.quantity input[type="number"]:focus,
.woocommerce .shop_table.cart input[type="number"]:focus, .woocommerce-page .shop_table.cart div.quantity input[type="number"]:focus {
	border-color: {$colors['input_bd_hover']};
}
.woocommerce .product_meta span,
.woocommerce .product_meta span span,
.woocommerce .product_meta a {
   color: {$colors['text_dark']};
}

.woocommerce div.product .product_meta span > a,
.woocommerce div.product .product_meta span > span {
	color: {$colors['text']};
}
.woocommerce div.product .product_meta a:hover {
	color: {$colors['text_dark']};
}

.woocommerce div.product div.images .flex-viewport,
.woocommerce div.product div.images img {
	border-color: {$colors['bd_color']};
}
.woocommerce div.product div.images a:hover img {
	border-color: {$colors['text_link']};
}

.woocommerce div.product .woocommerce-tabs ul.tabs li, .woocommerce #content div.product .woocommerce-tabs ul.tabs li, .woocommerce-page div.product .woocommerce-tabs ul.tabs li, .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li,
.woocommerce div.product .woocommerce-tabs ul.tabs li.active {
	background: {$colors['bg_color']};
}

.single_product_layout_stretched div.product .trx-stretch-width {
	background-color: {$colors['bg_color']};	
}
.single_product_layout_stretched div.product .woocommerce-tabs,
.woocommerce div.product .woocommerce-tabs .panel, .woocommerce-page div.product .woocommerce-tabs .panel,
.woocommerce #content div.product .woocommerce-tabs .panel, .woocommerce-page #content div.product .woocommerce-tabs .panel {
	border-color: {$colors['bd_color']};
}
.single-product div.product .woocommerce-tabs .wc-tabs li a {
	color: {$colors['alter_light']};
	border-color: {$colors['alter_bg_color']};
	background-color: transparent;
}
.single-product div.product .woocommerce-tabs .wc-tabs li.active a {
	color: {$colors['alter_dark']};
	border-color: {$colors['alter_bg_color']};
	background-color: {$colors['alter_bg_color']};
}
.single-product div.product .woocommerce-tabs .wc-tabs li:not(.active) a:hover {
	color: {$colors['alter_dark']};
	border-color: {$colors['alter_bg_color']};
	background-color: {$colors['alter_bg_color']};
}

.single_product_layout_stretched div.product .woocommerce-tabs .wc-tabs li a {
	color: {$colors['alter_light']};
}
.single_product_layout_stretched div.product .woocommerce-tabs .wc-tabs li.active a {
	color: {$colors['alter_dark']};
}
.single_product_layout_stretched div.product .woocommerce-tabs .wc-tabs li:not(.active) a:hover {
	color: {$colors['alter_dark']};
}

.single-product div.product .woocommerce-tabs .panel {
	color: {$colors['text']};
}
.single_product_layout_stretched div.product .woocommerce-tabs .panel {
	border-color: {$colors['bd_color']};
}
.woocommerce table.shop_attributes tr:nth-child(2n+1) > * {
	background-color: {$colors['alter_bg_color_04']};
}
.woocommerce table.shop_attributes tr:nth-child(2n) > *,
.woocommerce table.shop_attributes tr.alt > * {
	background-color: {$colors['alter_bg_color_02']};
}
.woocommerce table.shop_attributes th {
	color: {$colors['text_dark']};
}
.woocommerce div.product div.images > .woocommerce-product-gallery__wrapper {
	border-color: {$colors['bd_color']};
}
.woocommerce div.product form.cart .variations td.label {
   	color: {$colors['text_dark']};
}


/* Related Products */
.single-product .related {
	background-color: {$colors['bg_color']};
	border-color: {$colors['bd_color']};
}
.single-product .related.trx-stretch-width {
   	background-color: {$colors['alter_bg_color']};
}

.single-product ul.products li.product .post_data {
	color: {$colors['alter_text']};
}
.single-product ul.products li.product .post_data .price span.amount {
	color: {$colors['alter_link']};
}
.single-product ul.products li.product .post_data .post_header .post_tags,
.single-product ul.products li.product .post_data .post_header .post_tags a {
	color: {$colors['alter_light']};
}
.single-product ul.products li.product .post_data .post_header .post_tags a:hover {
	color: {$colors['alter_dark']};
}
.single-product ul.products li.product .post_data a {
    color: {$colors['alter_dark']};
}
.single-product ul.products li.product .post_data a:hover{
    color: {$colors['alter_text']};
}

.single-product ul.products li.product .post_data .button {
	color: {$colors['inverse_link']};
	background-color: {$colors['alter_link']};
}
.single-product ul.products li.product .post_data .button:hover {
	color: {$colors['inverse_link']} !important;
	background-color: {$colors['alter_hover']};
}

/* Rating */
.star-rating span,
.star-rating:before {
	color: {$colors['text_link']};
}
#review_form #respond p.form-submit input[type="submit"] {
	color: {$colors['inverse_link']};
	background-color: {$colors['text_link']};
}
#review_form #respond p.form-submit input[type="submit"]:hover,
#review_form #respond p.form-submit input[type="submit"]:focus {
	color: {$colors['inverse_link']};
	background-color: {$colors['text_hover']};
}

/* Shop mode selector */
.helion_shop_mode_buttons a {
	color: {$colors['text_link']};
}
.helion_shop_mode_buttons a:hover {
	color: {$colors['text_hover']};
}
.shop_mode_thumbs .helion_shop_mode_buttons a.woocommerce_thumbs,
.shop_mode_list .helion_shop_mode_buttons a.woocommerce_list {
	color: {$colors['text_dark']};
}

.woocommerce .woocommerce-result-count {
   color: {$colors['text_light']};
}


/* Messages */
.woocommerce .woocommerce-message,
.woocommerce .woocommerce-info {
	background-color: {$colors['alter_bg_color']};
	border-top-color: {$colors['alter_dark']};
}
.woocommerce .woocommerce-error {
	background-color: {$colors['alter_bg_color']};
	border-top-color: {$colors['alter_link']};
}
.woocommerce .woocommerce-message:before,
.woocommerce .woocommerce-info:before {
	color: {$colors['alter_dark']};
}
.woocommerce .woocommerce-error:before {
	color: {$colors['alter_link']};
}
.woocommerce form .form-row.woocommerce-invalid .select2-container,
.woocommerce form .form-row.woocommerce-invalid select,
.woocommerce form .form-row.woocommerce-invalid input.input-text {
    border-color:  {$colors['text_link']};
}
.woocommerce form .form-row.woocommerce-validated .select2-container,
.woocommerce form .form-row.woocommerce-validated input.input-text,
.woocommerce form .form-row.woocommerce-validated select {
    border-color:  {$colors['text_dark']};
}

.woocommerce form .form-row .required,
.woocommerce form .form-row.woocommerce-invalid label {
    color:  {$colors['text_link']};
}

/* Wishlist */
.woocommerce .product div:not(.yith_buttons_wrap) > .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse.show,
.woocommerce .product div:not(.yith_buttons_wrap) > .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse.show {
    border-color: {$colors['alter_bd_color']};
}
.woocommerce .yith-wcwl-share li a {
    color: {$colors['inverse_link']};
}
.wishlist_table.responsive.mobile li {
    border-color: {$colors['bd_color']};
}
.wishlist-title.wishlist-title-with-form h2:hover {
    background: transparent;
}
.woocommerce .wishlist_table tr td.product-thumbnail a img {
    border-color: {$colors['bd_color']};
}
.yith-wcwl-form .button.show-title-form,
.yith-wcwl-form .button.hide-title-form {
   color: {$colors['text_dark']} !important;
   background-color: transparent !important; 
}


/* Widget Cart Buttons */
.widget.woocommerce.widget_shopping_cart .button:not(.checkout) {
    color: {$colors['text_dark']};
    border-color: {$colors['text_dark']} !important;
    background-color: transparent;
}
.widget.woocommerce.widget_shopping_cart .button:not(.checkout):hover {
    color: {$colors['text_link']};
    border-color: {$colors['text_link']} !important;
    background-color: transparent;
}


/* Cart */
.woocommerce table.shop_table td {
	border-color: {$colors['alter_bd_color']} !important;
}
.woocommerce table.shop_table th {
	border-color: {$colors['alter_bd_color_02']} !important;
}
.woocommerce table.shop_table tfoot th, .woocommerce-page table.shop_table tfoot th {
	color: {$colors['text_dark']};
	border-color: transparent !important;
	background-color: transparent;
}
.woocommerce .quantity input.qty, .woocommerce #content .quantity input.qty, .woocommerce-page .quantity input.qty, .woocommerce-page #content .quantity input.qty {
	color: {$colors['input_light']};
}
.woocommerce .quantity input.qty:focus,
.woocommerce .quantity input.qty:hover,
.woocommerce #content .quantity input.qty:focus,
.woocommerce #content .quantity input.qty:hover,
.woocommerce-page .quantity input.qty:focus,
.woocommerce-page .quantity input.qty:hover,
.woocommerce-page #content .quantity input.qty:focus,
.woocommerce-page #content .quantity input.qty:hover {
	color: {$colors['input_text']};
}
.woocommerce .cart-collaterals .cart_totals table select,
.woocommerce-page .cart-collaterals .cart_totals table select {
	color: {$colors['input_text']};
	background-color: {$colors['input_bg_color']};
}
.woocommerce .cart-collaterals .cart_totals table select:focus, .woocommerce-page .cart-collaterals .cart_totals table select:focus {
	color: {$colors['input_dark']};
	background-color: {$colors['input_bg_hover']};
}
.woocommerce .cart-collaterals .shipping_calculator .shipping-calculator-button:after,
.woocommerce-page .cart-collaterals .shipping_calculator .shipping-calculator-button:after {
	color: {$colors['text_dark']};
}
.woocommerce table.shop_table .cart-subtotal .amount, .woocommerce-page table.shop_table .cart-subtotal .amount,
.woocommerce table.shop_table .shipping td, .woocommerce-page table.shop_table .shipping td {
	color: {$colors['text']};
}
.woocommerce table.shop_table .shipping td strong, .woocommerce-page table.shop_table .shipping td strong {
	color: {$colors['text_dark']};
}

.woocommerce table.cart td+td a, .woocommerce #content table.cart td+td a,
.woocommerce-page table.cart td+td a, .woocommerce-page #content table.cart td+td a,
.woocommerce table.cart td+td span:not(.sc_button_text):not(.sc_button_title),
.woocommerce #content table.cart td+td span:not(.sc_button_text):not(.sc_button_title),
.woocommerce-page table.cart td+td span:not(.sc_button_text):not(.sc_button_title),
.woocommerce-page #content table.cart td+td span:not(.sc_button_text):not(.sc_button_title) {
	color: {$colors['text']};
}
.woocommerce table.cart td+td a:hover,
.woocommerce #content table.cart td+td a:hover,
.woocommerce-page table.cart td+td a:hover,
.woocommerce-page #content table.cart td+td a:hover {
	color: {$colors['text_dark']};
}
#add_payment_method table.cart td.actions .coupon .input-text,
.woocommerce-cart table.cart td.actions .coupon .input-text,
.woocommerce-checkout table.cart td.actions .coupon .input-text {
	border-color: {$colors['input_bd_color']};
}
#add_payment_method table.cart td.actions .coupon .input-text:focus,
.woocommerce-cart table.cart td.actions .coupon .input-text:focus,
.woocommerce-checkout table.cart td.actions .coupon .input-text:focus {
	border-color: {$colors['input_bd_hover']};
}

.woocommerce table.cart td.actions, .woocommerce #content table.cart td.actions,
.woocommerce-page table.cart td.actions, .woocommerce-page #content table.cart td.actions {
	background-color:{$colors['bg_color']};
}
#add_payment_method table.cart img, .woocommerce-cart table.cart img, .woocommerce-checkout table.cart img {
    border-color: {$colors['bd_color']}
}

/* Checkout */
#add_payment_method #payment ul.payment_methods, .woocommerce-cart #payment ul.payment_methods, .woocommerce-checkout #payment ul.payment_methods {
	border-color:{$colors['bd_color']};
}
#add_payment_method #payment div.payment_box, .woocommerce-cart #payment div.payment_box, .woocommerce-checkout #payment div.payment_box {
	color:{$colors['input_dark']};
	background-color:{$colors['input_bg_hover']};
}
#add_payment_method #payment div.payment_box:before, .woocommerce-cart #payment div.payment_box:before, .woocommerce-checkout #payment div.payment_box:before {
	border-color: transparent transparent {$colors['input_bg_hover']};
}
.woocommerce .order_details li strong, .woocommerce-page .order_details li strong {
	color: {$colors['text_dark']};
}
.woocommerce .order_details.woocommerce-thankyou-order-details {
	color:{$colors['alter_text']};
	background-color:{$colors['alter_bg_color']};
}
.woocommerce .order_details.woocommerce-thankyou-order-details strong {
	color:{$colors['alter_dark']};
}
.woocommerce .checkout #order_review td.product-name,
.woocommerce-page .checkout #order_review td.product-name {
	color:{$colors['alter_dark']};
}
.woocommerce .checkout #order_review .product-name .variation,
.woocommerce-page .checkout #order_review .product-name .variation {
	color:{$colors['extra_light']};
}
.woocommerce .checkout #order_review table.shop_table td span.amount,
.woocommerce-page .checkout #order_review table.shop_table td span.amount,
.woocommerce .checkout #order_review table.shop_table .cart-subtotal .amount,
.woocommerce-page .checkout #order_review table.shop_table .cart-subtotal .amount {
	color:{$colors['alter_dark']};
}
.woocommerce .checkout #order_review  table.shop_table tfoot th,
.woocommerce-page .checkout #order_review table.shop_table tfoot th {
	color:{$colors['alter_text']};
}
/* My Account */
.woocommerce-account .woocommerce-MyAccount-navigation,
.woocommerce-MyAccount-navigation ul li,
.woocommerce-MyAccount-navigation li+li {
	border-color: {$colors['bd_color']};
}
.woocommerce-MyAccount-navigation li.is-active a {
	color: {$colors['text_link']};
}
.woocommerce-MyAccount-content .my_account_orders .button {
	color: {$colors['text_link']};
}
.woocommerce-MyAccount-content .my_account_orders .button:hover {
	color: {$colors['text_hover']};
}

/* Widgets */
.widget_product_search form:after {
	color: {$colors['input_light']};
}
.widget_product_search form:hover:after {
	color: {$colors['input_dark']};
}
.widget_shopping_cart .total {
	color: {$colors['text_dark']};
	border-color: {$colors['bd_color']};
}
.woocommerce a.remove {
	color: {$colors['text_link']} !important;
}
.woocommerce a.remove:hover {
	color: {$colors['text_hover']} !important;
}
.woocommerce ul.cart_list li a,
.woocommerce-page ul.cart_list li a,
.woocommerce ul.product_list_widget li a,
.woocommerce-page ul.product_list_widget li a {
    color: {$colors['alter_dark']};
}
.woocommerce ul.cart_list li a:hover,
.woocommerce-page ul.cart_list li a:hover,
.woocommerce ul.product_list_widget li a:hover,
.woocommerce-page ul.product_list_widget li a:hover {
    color: {$colors['alter_text']};
}


.woocommerce ul.cart_list li dl,
.woocommerce-page ul.cart_list li dl,
.woocommerce ul.product_list_widget li dl,
.woocommerce-page ul.product_list_widget li dl {
	border-color: {$colors['bd_color']};
}
.widget_layered_nav ul li.chosen a {
	color: {$colors['text_dark']};
}
.widget_price_filter .price_slider_wrapper .ui-widget-content { 
	background: {$colors['alter_bg_color']};
}
.widget_price_filter .price_label,
.widget_price_filter .price_label span {
	color: {$colors['text']};
}

/* WooCommerce Search widget */
.trx_addons_woocommerce_search_type_inline .trx_addons_woocommerce_search_form_field input[type="text"],
.trx_addons_woocommerce_search_type_inline .trx_addons_woocommerce_search_form_field .trx_addons_woocommerce_search_form_field_label {
	border-color: {$colors['text_link']};
	color: {$colors['text_link']};
}
.trx_addons_woocommerce_search_type_inline .trx_addons_woocommerce_search_form_field input[type="text"]:focus,
.trx_addons_woocommerce_search_type_inline .trx_addons_woocommerce_search_form_field .trx_addons_woocommerce_search_form_field_label:hover {
	border-color: {$colors['text_hover']};
	color: {$colors['text_hover']};
}
.trx_addons_woocommerce_search_type_inline .trx_addons_woocommerce_search_form_field_list {
	color: {$colors['alter_text']};
	border-color: {$colors['alter_bd_color']};
	background-color: {$colors['alter_bg_color']};
}
.trx_addons_woocommerce_search_type_inline .trx_addons_woocommerce_search_form_field_list li:hover {
	color: {$colors['alter_dark']};
	background-color: {$colors['alter_bg_hover']};
}



/* Third-party plugins
---------------------------------------------- */
.yith_magnifier_zoom_wrap .yith_magnifier_zoom_magnifier {
	border-color: {$colors['bd_color']};
}

.yith-woocompare-widget a.clear-all {
	color: {$colors['inverse_link']};
	background-color: {$colors['alter_link']};
}
.yith-woocompare-widget a.clear-all:hover {
	color: {$colors['inverse_hover']};
	background-color: {$colors['alter_hover']};
}

.widget.WOOCS_SELECTOR .woocommerce-currency-switcher-form .chosen-container-single .chosen-single {
	color: {$colors['input_text']};
	background: {$colors['input_bg_color']};
}
.widget.WOOCS_SELECTOR .woocommerce-currency-switcher-form .chosen-container-single .chosen-single:hover {
	color: {$colors['input_dark']};
	background: {$colors['input_bg_hover']};
}
.widget.WOOCS_SELECTOR .woocommerce-currency-switcher-form .chosen-container .chosen-drop {
	color: {$colors['input_dark']};
	background: {$colors['input_bg_hover']};
	border-color: {$colors['input_bd_hover']};
}
.widget.WOOCS_SELECTOR .woocommerce-currency-switcher-form .chosen-container .chosen-results li {
	color: {$colors['input_dark']};
}
.widget.WOOCS_SELECTOR .woocommerce-currency-switcher-form .chosen-container .chosen-results li:hover,
.widget.WOOCS_SELECTOR .woocommerce-currency-switcher-form .chosen-container .chosen-results li.highlighted,
.widget.WOOCS_SELECTOR .woocommerce-currency-switcher-form .chosen-container .chosen-results li.result-selected {
	color: {$colors['alter_link']} !important;
}

CSS;
		}

		return $css;
	}
}

