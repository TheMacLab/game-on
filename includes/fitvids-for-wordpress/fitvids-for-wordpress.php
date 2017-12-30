<?php
/*
Plugin Name: FitVids for WordPress
Plugin URI: http://wordpress.org/extend/plugins/fitvids-for-wordpress/
Description: This plugin makes videos responsive using the FitVids jQuery plugin on WordPress.
Version: 3.0.4
Tags: videos, fitvids, responsive
Author: Kevin Dees
Author URI: http://php-built.com
*/

if ( ! function_exists('add_action')) {
	echo "Hi there! Nice try. Come again.";
	exit;
}

class FitVidsWP
{

	public $path = null;
	public $message = '';
	public $request = array();
	public $activating = false;
	public $jquery_version = '1.12.4';
	public $transient = 'fitvids-admin-notice';
	public $id = 'fitvids-wp';

	function __construct()
	{
		$this->path = plugin_dir_path(__FILE__);
		$this->setup_request();
		register_activation_hook( __FILE__, array($this, 'activation') );
		add_action('admin_notices',  array($this, 'activation_notice') );
		add_action('admin_menu', array($this, 'menu') );
		add_action('wp_enqueue_scripts', array($this, 'scripts') );
	}

	function activation() {
		$this->activating = true;
		set_transient( $this->transient , true );
	}

	function activation_notice() {
		if( get_transient( $this->transient ) && ! $this->activating ) {
			?>
			<div class="notice notice-warning is-dismissible">
				<p>FitVids wants you to <a href="<?php menu_page_url($this->id, true); ?>">check your FitVids settings</a> to validate everything is correct.</p>
			</div>
			<?php
			delete_transient( $this->transient );
		}
	}

	function menu()
	{
		$page = add_submenu_page('themes.php', 'FitVids for WordPress', 'FitVids', 'switch_themes', $this->id, array($this, 'settings_page'));
		add_action('load-' . $page, array($this, 'help_tab') );
	}

	function settings_page()
	{
		$post = $this->request['post'];

		if (isset($post['submit']) && check_admin_referer('fitvids_action', 'fitvids_ref')) {
			$this->save_option('fitvids_wp_jq');
			$this->save_option('fitvids_wp_selector');
			$this->save_option('fitvids_wp_custom_selector');
			$this->save_option('fitvids_wp_ignore_selector');

			if ( $post['fitvids_wp_jq'] == 'true' ) {
				$this->message .= 'You have enabled Google CDN jQuery for your theme.';
			}
			$this->message = '<div id="message" class="updated notice is-dismissible"><p>Yay, FitVids is updated! ' . $this->message . '</p></div>';
		}

		require( $this->path . '/admin.php' );
	}

	function help_tab() {
		$screen = get_current_screen();
		$screen->add_help_tab( array(
			'id' => $this->id,
			'title' => 'Using FitVids',
			'content' => '',
			'callback' => array( $this, 'help_content')
		) );
	}

	function help_content() {
		require( $this->path . '/help.php' );
	}

	function scripts()
	{
		if ( get_option('fitvids_wp_jq') == 'true') {
			$v = $this->jquery_version;
			wp_deregister_script('jquery');
			wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/'.$v.'/jquery.min.js', array(), $v );
			wp_enqueue_script('jquery');
		}

		wp_register_script('fitvids', plugins_url('/jquery.fitvids.js', __FILE__), array('jquery'), '1.1', true);
		wp_enqueue_script('fitvids');
		add_action('wp_print_footer_scripts', array($this, 'generate_inline_js') );
	}

	function generate_inline_js()
	{
		$selector = get_option('fitvids_wp_selector');
		$ignore = $this->prepare_field( get_option('fitvids_wp_ignore_selector') );
		$custom = $this->prepare_field( get_option('fitvids_wp_custom_selector') );
		$selector = $this->prepare_field( $selector ? $selector : 'body' );
		$options = array();
		$settings = '';

		if ($custom) {
			$options[] = 'customSelector: "' . $custom . '"';
		}

		if( $ignore ) {
			$options[] = 'ignore: "' . $ignore . '"';
		}

		if($options) {
			$settings = '{' . implode(',', $options) . '}';
		}
		?>
		<script type="text/javascript">
		jQuery(document).ready(function () {
			jQuery('<?php echo $selector; ?>').fitVids(<?php echo $settings; ?>);
		});
		</script><?php
	}

	function prepare_field( $value, $sanitize = true ) {

		if($value) {
			$value = trim( $value );

			if($sanitize) {
				$sanitized = wp_strip_all_tags( preg_replace('/"/', "'", $value), array() );
				return $sanitized;
			}
		}

		return $value;
	}

	function setup_request()
	{
		$this->request['post'] = ! empty($_POST) ? array_map('wp_unslash', $_POST ) : array();
		$this->request['get'] = ! empty($_GET) ? array_map('wp_unslash', $_GET ) : array();
		$this->request['uri'] = $_SERVER['REQUEST_URI'];
	}

	function print_cdn_field_checked() {
		if (get_option('fitvids_wp_jq') == 'true') {
			echo 'checked="checked"';
		}
	}

	function save_option( $field ) {
		if( !empty($this->request['post'][$field]) ) {
			update_option($field, $this->prepare_field($this->request['post'][$field]) );
		} else {
			delete_option($field);
		}
	}

}

new FitVidsWP();