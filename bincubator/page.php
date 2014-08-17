<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
wp_enqueue_script("jquery"); 
get_header(); 
?>
<script type="text/javascript">
	jQuery(document).ready(function($){
		$('#respond').prop('hidden', true);
		$('.comment').prop('hidden', true);
		$('#comments_button').click(function(event) {
			var hide = ! $('#respond').prop('hidden');
			$('#respond').prop(
				'hidden',
				hide
			);
			$('.comment').prop(
				'hidden',
				hide
			);
		});
    });
</script>
<div id="container">
	<div id="content" role="main">
	<?php
	get_sidebar();
    /*
	while ( have_posts() ) :
		the_post();
		the_content();
		//get_template_part( 'content', 'page' );
        comments_template( '', true );
	endwhile; // end of the loop.
    */
    if( have_posts() ) {
        the_post();
        the_content();
        ?>
        <input class="blincubator_button" value="Show/Add/Hide Comments" id="comments_button" name="submit">
        <?php
        comment_form();
        $comments = get_comments("post_id=$post->ID");
        echo "There are " . count($comments) . " comments";
        wp_list_comments('', $comments);
    }
    ?>
	</div> <!-- #content -->
</div> <!-- #container -->
<?php
get_footer(); 
?>
