<?php
/**
 * Template Name: test_page
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
			$('#respond').prop('hidden',hide);
			$('.comment').prop('hidden',hide);
		});
    /*
	jQuery(document).ready(function($){
		$('#respond').prop('hidden', true);
		$('.comment').prop('hidden', true);
		$('#response_button').click(function(event) {
			var hide = ! $('#respond').prop('hidden');
			$('#respond').prop(
				'hidden',
				hide
			);
		});
		$('#comments_button').click(function(event) {
			var hide = ! $('.comment').prop('hidden');
			$('.comment').prop(
				'hidden',
				hide
			);
		});
    */
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
		<?php comment_form(); ?>
        <button class="blincubator_button" id="comments_button">Show/Hide Comments</button><?php
        $comments = get_comments(array(
            'post_id' => $post->ID,
            'order' => 'ASC',
            'orderby' => 'date'
        ));
        echo "There are " . count($comments) . " comments";
        wp_list_comments('', $comments);
    }
    ?>
	</div> <!-- #content -->
</div> <!-- #container -->
<?php
get_footer(); 
?>
