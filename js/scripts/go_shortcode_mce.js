//This file is loaded by a filter in the shortcode.php file.
( function() {
	tinymce.PluginManager.add( 'go_shortcode_button', function( editor, url ) {
		editor.addButton( 'go_shortcode_button', {
			text: "[ ]",
			type: 'splitbutton',
			title: 'Shortcodes',
			onclick: function( e ) {
				go_tinymce_insert_content ( editor, '[go_get_displayname]' );
			},
			menu: [
				{ text: 'Display Name', onclick: function() { go_tinymce_insert_content( editor, '[go_get_displayname]' ); } },
				{ text: 'First Name', onclick: function() { go_tinymce_insert_content( editor, '[go_firstname]' ); } },
				{ text: 'Last Name', onclick: function() { go_tinymce_insert_content( editor, '[go_lastname]' ); } },
				{ text: 'Login Name', onclick: function() { go_tinymce_insert_content( editor, '[go_loginname]' ); } },
                { text: '–––––––––––––',  },
				{ text: 'Insert the Map', onclick: function() { go_tinymce_insert_content( editor, '[go_make_map]' ); } },
                //{ text: '[go_single_map]', onclick: function() { go_tinymce_insert_content( editor, '[go_single_map_link map_id=""]Go to Map[/go_single_map_link]' ); } },
				{ text: 'Insert the Store', onclick: function() { go_tinymce_insert_content( editor, '[go_make_store]' ); } },
                { text: 'Store Item (get the Item ID from the edit page)', onclick: function() { go_tinymce_insert_content( editor, '[go_store id=""]' ); } },
                { text: '–––––––––––––',  },
				{ text: 'Video: Text link to Lightbox of Video URL', onclick: function() { go_tinymce_insert_content( editor, '[go_video_link video_url="" video_title=""]' ); } },
                { text: 'Video: Thumbnail to  Lightbox of Video URL', onclick: function() { go_tinymce_insert_content( editor, '[go_video video_url="" ]' ); } },
				{ text: 'iFrame Lightbox of any URL', onclick: function() { go_tinymce_insert_content( editor, '[go_lightbox_url link_url="" link_text=""]' ); } },

			],
		});
	});
})();

function go_tinymce_insert_content( editor, content ) {
	if ( content.search( '("|\' )' ) !== -1 ) {
		var content_index = content.search( '("|\' )' ) + 1;
	} else if ( content.search( '\\]\\[' ) !== -1 ) {
		var content_index = content.search( '\\]\\[' ) + 1;
	}
	if ( typeof( content_index ) !== 'undefined' ) {
		editor.insertContent( content );
		var bm_1 = editor.selection.getBookmark( 1 );
		var bm_2 = bm_1;
		var bm_2_node = bm_2.rng.startContainer;
		bm_2.rng.setStart( bm_2_node, content_index );
		bm_2.rng.setEnd( bm_2_node, content_index );
		editor.selection.moveToBookmark( bm_2 );
	} else {
		editor.insertContent( content );
	}
}