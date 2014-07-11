<?php
// Stylesheet
function tsk_lb_style(){
    wp_register_style( 'tsk_lb_css', plugins_url( 'css/lb-style.css' , __FILE__ ), false, '1.0.0' );
    wp_enqueue_style( 'tsk_lb_css' );
}
add_action('admin_enqueue_scripts', 'tsk_lb_style');
//
function tsk_new_ajx(){
	global $user_id;
	$title = $_POST['theTitle'];
	$content = $_POST['theContent'];
	$description = $_POST['theDesc'];
	$rank = $_POST['theRank'];
	$points = $_POST['thePoints'];
	$currency = $_POST['theCurrency'];
	$accept_message = $_POST['theAcceptMessage'];
	$mastery_message = $_POST['theMessage'];
	$complete_message = $_POST['theCompleteMessage'];
	$repeat = $_POST['theRepeat'];
	$repeat_message = $_POST['theRepeatMessage'];
	if ($repeat == 'false') {
		$repeats = '';
	} elseif ($repeat == 'true') {
		$repeats = 'on';
	}
	$the_cats = $_POST['theCats'];
	$go_new_task = array(
		'post_title'    => $title,
		'post_content'  => $content,
		'post_status'   => 'publish',
		'post_author'   => $user_id,
		'post_type'     => 'tasks',
	);
	// Insert the post into the database
	$new_task_id = wp_insert_post($go_new_task);
	if ($the_cats) {
		foreach ($the_cats as $cat) {
			wp_set_object_terms($new_task_id, $cat, 'task_categories');
		}
	}
	update_post_meta($new_task_id, 'go_mta_quick_desc', $description);
	update_post_meta($new_task_id, 'go_mta_req_rank', $rank);
	update_post_meta($new_task_id, 'go_mta_task_points', $points);
	update_post_meta($new_task_id, 'go_mta_task_currency', $currency);
	update_post_meta($new_task_id, 'go_mta_mastery_message', $mastery_message);
	update_post_meta($new_task_id, 'go_mta_accept_message', $accept_message);
	update_post_meta($new_task_id, 'go_mta_complete_message', $complete_message);
	update_post_meta($new_task_id, 'go_mta_task_repeat', $repeats);
	update_post_meta($new_task_id, 'go_mta_repeat_message', $repeat_message);
	echo $new_task_id;
	die();
}
add_action('wp_ajax_tsk_new_ajx', 'tsk_new_ajx');
// Post Type Boxes ajax
function tsk_cpt_bx_ajx() {
	$type_info = get_post_type_object($type);
	$args = array(
		'numberposts'      => 30,
		'orderby'          => 'post_date',
		'order'            => 'DESC',
		'post_type'        => $type
	);
	$the_posts = get_posts($args);
	$the_admin_url = get_admin_url();
	
	echo '<h2>'.$type_info->label.'</h2><br /><br />';
	$the_create_new_url = $the_admin_url.'post-new.php?&action=edit&post_type='.$type.'&go_tsk_id='.$tsk_id;
	echo '<a href="'.$the_create_new_url.'" target="_blank"><span>Add New...</span></a>';
	foreach ($the_posts as $post) {
		$the_url = $the_admin_url.'post.php?post='.$post->ID.'&action=edit&go_tsk_id='.$tsk_id;
		echo '<a href="'.$the_url.'" target="_blank"><span>'.$post->post_title.'</span></a>';
	}
	die();
}
add_action('wp_ajax_tsk_cpt_bx_ajx', 'tsk_cpt_bx_ajx');
function task_admin_lb($the_task_id, $task_check) {
?>
<script type="text/javascript">
function tsk_admn_opnr() {
	document.getElementById('tsk_admin_light').style.display='block';
	document.getElementById('tsk_admin_fade').style.display='block';
}
function quck_tsk_clear() {
	jQuery("#lte_tsk_title").val('');
	jQuery("#ltetskcontent").val('');
	jQuery("#ltetskdesc").val('');
	jQuery("#lte_tsk_rank").val('');
	jQuery("#lte_tsk_points").val('');
	jQuery("#lte_tsk_currency").val('');
	jQuery("#ltetskmasterymessage").val('');
	jQuery("#ltetskcompeltemessage").val('');
	jQuery("#ltetskacceptmessage").val('');
	jQuery("#lte_tsk_repeat").val('');
	jQuery("#ltetskrepeatmessage").val('');
}
function tsk_admn_clsr() {
	document.getElementById('tsk_admin_light').style.display='none';
	document.getElementById('tsk_admin_fade').style.display='none';
	quck_tsk_clear();
}
function new_task_ajax() {
	var lite_tsk_cat_arr = [];
	var lite_tsk_title = jQuery("#lte_tsk_title").val();
	var lite_tsk_content = tinymce.editors['ltetskcontent'].getContent();
	var lite_tsk_desc = tinymce.editors['ltetskdesc'].getContent();
	var lite_tsk_rank = jQuery('#lte_tsk_rank option:selected').val();
	var lite_tsk_points = jQuery("#lte_tsk_points").val();
	var lite_tsk_currency = jQuery("#lte_tsk_currency").val();
	var lite_tsk_mastery_message = tinymce.editors['ltetskmasterymessage'].getContent();
	var lite_tsk_complete_message = tinymce.editors['ltetskcompletemessage'].getContent();
	var lite_tsk_accept_message = tinymce.editors['ltetskcontent'].getContent();
	var lite_tsk_repeat = jQuery('#lte_tsk_repeat').is(':checked');
	var lite_tsk_repeat_message = tinymce.editors['ltetskrepeatmessage'].getContent();
	
	jQuery(".qck_tsk_catbox:checked").each(function() {
		lite_tsk_cat_arr.push(this.value);
	});
	
	jQuery.ajax({
		type:"POST",
		url: ajaxurl,
		data: {  
  			action: 'tsk_new_ajx',
			theTitle: lite_tsk_title,
			theContent: lite_tsk_content,
			theDesc: lite_tsk_desc,
			theRank: lite_tsk_rank,
			thePoints: lite_tsk_points,
			theCurrency: lite_tsk_currency,
			theMessage: lite_tsk_mastery_message,
			theCompleteMessage: lite_tsk_complete_message,
			theAcceptMessage: lite_tsk_accept_message,
			theRepeat: lite_tsk_repeat,
			theRepeatMessage: lite_tsk_repeat_message,
			theCats: lite_tsk_cat_arr,
  		},
		dataType : 'html',
		success:function(results){
			tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, '[go_task id="'+results+'"]');
			tsk_admn_clsr();
		},
		error: function(MLHttpRequest, textStatus, errorThrown){  
  			alert(errorThrown);
  		}
		});
}
function tsk_cpt_bx(type, id) {
	jQuery('#tsk_pt_bx_'+type+'').fadeOut(500);
	jQuery.ajax({
		type:"POST",
		url: ajaxurl,
		data: {  
  			action: 'tsk_cpt_bx_ajx',
			theType: type,
			theID: id, 		
		},
		dataType : 'html',
		success:function(results){
			jQuery('#tsk_pt_bx_'+type+'').css('width', '95%');
			jQuery('#tsk_pt_bx_'+type+'').insertAfter(jQuery('.tsk_body_info'));
			jQuery('#tsk_pt_bx_'+type+'').fadeIn(500);
			jQuery('#tsk_pt_bx_'+type+'').html(results);
		},
		error: function(MLHttpRequest, textStatus, errorThrown){  
  			alert(errorThrown);
  		}
		});
}
</script>

        <div id="tsk_admin_light" class="tsk_content_light">
        	<div class="tsk_light_head">
            <?php if ($task_check == false) { ?>
            	<a class="quick_tsk_hdr" id="qk_tsk_new" href="javascript:void(0);" current="true">New Task</a>
                <a class="quick_tsk_hdr" id="qk_tsk_exstn" href="javascript:void(0);" current="true">Existing Task</a>
            <?php } ?>
        		<a class="tsk_light_closer" href="javascript:void(0);" onclick="tsk_admn_clsr();">Close</a>
        	</div>
            <div class="tsk_body_light">
            <?php if ($task_check == false) { ?>
                <form id="lite_new_tsk">
                	<table class="form-table cmb_metabox">
                     <tr>
                        	<th style="width:18%">
                            	<label>Shortcode</label>
                            </th> 
                          	<td>
                            	<input type="text" value="[go_task id='<?php echo get_the_id(); ?>']" />
                                <p class="cmb_metabox_description">Shortcode of the task</p>
                            </td>
                       </tr>
                      <tr>
                        	<th style="width:18%">
                            	<label for="lte_tsk_title">Task Title</label>
                            </th> 
                          	<td>
                            	<input type="text" id="lte_tsk_title" />
                                <p class="cmb_metabox_description">Title of the Task</p>
                            </td>
                       </tr>
                    <tr>
                    	<th style="width:18%">
                        	<label for="lte_tsk_rank">Required Rank</label>
                        </th> 
                    	<td>
                    		<select id="lte_tsk_rank">
								<?php go_clean_ranks(); ?>
                    		</select>
                            <p class="cmb_metabox_description">rank required to begin task</p>
                    	</td>
                    </tr>
                    <tr>
                   <th style="width:18%">
                        	<label for="lte_tsk_presets">Presets</label>
                            
                        </th> 
                        <td>
                    <select id="go_presets" onchange="apply_presets();">
                    <option>...</option>
                    <?php
					$presets = get_option('go_presets',false);
					if($presets){
		   foreach($presets as $key=>$value){ 
		  
			 echo '<option value="'.$key.'" points="'.$value[0].'" currency="'.$value[1].'">'.$key.' - '.$value[0].' - '.$value[1].'</option>';  
		   }}
					 ?>
                    </select>
                    
                    </td>
                    </tr>
                    <tr>
                    	<th style="width:18%">
                        	<label for="lte_tsk_points">Points</label>
                            
                        </th> 
                        <td>
                        	<input type="text" id="lte_tsk_points" />
                            <p class="cmb_metabox_description">points awarded for encountering, accepting, completing, and mastering the task. (comma seperated, e.g. 10,20,50,70)</p>
                        </td>
                    </tr>
                    
                    <tr>
                    	<th style="width:18%">
                    		<label for="lte_tsk_currency">Currency</label>
                        </th>
                        <td>
                        	<input type="text" id="lte_tsk_currency">
                        	<p class="cmb_metabox_description">currency awarded for encountering, accepting, completing, and mastering the task. (comma seperated, e.g. 10,20,50,70)</p>
                        </td>
                    </tr>
                    	<tr>
                    	<th style="width:18%">
                    		<label for="lte_tsk_desc">Encounter Message</label>
                    	</th> <br>

						<td>
                    		<?php wp_editor( '', 'ltetskdesc', $settings = array('textarea_name' => 'ltetskdesc') ); ?>
                            <p class="cmb_metabox_description">Encounter message for your task</p>
                   		</td>		
                    </tr>
                       <tr>
                       		<th style="width:18%">
                            	<label for="lte_tsk_content">Accept Message</label>
                            </th> 
                            <td>
                            	<?php wp_editor( '', 'ltetskcontent', $settings = array('textarea_name' => 'ltetskcontent') ); ?>
                                <p class="cmb_metabox_description">The accept message for your task</p>
                            </td>
                      </tr>
                    
                    
					<tr>
                       		<th style="width:18%">
                            	<label for="lte_tsk_complete_message">Completion Message</label>
                            </th> 
                            <td>
                            	<?php wp_editor( '', 'ltetskcompletemessage', $settings = array('textarea_name' => 'ltetskcompletemessage') ); ?>
                                <p class="cmb_metabox_description">Enter a message for the user to recieve when they have completed the task</p>
                            </td>
                    </tr>
                    <tr>
                       		<th style="width:18%">
                            	<label for="lte_tsk_mastery_message">Mastery Message</label>
                            </th> 
                            <td>
                            	<?php wp_editor( '', 'ltetskmasterymessage', $settings = array('textarea_name' => 'ltetskmasterymessage') ); ?>
                                <p class="cmb_metabox_description">Enter a message for the user to recieve when they have mastered the task</p>
                            </td>
                    </tr>
                    <tr>
                    	<th style="width:18%">
                    		<label for="lte_tsk_repeat">Repeatable</label>
                    	</th>
                    	<td>
                    		<input type="checkbox" id="lte_tsk_repeat">
                    		<span class="cmb_metabox_description">Select to make task repeatable</span>
                    	</td>
                    </tr>
					<tr id="rpt_mssg" style="display:none;">
                       		<th style="width:18%">
                            	<label for="lte_tsk_repeat_message">Repeat Message</label>
                            </th> 
                            <td>
                            	<?php wp_editor( '', 'ltetskrepeatmessage', $settings = array('textarea_name' => 'ltetskrepeatmessage') ); ?>
                                <p class="cmb_metabox_description">Enter a message for the user to recieve when they have repeated the task</p>
                            </td>
                    </tr>
					<tr >
                       		<th style="width:18%">
                            	<label for="lte_tsk_cats">Task Categories</label>
                            </th> 
                            <td id="tsk_cat_qt">
								<?php
								$the_task_terms = get_terms('task_categories');
								if( !$the_task_terms){
								
								} else {
									$tsk_term_count = count($the_task_terms);
									if ( $tsk_term_count > 0 ){
										 foreach ($the_task_terms as $term) {
										   echo '<input class="qck_tsk_catbox" type="checkbox" value="'.$term->slug.'" /> '.$term->name.'<br />';
										}
									} else {
										return 'You either have no Task Categories, or you have no tasks with a category assigned.';
									} 
								}
								?>
                                <p class="cmb_metabox_description">Select all Categories you would like this task to fall under.</p>
                            </td>
                    </tr>
                       <tr>
                        	<th style="width:18%">
                            	<label>Shortcode</label>
                            </th> 
                          	<td>
                            	<input type="text" value="[go_task id='<?php echo get_the_id(); ?>']" />
                                <p class="cmb_metabox_description">Shortcode of the task</p>
                            </td>
                       </tr>
					<script type="text/javascript">
						jQuery('#lte_tsk_repeat').click(function() {
							if (jQuery('#lte_tsk_repeat').prop('checked')) {
								jQuery('#rpt_mssg').show('slow');
							} else {
								jQuery('#rpt_mssg').hide('slow');
							}
						});
						jQuery('.quick_tsk_hdr').click(function() {
							var the_hdr_itm_id = jQuery(this).attr('id');
							if (the_hdr_itm_id == 'qk_tsk_new') {
								jQuery('#qk_tsk_new').attr("current","true");
								jQuery('#qk_tsk_exstn').attr("current","false");
							} else if (the_hdr_itm_id == 'qk_tsk_exstn') {
								jQuery('#qk_tsk_new').attr("current","false");
								jQuery('#qk_tsk_exstn').attr("current","true");
							}
						});
function apply_presets(){
		var points =jQuery('#go_presets option:selected').attr('points');
		var currency =jQuery('#go_presets option:selected').attr('currency');
		jQuery('#lte_tsk_points').val(points);
		jQuery('#lte_tsk_currency').val(currency);
							
							}
					</script>
                </table>
                </form>
                <br />
                <div id="tsk_shortcode"></div>
                <a id="lte_tsk_submit" class="tsk_submitter" onclick="new_task_ajax();">Create Task</a>
                <br />
            <?php } elseif ($task_check == true) { 
					echo '<div class="tsk_body_info"><h2>Display Current Task</h2>Redirects to a New Page, with shortcode inserted into the content area.</div><br />';	
					$name = 'page';
					if ($name == 'page') {
						$a_name = "'".$name."'";
						$a_id = $_GET['post'];
						echo '<a href="'.$the_admin_url.'post-new.php?&action=edit&post_type=page&go_tsk_id='.$a_id.'" target="_blank">New Page</a>';
				}
				
			}
?>
            </div>
        </div>
        
        <div id="tsk_admin_fade" class="tsk_dark" onclick="tsk_admn_clsr();"></div>
<?php
}
function task_admin_lightbox_incl() {
	$task_id = $_GET["post"];
	$go_post_type = get_post_type($task_id);
		if ($go_post_type == 'tasks') {
			task_admin_lb($task_id, true);
		} else {
			task_admin_lb($task_id, false);
		}
}
add_action('admin_head', 'task_admin_lightbox_incl');
?>