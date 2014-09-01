<?php
/*
Template Name: Review
*/

wp_enqueue_script("jquery"); 
gravity_form_enqueue_scripts(1, false);
get_header(); 

function is_new(){
	if(null != $_GET['library_post_id'])
		return true;
	else
		return false;
}

if(!is_new()){
?>
<script type="text/javascript">
	jQuery(document).ready(function($){
		$('#respond').prop('hidden', true);
		$('.comment').prop('hidden', true);
		$('#comments_button').click(function(event) {
			var hide = ! $('#respond').prop('hidden');
			$('#respond').prop('hidden',hide);
			$('.comment').prop('hidden',hide);
		});
	});
</script>
<?php
}

function is_editable(){
	global $post;
	$author = $post->post_author;
	$user = get_current_user_id();
	if($author == $user)
		return true;
	else
		return false;
}

if(!is_editable() && !is_new()){
?>
<script type="text/javascript">
	jQuery(document).ready(function($){
		$('#gform_submit_button_5').prop('hidden', true);
		$('.gform_wrapper input').prop('disabled', true);
		$('.gform_wrapper textarea').prop('readonly', true);
	});
</script>
<?php
}

function review_form_values($post_id){
	//echo "post_id = " . $post_id . "<br/>";
	$properties = array(
		'post_title' =>  get_post($post_id)->post_title,
		'post_author' => get_post($post_id)->post_author,
		'knowledge_rating' => get_post_meta($post_id, 'knowledge_rating', true),
		'effort' => get_post_meta($post_id, 'effort', true),
		'effort_rating' => get_post_meta($post_id, 'effort_rating', true),
		'usefulness_rating' => get_post_meta($post_id, 'usefulness_rating', true),
		'design' => get_post_meta($post_id, 'design', true),
		'design_rating' => get_post_meta($post_id, 'design_rating', true),
		'implementation' => get_post_meta($post_id, 'implementation', true),
		'implementation_rating' => get_post_meta($post_id, 'implementation_rating', true),
		'documentation' => get_post_meta($post_id, 'documentation', true),
		'documentation_rating' => get_post_meta($post_id, 'documentation_rating', true),
		'accept' => get_post_meta($post_id, 'accept', true),
		'conditions' => get_post_meta($post_id, 'conditions', true),
		'post_parent' => get_post_meta($post_id, 'post_parent', true)
	);
	//print_r($properties);
	return $properties;
}
?>

<div id="container">
	<div id="content">
	<?php 
	get_sidebar();
	//global $post;
	$field_values = array();
	//echo "1 field_values = " . print_r($field_values) . "<br/>";
    $library_post_id;
	if(is_new()){
		$library_post_id = $_GET['library_post_id'];
		$field_values = array('post_parent' => $library_post_id);
		$library_post = get_post($library_post_id);
		$library_name = $library_post->post_title;
		//echo "2 field_values = " . print_r($field_values) . "<br/>";
	}
	else{
		$post_id = $_GET['gform_post_id'];
		$field_values = review_form_values($post_id);
		$post = get_post($post_id);
		$library_post_id = $post->post_parent;
		$library_post = get_post($library_post_id);
		$library_name = $library_post->post_title;
		//echo "3 field_values = " . print_r($field_values) . "<br/>";
	}
	echo "<h2>" . $library_name . "</h2><br/>";
	gravity_form(
		5,      // form id
		true,  // display title
		true,  // don't display description
		true,  // don't display inactive
		$field_values
	);
	/*
	echo "author = " . $post->post_author . "<br/>";
	echo "user = " . get_current_user_id() . "<br/>";
	echo "is_editable() = " . (is_editable() ? "true" : " false") . "<br/>";
	echo "is_new() = " . (is_new() ? "true" : " false") . "<br/>";
	*/
	if(!is_new()){
        echo apply_filters('the_content', '');
		?>
		<input class="blincubator_button" type="submit" value="Show/Hide Comments" id="comments_button" name="submit">
		<?php
		comment_form();
		wp_list_comments();
	}
	?>
	</div>
</div>
<?php get_footer(); 
?>