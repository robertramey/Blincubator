<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all posts by default.
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
        ?><h2><?php
        the_title();
        ?></h2><?php
        the_content();
        ?>
        <button class="blincubator_button" id="comments_button">Show/Hide Comments</button>
        <?php
        $comments = get_comments(array(
            'post_id' => $post->ID,
            'order' => 'ASC',
            'orderby' => 'date'
        ));
        echo "There are " . count($comments) . " comments and replies";
		comment_form(array('comment_notes_after' => ''));
        wp_list_comments('', $comments);
    }
    ?>
	</div> <!-- #content -->
</div> <!-- #container -->
<?php
get_footer(); 
?>
