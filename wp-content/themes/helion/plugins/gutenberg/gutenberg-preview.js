/* global jQuery:false */
/* global HELION_STORAGE:false */

jQuery( window ).load(function() {
	"use strict";
	helion_gutenberg_first_init();
	// Create the observer to reinit visual editor after switch from code editor to visual editor
	var helion_observers = {};
	if (typeof window.MutationObserver !== 'undefined') {
		helion_create_observer('check_visual_editor', jQuery('.block-editor').eq(0), function(mutationsList) {
			var gutenberg_editor = jQuery('.edit-post-visual-editor:not(.helion_inited)').eq(0);
			if (gutenberg_editor.length > 0) helion_gutenberg_first_init();
		});
	}

	function helion_gutenberg_first_init() {
		var gutenberg_editor = jQuery( '.edit-post-visual-editor:not(.helion_inited)' ).eq( 0 );
		if ( 0 == gutenberg_editor.length ) {
			return;
		}
		jQuery( '.editor-block-list__layout' ).addClass( 'scheme_' + HELION_STORAGE['color_scheme'] );
		gutenberg_editor.addClass( 'sidebar_position_' + HELION_STORAGE['sidebar_position'] );
		if ( HELION_STORAGE['expand_content'] > 0 ) {
			gutenberg_editor.addClass( 'expand_content' );
		}
		if ( HELION_STORAGE['sidebar_position'] == 'left' ) {
			gutenberg_editor.prepend( '<div class="editor-post-sidebar-holder"></div>' );
		} else if ( HELION_STORAGE['sidebar_position'] == 'right' ) {
			gutenberg_editor.append( '<div class="editor-post-sidebar-holder"></div>' );
		}

		gutenberg_editor.addClass('helion_inited');
	}

	// Create mutations observer
	function helion_create_observer(id, obj, callback) {
		if (typeof window.MutationObserver !== 'undefined' && obj.length > 0) {
			if (typeof helion_observers[id] == 'undefined') {
				helion_observers[id] = new MutationObserver(callback);
				helion_observers[id].observe(obj.get(0), { attributes: false, childList: true, subtree: true });
			}
			return true;
		}
		return false;
	}
} );
