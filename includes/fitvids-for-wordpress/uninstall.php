<?php
if(!defined('WP_UNINSTALL_PLUGIN')) {
	echo "Hi there! Nice try. Come again.";
	exit;
}

delete_option('fitvids_wp_jq');
delete_option('fitvids_wp_selector');
delete_option('fitvids_wp_custom_selector');
delete_option('fitvids_wp_ignore_selector');