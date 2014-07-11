<?php
if(is_admin()) {
	$tsk_pst_id = $_GET['go_tsk_id'];
	if($tsk_pst_id) {
		function tsk_pop_js() {
		?>
        <script type="text/javascript">
		var tsk_pp_the_id = <?php echo $_GET['go_tsk_id']; ?>;
		jQuery(window).load(function () {
			function tsk_pst_pop_ins() {
				tinyMCE.execInstanceCommand('content', "mceInsertContent", false, "[go_task id='"+tsk_pp_the_id+"']");
			}
			tsk_pst_pop_ins();
		});
		</script>
        <?php
		}
		add_action('admin_head', 'tsk_pop_js');
	}
}
?>