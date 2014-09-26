(function() {
	tinymce.PluginManager.add('go_shortcode_button', function( editor, url ) {
		console.log(url);
		editor.addButton( 'go_shortcode_button', {
			title: 'Shortcodes',
			image: url + "/go_logo.png",
			onclick: function() {
				editor.windowManager.open({
					title: 'Insert Shortcode',
					body: [{
						type: 'listbox', 
						label: 'Shortcode', 
						values: go_list_shortcodes(),
					}],
					onselect: function(va) {
						var stuff = String(va.control._value);
						console.log(stuff);
						jQuery('.mce-close').click();
						editor.insertContent(stuff);
					}
				});
			}
		});
	});
})();

function go_list_shortcodes(option) {
	tinyMCE.activeEditor.settings.myKeyValueList = [{text: '[go_get_displayname]', value: '[go_get_displayname]'}, {text: '[go_get_category]', value: '[go_get_category]'}, {text: '[go_user_only_content]', value: '[go_user_only_content][/go_user_only_content]'}, {text: '[go_visitor_only_content]', value: '[go_visitor_only_content][/go_visitor_only_content]'}, {text: "[go_display_video video_url='' video_title='' width='' height='']", value: "[go_display_video video_url='' video_title='' width='' height='']"}, {text: "[go_store cats='']", value: "[go_store cats='']"}, {text: "[go_store id='']", value: "[go_store id='']"}, {text: '[go_admin_only_content]', value: '[go_admin_only_content][/go_admin_only_content]'}, {text: '[go_firstname]', value: '[go_firstname]'}, {text: '[go_lastname]', value: '[go_lastname]'}, {text: '[go_loginname]', value: '[go_loginname]'}];
	return tinyMCE.activeEditor.settings.myKeyValueList;       
}
