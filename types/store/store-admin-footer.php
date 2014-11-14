<?php
function store_edit_jquery(){
?>
<script type="text/javascript"> 
jQuery(document).ready(function() {
	if (jQuery('#go_store_limit_checkbox').prop('checked')) {
		jQuery('#go_store_limit_input').show('slow');
	} else {
		jQuery('#go_store_limit_input').hide('slow');
	}
	jQuery('#go_store_limit_checkbox').click(function() {
		if (jQuery('#go_store_limit_checkbox').prop('checked')) {
			jQuery('#go_store_limit_input').show('slow');
		} else {
			jQuery('#go_store_limit_input').hide('slow');
		}
	});

	if (jQuery('#go_store_filter_checkbox').prop('checked')) {
		jQuery('.go_store_filter_input').show('slow');
	} else {
		jQuery('.go_store_filter_input').hide('slow');
	}
	jQuery('#go_store_filter_checkbox').click(function() {
		if (jQuery('#go_store_filter_checkbox').prop('checked')) {
			jQuery('.go_store_filter_input').show('slow');
		} else {
			jQuery('.go_store_filter_input').hide('slow');
		}
	});

	if (jQuery('#go_store_exchange_checkbox').prop('checked')) {
		jQuery('.go_store_exchange_input').show('slow');
	} else {
		jQuery('.go_store_exchange_input').hide('slow');
	}
	jQuery('#go_store_exchange_checkbox').click(function() {
		if (jQuery('#go_store_exchange_checkbox').prop('checked')) {
			jQuery('.go_store_exchange_input').show('slow');
		} else {
			jQuery('.go_store_exchange_input').hide('slow');
		}
	});

	if (jQuery('#go_store_focus_checkbox').prop('checked')) {
		jQuery('#go_store_focus_select').show('slow');
	} else {
		jQuery('#go_store_focus_select').hide('slow');
	}
	jQuery('#go_store_focus_checkbox').click(function() {
		if (jQuery('#go_store_focus_checkbox').prop('checked')) {
			jQuery('#go_store_focus_select').show('slow');
		} else {
			jQuery('#go_store_focus_select').hide('slow');
		}
	});
});
</script>
<?php 
}
add_action('admin_footer', 'store_edit_jquery');
?>