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
			$('.comment').prop('hidden',hide);
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
            // library name is only editable for new submissions
            $('.gform_wrapper #input_1_1').prop('readonly', <?php echo is_new() ? 'false' : 'true' ; ?>);
            $('.library_link input').css('cursor', mode ? 'auto' : 'pointer');

            // sponsor list is only visible when editing
            $('.library_visible').css('display', mode ? 'block' : 'none');

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
	//$tags = wp_get_post_tags( $post_id, array('fields' => 'names'));
	//echo "post_tags = " .  print_r($tags) . "<br/>";
	//echo "[0] = " . $tags[0]  . "<br/>";

    // the following returns one string per sponser in the format
    // logo_link | web_link | comment
    $raw_sponsor_list = get_post_meta($post_id, 'sponsor_list', false);
	//echo print_r($raw_sponsor_list, true) . "<br/>";

    $sponsor_list = "";
    foreach($raw_sponsor_list as &$s){
        $sponsor_list = $sponsor_list . $s . ',';
    }
    $sponsor_list = trim($sponsor_list, ",");

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
		'issues_link' => get_post_meta($post_id, 'issues_link', true),
        'sponsor_list' => $sponsor_list
	);
}

function new_sidebars(){
	if ( is_active_sidebar( 'tertiary-widget-area' ) ) : ?>
		<div id="tertiary" class="widget-area" role="complementary">
			<ul class="xoxo">
				<?php dynamic_sidebar( 'tertiary-widget-area' ); ?>
			</ul>
		</div><!-- #tertiary .widget-area -->
    <?php
        endif;
}

?>

<div id="container">
	<div id="content">
	<?php
	get_sidebar('right');
    //new_sidebars();
	//while ( have_posts() ) {
		the_post();
	//};
	$field_values = null;
	if(! is_new()){
		$_GET['gform_post_id'] = $post->ID;
		$field_values = library_form_values();
        $sponsor_list = explode(',', $field_values['sponsor_list']);
        foreach($sponsor_list as $sponsor){
            $sponsor_array = explode('|', $sponsor, 3);
            $logo = $sponsor_array[0];
            $link = $sponsor_array[1];
            echo '<a href="' . $link . '" class="sponsor-logo"><img src="' . $logo . '"></a>';
        }
        ?>
        <a
            class="popup blincubator_button sponsor-logo"
            href="http://rrsd.com/blincubator.com/contact-author?library_id=<?php the_ID(); ?>"
        >
            You can sponsor this library!
        </a>
        <?php
	}

	gravity_form(
		1,      // form id
		true,  // display title
		true,  // don't display description
		true,  // don't display inactive
		$field_values
	);
	if(! is_new()){
		if(is_editable()){
			?>
			<button class="blincubator_button" id="edit_button">Edit/View</button>
            <br>
			<?php
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
        ?>
        <div class="commentlist">
            <?php
                comment_form();
                wp_list_comments('', $comments);
            ?>
        </div>
        <?php
        echo apply_filters('the_content', '');
	}
	?>
	</div><!-- #content -->
</div><!-- #container -->
<?php get_footer(); ?>