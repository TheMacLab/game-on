<?php
if ( ! function_exists('add_action')) {
    echo "Hi there! Nice try. Come again.";
    exit;
}
?>

<div id="fitvids-wp-page" class="wrap">

    <h1>FitVids for WordPress</h1>

    <?php echo $this->message; ?>

    <form method="post" action="<?php echo esc_attr($this->request['uri']); ?>">
        <?php wp_nonce_field('fitvids_action', 'fitvids_ref'); ?>

        <h2>Advanced Settings</h2>
        <p>No configuration is required. Need help? Click the help tab at the top right of this page.</p>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row"><label for="fitvids_wp_selector">FitVids Main Selector</label></th>
                <td>
                    <input id="fitvids_wp_selector"
                           placeholder="body"
                           value="<?php echo esc_attr( get_option('fitvids_wp_selector', 'body') ); ?>"
                           name="fitvids_wp_selector"
                           type="text"
                           class="regular-text"
                    >
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="fitvids_wp_custom_selector">FitVids Custom Selector</label></th>
                <td>
                    <input placeholder="iframe[src^='http://mycoolvideosite.com']"
                           value="<?php echo esc_attr(get_option('fitvids_wp_custom_selector')); ?>"
                           name="fitvids_wp_custom_selector"
                           type="text"
                           class="regular-text"
                           id="fitvids_wp_custom_selector"
                    >
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="fitvids_wp_ignore_selector">FitVids Ignore Selector</label></th>
                <td>
                    <input placeholder=".ignore-item, .ignore-section"
                           value="<?php echo esc_attr(get_option('fitvids_wp_ignore_selector')); ?>"
                           name="fitvids_wp_ignore_selector"
                           type="text"
                           class="regular-text"
                           id="fitvids_wp_ignore_selector"
                    >
                </td>
            </tr>
            </tbody>
        </table>

        <h2>Extras</h2>
        <p>Enable the Google jQuery CDN if FitVids is not working. You might be missing jQuery.</p>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">Use Google CDN</th>
                <td>
                    <input type="hidden" name="fitvids_wp_jq" value="false">
                    <label>
                        <input id="fitvids_wp_jq"
                               value="true"
                               name="fitvids_wp_jq"
                               type="checkbox"
                            <?php $this->print_cdn_field_checked(); ?>
                        >
                        My theme is missing jQuery. Add jQuery 1.12.4 from Google CDN.
                    </label>
                </td>
            </tr>
            </tbody>
        </table>

        <p class="submit"><input type="submit" name="submit" class="button-primary" value="Save Changes"/>

    </form>

</div>