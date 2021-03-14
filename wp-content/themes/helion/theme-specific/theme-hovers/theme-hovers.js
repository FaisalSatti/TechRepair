// Buttons decoration (add 'hover' class)
// Attention! Not use cont.find('selector')! Use jQuery('selector') instead!

jQuery( document ).on(
	'action.init_hidden_elements', function(e, cont) {
		"use strict";

        if (HELION_STORAGE['button_hover'] && HELION_STORAGE['button_hover'] == 'arrow') {
            jQuery(
                '.sc_button:not([class*="sc_button_plain"]):not([class*="sc_button_simple"]):not(.sc_button_more):not([class*="portfolio_more_link"]):not([class*="services_more_link"]):not(.download_btn):not(.subscribe_btn):not(.inited),\
                button:not(.full_post_close):not(.search_button):not(.mfp-close):not(.search_submit):not(.mc4wp-button):not(.inited):not([class*="sc_button_hover_"]),\
			 	.theme_button:not([class*="sc_button_hover_"]),\
				.sc_button:not([class*="sc_button_plain"]):not([class*="sc_button_simple"]):not(.sc_button_more):not([class*="portfolio_more_link"]):not([class*="services_more_link"]):not(.download_btn):not(.subscribe_btn):not([class*="sc_button_hover_"]),\
				.sc_form_field button:not([class*="sc_button_hover_"]),\
				.post_item:not(.post_layout_excerpt) .more-link:not([class*="sc_button_hover_"]),\
				.trx_addons_hover_content .trx_addons_hover_links a:not([class*="sc_button_hover_"]),\
				.helion_tabs .helion_tabs_titles li a:not([class*="sc_button_hover_"]),\
				.hover_shop_buttons .icons a:not(.inited):not([class*="sc_button_hover_"]),\
				.edd-submit.button:not([class*="sc_button_hover_style_"]),\
				.widget_edd_cart_widget .edd_checkout a:not([class*="sc_button_hover_style_"]),\
				.woocommerce .button:not([class*="shop_"]):not([class*="view"]):not([class*="sc_button_hover_"]):not(.checkout-button):not(.hide-title-form):not(.show-title-form),\
				.woocommerce-page .button:not([class*="shop_"]):not([class*="view"]):not([class*="sc_button_hover_"]):not(.checkout-button):not(.hide-title-form):not(.show-title-form),\
				#buddypress a.button:not([class*="sc_button_hover_"]),\
				.isotope_filters_button:not([class*="sc_button_hover_"]),\
				.sc_promo_modern .sc_promo_link2:not([class*="sc_button_hover_"])\
				'
            ).each(function () {
                    if (jQuery(this).find('.sc_button_text .sc_button_title').length === 0)
                        jQuery(this).html('<span class="sc_button_text"><span class="sc_button_title">' + jQuery(this).html() + '</span></span>');
                }
            ).addClass( 'sc_button_hover_just_init sc_button_hover_' + HELION_STORAGE['button_hover'] );
            // Remove just init hover class
            setTimeout(
                function() {
                    jQuery( '.sc_button_hover_just_init' ).removeClass( 'sc_button_hover_just_init' );
                }, 500
            );

            // Remove hover class
            jQuery(
                '.mejs-controls button,\
                .mfp-close,\
                .sc_button_bg_image,\
                button.pswp__button,\
                form.mc4wp-form button,\
                .woocommerce-orders-table__cell-order-actions .button,\
                .sc_layouts_row_type_narrow .sc_button,\
                .sc_layouts_cart .widget_shopping_cart .button,\
                .hover_shop_buttons .button\
                '
			).removeClass('sc_button_hover_' + HELION_STORAGE['button_hover']);
        }

	    else if (HELION_STORAGE['button_hover'] && HELION_STORAGE['button_hover'] != 'default' ) {
			jQuery(
				'button:not(.search_submit):not(.full_post_close):not([class*="sc_button_hover_"]),\
				.theme_button:not([class*="sc_button_hover_"]),\
				.sc_button:not([class*="sc_button_plain"]):not([class*="sc_button_simple"]):not([class*="sc_button_bordered"]):not([class*="sc_button_hover_"]),\
				.sc_form_field button:not([class*="sc_button_hover_"]),\
				.post_item .more-link:not([class*="sc_button_hover_"]),\
				.trx_addons_hover_content .trx_addons_hover_links a:not([class*="sc_button_hover_"]),\
				.helion_tabs .helion_tabs_titles li a:not([class*="sc_button_hover_"]),\
				.hover_shop_buttons .icons a:not([class*="sc_button_hover_style_"]),\
				.mptt-navigation-tabs li a:not([class*="sc_button_hover_style_"]),\
				.edd_download_purchase_form .button:not([class*="sc_button_hover_style_"]),\
				.edd-submit.button:not([class*="sc_button_hover_style_"]),\
				.widget_edd_cart_widget .edd_checkout a:not([class*="sc_button_hover_style_"]),\
				.woocommerce #respond input#submit:not([class*="sc_button_hover_"]),\
				.woocommerce .button:not([class*="shop_"]):not([class*="view"]):not([class*="sc_button_hover_"]),\
				.woocommerce-page .button:not([class*="shop_"]):not([class*="view"]):not([class*="sc_button_hover_"]),\
				#buddypress a.button:not([class*="sc_button_hover_"])\
				'
			).addClass( 'sc_button_hover_just_init sc_button_hover_' + HELION_STORAGE['button_hover'] );
			if (HELION_STORAGE['button_hover'] != 'arrow') {
				jQuery(
					'input[type="submit"]:not([class*="sc_button_hover_"]),\
					input[type="button"]:not([class*="sc_button_hover_"]),\
					.vc_tta-accordion .vc_tta-panel-heading .vc_tta-controls-icon:not([class*="sc_button_hover_"]),\
					.vc_tta-color-grey.vc_tta-style-classic .vc_tta-tab > a:not([class*="sc_button_hover_"]),\
					.single-product div.product .woocommerce-tabs .wc-tabs li a,\
					.woocommerce nav.woocommerce-pagination ul li a:not([class*="sc_button_hover_"]),\
					.tribe-events-button:not([class*="sc_button_hover_"]),\
					#tribe-bar-views .tribe-bar-views-list .tribe-bar-views-option a:not([class*="sc_button_hover_"]),\
					.tribe-bar-mini #tribe-bar-views .tribe-bar-views-list .tribe-bar-views-option a:not([class*="sc_button_hover_"]),\
					.tribe-events-cal-links a:not([class*="sc_button_hover_"]),\
					.tribe-events-sub-nav li a:not([class*="sc_button_hover_"]),\
					.isotope_filters_button:not([class*="sc_button_hover_"]),\
					.trx_addons_scroll_to_top:not([class*="sc_button_hover_"]),\
					.sc_promo_modern .sc_promo_link2:not([class*="sc_button_hover_"]),\
					.post_item_single .post_content .post_meta .post_share .socials_type_block .social_item .social_icon:not([class*="sc_button_hover_"]),\
					.slider_container .slider_prev:not([class*="sc_button_hover_"]),\
					.slider_container .slider_next:not([class*="sc_button_hover_"]),\
					.sc_slider_controller_titles .slider_controls_wrap > a:not([class*="sc_button_hover_"]),\
					.tagcloud > a:not([class*="sc_button_hover_"])\
					'
				).addClass( 'sc_button_hover_just_init sc_button_hover_' + HELION_STORAGE['button_hover'] );
			}
			// Add alter styles of buttons
			jQuery(
				'.sc_slider_controller_titles .slider_controls_wrap > a:not([class*="sc_button_hover_style_"])\
				'
			).addClass( 'sc_button_hover_just_init sc_button_hover_style_default' );
			jQuery(
				'.trx_addons_hover_content .trx_addons_hover_links a:not([class*="sc_button_hover_style_"]),\
				.single-product ul.products li.product .post_data .button:not([class*="sc_button_hover_style_"])\
				'
			).addClass( 'sc_button_hover_just_init sc_button_hover_style_inverse' );
			jQuery(
				'.post_item_single .post_content .post_meta .post_share .socials_type_block .social_item .social_icon:not([class*="sc_button_hover_style_"]),\
				.woocommerce #respond input#submit.alt:not([class*="sc_button_hover_style_"]),\
				.woocommerce a.button.alt:not([class*="sc_button_hover_style_"]),\
				.woocommerce button.button.alt:not([class*="sc_button_hover_style_"]),\
				.woocommerce input.button.alt:not([class*="sc_button_hover_style_"])\
				'
			).addClass( 'sc_button_hover_just_init sc_button_hover_style_hover' );
			jQuery(
				'.woocommerce .woocommerce-message .button:not([class*="sc_button_hover_style_"]),\
				.woocommerce .woocommerce-info .button:not([class*="sc_button_hover_style_"])\
				'
			).addClass( 'sc_button_hover_just_init sc_button_hover_style_alter' );
			jQuery(
				'.sidebar .trx_addons_tabs .trx_addons_tabs_titles li a:not([class*="sc_button_hover_style_"]),\
				.helion_tabs .helion_tabs_titles li a:not([class*="sc_button_hover_style_"]),\
				.widget_tag_cloud a:not([class*="sc_button_hover_style_"]),\
				.widget_product_tag_cloud a:not([class*="sc_button_hover_style_"])\
				'
			).addClass( 'sc_button_hover_just_init sc_button_hover_style_alterbd' );
			jQuery(
				'.vc_tta-accordion .vc_tta-panel-heading .vc_tta-controls-icon:not([class*="sc_button_hover_style_"]),\
				.vc_tta-color-grey.vc_tta-style-classic .vc_tta-tab > a:not([class*="sc_button_hover_style_"]),\
				.single-product div.product .woocommerce-tabs .wc-tabs li a:not([class*="sc_button_hover_style_"]),\
				.sc_button.color_style_dark:not([class*="sc_button_plain"]):not([class*="sc_button_simple"]):not([class*="sc_button_hover_style_"]),\
				.slider_prev:not([class*="sc_button_hover_style_"]),\
				.slider_next:not([class*="sc_button_hover_style_"]),\
				.trx_addons_video_player.with_cover .video_hover:not([class*="sc_button_hover_style_"]),\
				.trx_addons_tabs .trx_addons_tabs_titles li a:not([class*="sc_button_hover_style_"])\
				'
			).addClass( 'sc_button_hover_just_init sc_button_hover_style_dark' );
			jQuery(
				'.sc_price_item_link:not([class*="sc_button_hover_style_"])\
				'
			).addClass( 'sc_button_hover_just_init sc_button_hover_style_extra' );
			jQuery(
				'.sc_button.color_style_link2:not([class*="sc_button_plain"]):not([class*="sc_button_simple"]):not([class*="sc_button_hover_style_"])\
				'
			).addClass( 'sc_button_hover_just_init sc_button_hover_style_link2' );
			jQuery(
				'.sc_button.color_style_link3:not([class*="sc_button_plain"]):not([class*="sc_button_simple"]):not([class*="sc_button_hover_style_"])\
				'
			).addClass( 'sc_button_hover_just_init sc_button_hover_style_link3' );
			// Remove just init hover class
			setTimeout(
				function() {
					jQuery( '.sc_button_hover_just_init' ).removeClass( 'sc_button_hover_just_init' );
				}, 500
			);

			// Remove hover class
			jQuery(
				'.mejs-controls button,\
				.mfp-close,\
				.sc_button_bg_image,\
				.hover_shop_buttons a,\
				button.pswp__button,\
				.woocommerce-orders-table__cell-order-actions .button,\
				.sc_layouts_row_type_narrow .sc_button\
				'
			).removeClass( 'sc_button_hover_' + HELION_STORAGE['button_hover'] );

		}

		 // Elements Decoration
		//+++++++++++++++++++++

		//Underline Animation
		//+++++++++++++++++++
		jQuery(
			'.contacts_email a:not(.underline_anim),\
			 .sc_button_plain:not(.underline_anim),\
		 	 .trx_addons_title_with_link a[href*="mailto"]:not(.underline_anim),\
		 	 .rev_ca_email_anim:not(.underline_anim)\
			'
		).addClass( 'underline_anim' );

		jQuery(window).scroll(function() {
			jQuery( '.underline_anim:not(.underline_hover)' ).each( function() {
				var item = jQuery(this);
				if ( item.offset().top < jQuery( window ).scrollTop() + jQuery( window ).height() - 80 ) {
					item.addClass( 'underline_hover' );
				}
			} );
		});

		//Underscore Hover
		//++++++++++++++++
        jQuery(
            '.widget_area .post_item .post_info .post_info_categories a:not(.underscore_hover),\
              aside .post_item .post_info .post_info_categories a:not(.underscore_hover),\
             .post_meta_item.post_categories a:not(.underscore_hover),\
             .related_wrap .post_categories a:not(.underscore_hover),\
             .sc_portfolio_item_subtitle a:not(.underscore_hover),\
             .sc_services_modern .sc_services_item_subtitle a:not(.underscore_hover),\
             .wpgdprc-checkbox label a:not(.underscore_hover),\
             form.mc4wp-form .mc4wp-form-fields input[type="checkbox"] + label a:not(.underscore_hover),\
             form.mc4wp-form .mc4wp-alert a:not(.underscore_hover),\
             form.mc4wp-form .mc4wp-alert a:not(.underscore_hover),\
             .sc_form_field_checkbox label a:not(.underscore_hover),\
             .esg-grid .eg-helion-skin-element-11,\
             .esg-grid .eg-helion-skin-2-element-11\
            '
        ).addClass( 'underscore_hover' );

        // Remove hover class
        jQuery(
            '[class*="over_bottom_left"] .post_meta_item.post_categories a\
            '
        ).removeClass('underscore_hover');


        //Strike Hover
		//++++++++++++
        jQuery(
            '.sc_socials_names .social_item:not(.strike_hover)\
			'
        ).addClass( 'strike_hover' );

        //Strike Hover Thin Line
        //++++++++++++++++++++++
        jQuery(
             '.rev_ps_slider_socials:not(.strike_hover_thin),\
              .slider_socials:not(.strike_hover_thin),\
             div.esg-filter-wrapper .esg-filterbutton > span:not(.strike_hover_thin):not(.esg-filter-checked),\
            .mptt-navigation-tabs li a:not(.strike_hover_thin),\
             div.helion_tabs .helion_tabs_titles li a:not(.strike_hover_thin),\
    		 div.helion_tabs .helion_tabs_titles li a.ui-tabs-anchor:not(.strike_hover_thin),\
    		 .sc_item_filters_tabs li a:not(.strike_hover_thin)\
			'
        ).addClass( 'strike_hover_thin' );

		//Check Strike Animation
        setTimeout(function(){
            window.helion = {};
            window.helion.strikeAnimThinSwither = true;
            checkStrikeAnim();
            jQuery(window).scroll(function() {
                if(window.helion.strikeAnimThinSwither) checkStrikeAnim();
            });
            var strikeAnimItems = jQuery( '.esg-filterbutton .strike_hover_thin, .sc_item_filters_tabs a' );
            strikeAnimItems.click(function() {
                strikeAnimItems.removeClass('strike_anim_thin');
                var item = jQuery(this);
                if( jQuery('.sc_item_filters_tabs').length ) {
                    jQuery( document ).ajaxComplete(function() {
                        item.addClass('strike_anim_thin');
                    });
				} else item.addClass('strike_anim_thin');
            });
        }, 1000);
    }
);

//Check Strike Initial Animation
function checkStrikeAnim() {
    jQuery( '.esg-filterbutton.selected .strike_hover_thin:not(.strike_anim_thin), .sc_item_filters_tabs a.active' ).each( function() {
        if (jQuery(this).offset().top < jQuery( window ).scrollTop() + jQuery( window ).height() - 80) {
        	jQuery(this).addClass('strike_anim_thin');
        	window.helion.strikeAnimThinSwither = false;
        }
    });
}


jQuery( window ).load(function() {
    //Menu Strike Animation
    //+++++++++++++++++++++
	setTimeout(function () {
		jQuery (
			'.menu_hover_strike > ul > li.current-menu-item a:not(.menu_strike_anim),\
			 .menu_hover_strike > ul > li.current-menu-parent a:not(.menu_strike_anim),\
			 .menu_hover_strike > ul > li.current-menu-ancestor a:not(.menu_strike_anim)\
			'
		).addClass( 'menu_strike_anim' );

	}, (jQuery('.revslider-initialised').length > 0 && jQuery('body').hasClass('header_position_over')) ? 1000 : 0);

});


