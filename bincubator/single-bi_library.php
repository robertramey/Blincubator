<?php
/*
Template Name: Library
*/

wp_enqueue_script("jquery"); 
gravity_form_enqueue_scripts(1, false);
get_header(); 

function is_new(){
	$user = get_current_user_id();
	return (! is_single()) && (null != $user);
	//return !is_single();
}

function is_editable(){
	global $post;
	$author = $post->post_author;
	$user = get_current_user_id();
	return ($author == $user) &&  (null != $user);
	//return $author == $user;
}

?>
<script type="text/javascript">
	jQuery(document).ready(function($){
		$('#respond').prop('hidden', true);
		$('.comment').prop('hidden', true);
		$('#comments_button').click(function(event) {
			var hide = ! $('#respond').prop('hidden');
			$('#respond').prop('hidden',hide);
			$('#comments').prop('hidden',hide);
		});

		var edit_mode = false;
		function set_edit_mode(mode){
			$('#gform_submit_button_1').prop('hidden', ! mode);
			$('#comments_button').prop('hidden', mode);
			$('#reviews_button').prop('hidden', mode);
            $('.ginput_container input').prop('readonly', ! mode);
            $('.ginput_container textarea').prop('readonly', ! mode);
            // author is never editable
            $('.gform_wrapper #input_1_17').prop('readonly', true);
            $('.library_link input').css('cursor', mode ? 'auto' : 'pointer');

			edit_mode = mode;
		}

		// setup handler for comment button
		$('#comments_button').click(function($) {
			show_comments( ! comments_visible );
		});

		// setup handler for edit/view button
		$('#edit_button').click(function($){
			set_edit_mode(!edit_mode);
		});

		$('.library_link').click(function($) {
			if(! edit_mode){
				window.open(this.children[1].firstChild.value);
				event.preventDefault();
			}
		});
		// set edit on for new documents, viewing for everthing else
		set_edit_mode(<?php echo is_new() ? 'true' : 'false' ; ?>);
	});
</script>

<?php

function library_form_values(){
	global $post;
	$post_id = $post->ID;
	$tags = wp_get_post_tags( $post_id, array('fields' => 'names'));
	//echo "post_tags = " .  print_r($tags) . "<br/>";
	//echo "[0] = " . $tags[0]  . "<br/>";
	return array(
		'post_id' => $post_id,
		'post_title' => $post->post_title,
		'post_excerpt' => $post->post_excerpt,
		'post_author' => get_userdata($post->post_author)->display_name,
		'post_tags' => wp_get_post_tags( $post_id, array('fields' => 'names')),
		'post_content' => $post->post_content,
		'copyright_date' => get_post_meta($post_id, 'copyright_date', true),
		'release_date' => get_post_meta($post_id, 'release_date', true),
		'release_number' => get_post_meta($post_id, 'release_number', true),
		'compilers_tested' => get_post_meta($post_id, 'compilers_tested', true),
		'cpp_standard' => get_post_meta($post_id, 'cpp_standard', true),
		'dependencies' => get_post_meta($post_id, 'dependencies', true),
		'build_and_link' => get_post_meta($post_id, 'build_and_link', true),
		'reviews_link' => get_post_meta($post_id, 'reviews_link', true),
		'documentation_link' => get_post_meta($post_id, 'documentation_link', true),
		'download_link' => get_post_meta($post_id, 'download_link', true),
		'repository_link' => get_post_meta($post_id, 'repository_link', true),
		'test_dashboard_link' => get_post_meta($post_id, 'test_dashboard_link', true),
		'issues_link' => get_post_meta($post_id, 'issues_link', true)
	);
}

?>

<div id="container">
	<div id="content">
	<?php 
	get_sidebar();
	//while ( have_posts() ) {
		the_post();
	//};
	$field_values = null;
	if(is_single()){
		$field_values = library_form_values();
		$_GET['gform_post_id'] = $post->ID;
	}
	gravity_form(
		1,      // form id
		true,  // display title
		true,  // don't display description
		true,  // don't display inactive
		$field_values
	);
	if(! is_new()){
		?>
		<a class="blincubator_button" id="statistics_button" href="http://rrsd.com/wordpresstest/wp-admin/admin.php?page=wp-slim-view-3&fs[user]=is_not_equal_to+<?php echo get_userdata($post->post_author)->user_login;?>&fs[type]=is_not_equal_to+1&fs[resource]=contains+<?php echo $post->post_name;?>">Display Statistics</a>
		<?php
		if(is_editable()){
			?>
			<input type="submit" value="Edit/View" class="blincubator_button" id="edit_button" name="submit">
			<?php
		}
        ?>
		<a class="blincubator_button" id="reviews_button" href="reviews?library_post_id=<?php the_ID(); ?>">Reviews</a>
        <input class="blincubator_button" value="Show/Hide Comments" id="comments_button" name="submit">
        <?php
        $comments = get_comments("post_id=$post->ID");
        wp_list_comments('', $comments);
		comment_form();
        ?>
        <?php

	}
	?>
	</div><!-- #content -->
</div><!-- #container -->
<?php get_footer(); ?>