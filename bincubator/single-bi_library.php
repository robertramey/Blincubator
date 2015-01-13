<?php
/*
Template Name: bi_library
*/

wp_enqueue_script("jquery"); 
gravity_form_enqueue_scripts(1, false);
wp_enqueue_script('autosize');
get_header();

function is_editable(){
	global $post;
	$author = $post->post_author;
	$user = get_current_user_id();
	return ($author == $user) &&  (null != $user);
}

add_filter('gform_update_post/public_edit', 'single_bi_library_return_true');
function single_bi_library_return_true(){
    //echo "single_bi_library:gform_update_post/public_edit\r";
    return true;
}

?>

<script type="text/javascript">
	jQuery(document).ready(function($){
        $('textarea').autosize();
        $('#respond').prop('hidden', true);
        $('.comment').prop('hidden', true);
        $('#comments_button').click(function(event) {
            var hide = ! $('#respond').prop('hidden');
            $('#respond').prop('hidden',hide);
            $('.comment').prop('hidden',hide);
        });
        $('#gform_submit_button_1').val('Update');
        var edit_mode;
        function set_edit_mode(mode){
            // author library names are never editable
            $('.gform_body #input_1_17').prop('readonly', true);
            $('.gform_body #input_1_1').prop('readonly', true);

            // mode true => permit editing
            // mode false => don't permit editing
            $('.gform_body').prop('readonly', ! mode);
            $('.gform_body textarea').prop('readonly', ! mode);
            $('.gform_body input').prop('readonly', ! mode);

            // only show edit button when we actually edit
            $('#gform_submit_button_1').prop('hidden', ! mode);

            $('#edit_button').html(mode ? 'Cancel' : 'Edit');

            // adjust cursor according to mode
            $('.library_link input').css('cursor', mode ? 'auto' : 'pointer');
            // sponsor list is only visible when editing
            $('.library_visible').css('display', mode ? 'block' : 'none');
            edit_mode = mode;
        }
        set_edit_mode(false);
        $('#edit_button').click(function() {
            // toggle to new edit mode
            set_edit_mode(! edit_mode);
        });
        $('form').submit(function() {
            set_edit_mode(false);
        });
        $('.library_link').click(function($) {
            if(! edit_mode){
                window.open(this.children[1].firstChild.value);
                event.preventDefault();
            }
        });
	});
</script>

<div id="container">
	<div id="content">
	<?php
	get_sidebar('right');
    $post_id = $_GET['gform_post_id'];
    if (!empty($post_id)){
		$post = get_post($post_id);
		echo "<h2>" . $post->post_title . "</h2>";
        ?>
        <a
            class="blincubator_button sponsor-logo"
            href="http://rrsd.com/blincubator.com/contact-author?library_id=<?php the_ID(); ?>"
        >
            Sponsor this Library!
        </a>
        <?php
        $sponsor_list = get_post_meta($post_id, 'sponsor_list', false);
        foreach($sponsor_list as $sponsor){
            $sponsor_array = explode('|', $sponsor, 3);
            $logo = $sponsor_array[0];
            $link = $sponsor_array[1];
            echo '<a href="' . $link . '" class="sponsor-logo"><img src="' . $logo . '"></a>';
        }
        do_action('gform_update_post/setup_form', $post_id);

        $field_value = array(
            'post_author' => get_userdata($post->post_author)->display_name
        );
        gravity_form(1, true, true, false, $field_value);

        if(is_editable()){
            echo '<button class="blincubator_button" id="edit_button">Edit</button><br/>';
        }
        ?>
		<a class="blincubator_button" id="statistics_button" href="http://rrsd.com/wordpresstest/wp-admin/admin.php?page=wp-slim-view-3&fs[user]=is_not_equal_to+<?php echo get_userdata($post->post_author)->user_login;?>&fs[type]=is_not_equal_to+1&fs[resource]=contains+<?php echo $post->post_name;?>">Display Statistics</a>
        <br/>
		<a class="blincubator_button" id="reviews_button" href="reviews?library_id=<?php the_ID(); ?>">Reviews</a>
        <?php
        $reviews = get_reviews_query($post->ID);
        echo "There are " . $reviews->found_posts . " reviews";
        ?>
        <br/>
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
	?>
	</div><!-- #content -->
</div><!-- #container -->
<?php get_footer(); ?>