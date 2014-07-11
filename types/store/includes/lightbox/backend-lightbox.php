<?php
////////////////////////////////////////
// Lightbox for BACK END Game On Store Inserts
// The Author: Vincent Astolfi (http://nueue.net/)
////////////////////////////////////////
include('cat-item-ajax.php');
function go_store_head() { // Run this function that inserts things into the head
	global $post_type; // Bring the post type into global scope
	if( 'go_store' != $post_type ) { // If the current post type isn't go_store, do the following
?>
<script type="text/javascript">
		function go_close_lb() { // Close Lightbox Function ( changes styles to display nothing )
		document.getElementById('go_lightbox').style.display='none';document.getElementById('fade').style.display='none'; 
		}
		function go_cog_click(theboxID,thearrowID) { // Open cog menu of a specific item (theboxID is later defined by a php variable)
			document.getElementById(theboxID).style.cssText = 'background:#333; color:#fff; width:93%;';
			document.getElementById(thearrowID).style.cssText = 'border-left: 4px solid transparent; border-right: 4px solid transparent; border-top: 4px solid gray; border-bottom:none; margin: 7px 0px 0px -12px;';
		}
		function allowDrop(ev) {
			ev.preventDefault();
		}
		function drag(ev) {
			ev.dataTransfer.setData("Text",ev.target.id); // Allows "Drag" function for items in lightbox
		}
		function drop(ev) {
			ev.preventDefault();
			var data=ev.dataTransfer.getData("Text");
			ev.target.appendChild(document.getElementById(data));
			document.getElementById(data).className = "lightbox-inner-box-selected";
		}
		gosboxArray = new Array();
		function gogetid () {
		jQuery('.lightbox-inner-box-selected').each(function() {
		uncutID = this.id
		theID = uncutID.substring(4);
		gosboxArray.push(theID);
		});
		window.parent.send_to_editor('[go_store cats="'+gosboxArray+'"]');
    	window.parent.tb_remove();
		gosboxArray = new Array(); // Resets the array
		go_close_lb(); // Closes the Lightbox
		}
</script>

<div id="go_lightbox" class="store_white_content">
  <div class="go_box_top"> <a href="javascript:void(0)" class="go_box_close" onclick="go_close_lb();">Close</a> </div>
  <div id="go_lightbox_desc">
    <h2>Select a Category or Single Item</h2>
    <div class="description"></div>
    <div id="go-drop-box" ondrop="drop(event)" ondragover="allowDrop(event)"> </div>
    <div id="go-super-subs" class="go-super-sub">
      <?php
$gos_arr = array(
    'orderby'       => 'count', // order by number of posts in each cat
    'order'         => 'DESC', // descending order
    'hierarchical'  => 0, 
	'child_of'      => 0,
	'pad_counts'    => 0, 
); 
$go_type_terms = get_terms('store_types', $gos_arr); // get terms from store_types taxonomy using instructions from above array
$go_count = count($go_type_terms);
if ( $go_count > 0 ) {
		foreach ( $go_type_terms as $term ) {
			$parent = $term->parent;
			$parentID = $term->parent;
			$name = $term->name;
			$termID = $term->term_id;
			$termslug = $term->slug;
			$termchildren = get_term_children($termID, 'store_types');
			$the_count = 0;
			$the_identifier = 'gos_'.strtolower($name);
			?>
      <div id="<?php echo $the_identifier; ?>" class="lightbox-inner-box" draggable="true" ondragstart="drag(event)">
        <div id="gos_cog" onclick="go_cog_click('<?php echo $the_identifier; ?>', '<?php echo $the_identifier.'_arrow'; ?>');"><a class="go-arrow-right" id="<?php echo $the_identifier.'_arrow'; ?>"></a></div>
        <div id="gos_name"><span><strong><?php echo $name; ?></strong></span></div>
      </div>
      <?php
		}
}
?>
    </div>
  </div>
  <a id="gosc_insert" onclick="gogetid();">Insert Shortcode</a> </div>
<div id="fade" class="store_black_overlay"></div>
<style type="text/css">
#lightbox-outer {
	overflow:auto; 

}
#go-drop-box {
	border: 2px dashed gray;
	width: 94%;
	min-height: 50px;
	padding: 13px;
	margin-bottom: 10px;
	overflow:auto;
}
[draggable] {
  -moz-user-select: none;
  -khtml-user-select: none;
  -webkit-user-select: none;
  user-select: none;
  /* Required to make elements draggable in old WebKit */
  -khtml-user-drag: element;
  -webkit-user-drag: element;
  cursor:move;
}
.store_black_overlay {
	display: none;
	position: absolute;
	top: 0%;
	left: 0%;
	width: 100%;
	height: 100%;
	background-color: black;
	z-index:100001;
	-moz-opacity: 0.8;
	opacity:.80;
	filter: alpha(opacity=80);
}
.store_white_content {
	display: none;
	position: absolute;
	top: 25%;
	left: 25%;
	width: 50%;
	height: 55%;
	border: 1px solid #d1d1d1;
	background: #f5f5f5;
	background-image: -webkit-gradient(linear, left bottom, left top, from(#f5f5f5), to(#f9f9f9));
	background-image: -webkit-linear-gradient(bottom, #f5f5f5, #f9f9f9);
	background-image: -moz-linear-gradient(bottom, #f5f5f5, #f9f9f9);
	background-image: -o-linear-gradient(bottom, #f5f5f5, #f9f9f9);
	background-image: linear-gradient(to top, #f5f5f5, #f9f9f9);
	-webkit-border-radius: 3px;
	border-radius: 3px;
	z-index:100002;
	overflow: auto;
}
.go_box_close {
	position: absolute;
	right: 13px;
	top: 10px;
}
.go_box_top {
	width:100%;
	height:38px;
 	border-bottom: 1px solid #d1d1d1;  
	background: #eee;
	background-image: -webkit-gradient(linear, left bottom, left top, from(#e5e5e5), to(#f4f4f4));
	background-image: -webkit-linear-gradient(bottom, #e5e5e5, #f4f4f4);
	background-image: -moz-linear-gradient(bottom, #e5e5e5, #f4f4f4);
	background-image: -o-linear-gradient(bottom, #e5e5e5, #f4f4f4);
	background-image: linear-gradient(to top, #e5e5e5, #f4f4f4);
	-webkit-border-top-right-radius: 3px;
	-webkit-border-top-left-radius: 3px;
	border-top-right-radius: 3px;
	border-top-left-radius: 3px;
	border-color: #ccc #ccc #dfdfdf;
}
.lightbox-inner-box, .lightbox-inner-box-selected {
	width: 29%;
	float: left;
	margin: 1% 1.3% 1% 1.3%;
	background: white;
	-webkit-border-radius: 3px;
	border-radius: 3px;
	border: 1px solid #d1d1d1;
	padding: 4px 0px 16px 0px;
	transition: width 2s;
	-webkit-transition: width 2s;
	overflow:hidden;
}
.lightbox-inner-box span, .lightbox-inner-box-selected span {
	padding: 0px 0px 0px 19px;
	font-size: 16px;
}
#go_lightbox_desc {
	margin-left: 15px;
	margin-right: 15px;
}
#gos_cog {
	width: 17px;
height: 17px;
background: url(/wp-content/plugins/cube-gold/test-store/images/cog-small.png);
position: relative;
float: right;
margin-right: 2px;
cursor:pointer;
}
#gos_name {
	margin-top: 12px;
}
.go-arrow-right {
width: 0;
height: 0;
border-left: 4px solid gray;
border-right: none;
border-bottom: 4px solid transparent;
float: left;
margin: 4px 0px 0px -8px;
cursor: pointer;
border-top: 4px solid transparent;
}
#gosc_insert {
position: absolute;
bottom: 5%;
right: 5%;
background: #fff;
padding: 5px;
border-radius: 2px;
border: 1px solid #d1d1d1;
cursor:pointer;
}
</style>
<?php
	} elseif ($post_type == 'go_store') { // Else, do this stuff. I also could've said "elseif ($post_type == 'go_store')"

	?>
<div id="go_lightbox" class="store_white_content">
  <div class="go_box_top"> <a href="javascript:void(0)" class="go_box_close" onclick="document.getElementById('go_lightbox').style.display='none';document.getElementById('fade').style.display='none'">Close</a> </div>
  <div id="go_lightbox_desc">
    <h2>Choose a Content Type</h2>
    <div class="description">These buttons create a new instance of the selected post type with the shortcode automatically inserted in their respective content areas. You will be forwarded to the edit interface for this node.</div>
  </div>
  <?php
$go_get_post_types = get_post_types(); 
foreach ($go_get_post_types as $type ) {
	$go_store_id = $_GET["post"];
	$get_type = get_post_type_object($type);
  	$sing_name = $get_type->labels->singular_name;
	echo '<a href="/wp-admin/post-new.php?post_type='.$type.'&go_store=true&go_store_id='.$go_store_id.'" target="_blank" /><div id="go_store_lightbox_'.$type.'" class="lightbox-inner-box"><span>New '.$sing_name.'</span></div></a> ';
}
?>
</div>
<div id="fade" class="store_black_overlay"></div>
<style type="text/css">
.store_black_overlay {
	display: none;
	position: absolute;
	top: 0%;
	left: 0%;
	width: 100%;
	height: 100%;
	background-color: black;
	z-index:100001;
	-moz-opacity: 0.8;
	opacity:.80;
	filter: alpha(opacity=80);
}
.store_white_content {
	display: none;
	position: absolute;
	top: 25%;
	left: 25%;
	width: 50%;
	height: 55%;
	border: 1px solid #d1d1d1;
	background: #f5f5f5;
	background-image: -webkit-gradient(linear, left bottom, left top, from(#f5f5f5), to(#f9f9f9));
	background-image: -webkit-linear-gradient(bottom, #f5f5f5, #f9f9f9);
	background-image: -moz-linear-gradient(bottom, #f5f5f5, #f9f9f9);
	background-image: -o-linear-gradient(bottom, #f5f5f5, #f9f9f9);
	background-image: linear-gradient(to top, #f5f5f5, #f9f9f9);
	-webkit-border-radius: 3px;
	border-radius: 3px;
	z-index:100002;
	overflow: auto;
}
.go_box_close {
	position: absolute;
	right: 13px;
	top: 10px;
}
.go_box_top {
	width:100%;
	height:38px;
 	border-bottom: 1px solid #d1d1d1;  
	background: #eee;
	background-image: -webkit-gradient(linear, left bottom, left top, from(#e5e5e5), to(#f4f4f4));
	background-image: -webkit-linear-gradient(bottom, #e5e5e5, #f4f4f4);
	background-image: -moz-linear-gradient(bottom, #e5e5e5, #f4f4f4);
	background-image: -o-linear-gradient(bottom, #e5e5e5, #f4f4f4);
	background-image: linear-gradient(to top, #e5e5e5, #f4f4f4);
	-webkit-border-top-right-radius: 3px;
	-webkit-border-top-left-radius: 3px;
	border-top-right-radius: 3px;
	border-top-left-radius: 3px;
	border-color: #ccc #ccc #dfdfdf;
}
.lightbox-inner-box {
	width: 29%;
	float: left;
	margin: 15px 3px 0px 14px;
	border: 1px solid #d1d1d1;
	background: white;
	-webkit-border-radius: 3px;
	border-radius: 3px;
	border: 1px solid #d1d1d1;
	padding: 16px 0px 16px 0px;
	background-image: url(/wp-content/plugins/cube-gold/test-store/images/cog.png);
	background-repeat: no-repeat;
	background-position: top 2px right -3px;
}
.lightbox-inner-box span {
	padding: 0px 0px 0px 19px;
	font-size: 16px;
}
#go_store_lightbox_revision, #go_store_lightbox_nav_menu_item, #go_store_lightbox_attachment, #go_store_lightbox_safecss, #go_store_lightbox_go_store {
	display:none;
}
#go_lightbox_desc {
	margin-left: 15px;
	margin-right: 15px;
}
#go_store_lightbox_post {
	background-image: url(/wp-content/plugins/cube-gold/test-store/images/pin.png);
	background-repeat: no-repeat;
	background-position: top 3px right -4px;
}
#go_store_lightbox_page {
	background-image: url(/wp-content/plugins/cube-gold/test-store/images/page.png);
	background-repeat: no-repeat;
	background-position: top 4px right -4px;
}
#view-post-btn {
	display:none;
}
</style>
<?php
	}
}
add_action( 'admin_head', 'go_store_head' );
?>
