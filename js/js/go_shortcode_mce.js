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
				{ text: '[go_get_displayname]', onclick: function() { go_tinymce_insert_content( editor, '[go_get_displayname]' ); } },
				{ text: '[go_firstname]', onclick: function() { go_tinymce_insert_content( editor, '[go_firstname]' ); } },
				{ text: '[go_lastname]', onclick: function() { go_tinymce_insert_content( editor, '[go_lastname]' ); } },
				{ text: '[go_loginname]', onclick: function() { go_tinymce_insert_content( editor, '[go_loginname]' ); } },
				{ text: '[go_store id]', onclick: function() { go_tinymce_insert_content( editor, '[go_store id=""]' ); } },
				{ text: '[go_make_map]', onclick: function() { go_tinymce_insert_content( editor, '[go_make_map]' ); } },
				{ text: '[go_make_store]', onclick: function() { go_tinymce_insert_content( editor, '[go_make_store]' ); } }
				
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