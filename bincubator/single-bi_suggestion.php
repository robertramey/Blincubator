<?php
/*
Template Name: bi_suggestion
*/

wp_enqueue_script("jquery");
gravity_form_enqueue_scripts(9, false);
get_header(); 

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
			$('.comment').prop('hidden',hide);
		});
		// setup handler for comment button
		$('#comments_button').click(function($) {
			show_comments( ! comments_visible );
		});

	});
</script>

<div id="container">
	<div id="content">
	<?php 
	get_sidebar();
    the_post();

    if (empty($_GET['gform_post_id'])){
        the_title( '<h2>Library Idea<br>', '</h2><br>' );
        the_content();
        if(is_editable()){
            // Show a link to edit the form
            do_action('gform_update_post/edit_link', 'text=Edit');
        }
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
        // Show the form
        gravity_form(9);
    }

    ?>
	</div>
</div>
<?php get_footer(); 
?>