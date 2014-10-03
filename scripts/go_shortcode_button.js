(function() {
	tinymce.PluginManager.add('go_shortcode_button', function( editor, url ) {
		editor.addButton( 'go_shortcode_button', {
			text: "[ ]",
			type: 'splitbutton',
			title: 'Shortcodes',
			onclick: function(e) {
				editor.insertContent('[go_get_displayname]');
			},
			menu: [
				{text: '[go_get_displayname]', onclick: function() {editor.insertContent('[go_get_displayname]');}}, 
				{text: '[go_get_category]', onclick: function() {editor.insertContent('[go_get_category]');}}, 
				{text: '[go_user_only_content]', onclick: function() {editor.insertContent('[go_user_only_content][/go_user_only_content]');}}, 
				{text: '[go_visitor_only_content]', onclick: function() {editor.insertContent('[go_visitor_only_content][/go_visitor_only_content]');}}, 
				{text: '[go_display_video video_url]', onclick: function() {editor.insertContent('[go_display_video video_url="" video_title="" width="" height=""]');}}, 
				{text: '[go_store cats]', onclick: function() {editor.insertContent('[go_store cats=""]');}}, 
				{text: '[go_store id]', onclick: function() {editor.insertContent('[go_store id=""]');}}, 
				{text: '[go_admin_only_content]', onclick: function() {editor.insertContent('[go_admin_only_content][/go_admin_only_content]');}}, 
				{text: '[go_firstname]', onclick: function() {editor.insertContent('[go_firstname]');}}, 
				{text: '[go_lastname]', onclick: function() {editor.insertContent('[go_lastname]');}},
				{text: '[go_loginname]', onclick: function() {editor.insertContent('[go_loginname]');}}, 
			],
		});
	});
})();