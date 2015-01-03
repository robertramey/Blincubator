<?php
/*
Template Name: bi_review
*/

wp_enqueue_script("jquery"); 
gravity_form_enqueue_scripts(5, false);
wp_enqueue_script('autosize');
get_header();

function is_editable(){
	global $post;
	$author = $post->post_author;
	$user = get_current_user_id();
	return ($author == $user) &&  (null != $user);
}

?>
<script type="text/javascript">
	jQuery(document).ready(
        function($){
            $('textarea').autosize();
        }
    );
</script>

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

add_filter('gform_update_post/public_edit', '__return_true');
if(!is_editable()){
?>
<script type="text/javascript">
	jQuery(document).ready(function($){
        $('.gform_body').prop('readonly', true);
		$('#gform_submit_button_5').prop('hidden', true);
		$('.gform_wrapper input').prop('disabled', true);
		$('.gform_wrapper textarea').prop('readonly', true);
	});
</script>
<?php
}

?>
<div id="container">
	<div id="content">
	<?php 
	get_sidebar('right');

    $post_id = $_GET['gform_post_id'];
    if (!empty($post_id)){
        $post = get_post($post_id);
		$library_id = $post->post_parent;
		$library_post = get_post($library_id);
		$library_name = $library_post->post_title;
        echo "<h2>" . $library_name . "</h2>";

        do_action('gform_update_post/setup_form', $post_id);
        gravity_form(5);
        ?>
        <br>
        <button class="blincubator_button" id="comments_button">Show/Hide Comments</button>
        <?php
        $comments = get_comments(array(
            'post_id' => $post->ID,
            'order' => 'ASC',
            'orderby' => 'date'
        ));
        echo "There are " . count($comments) . " comments";
        comment_form();
        wp_list_comments('', $comments);
    }
    else{
		$library_id = $_GET['library_id'];
		$library_post = get_post($library_id);
		$library_name = $library_post->post_title;
        echo "<h2>" . $library_name . "</h2>";
        gravity_form(5);
    }
    ?>
	</div>
</div>
<?php get_footer(); 
?>
