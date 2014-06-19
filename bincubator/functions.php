<?php

/*
 * remove items from admin menu - not currently used
 */
add_action( 'admin_menu', 'remove_links_menu' );
function remove_links_menu() {
     remove_menu_page('index.php'); // Dashboard
     remove_menu_page('edit.php'); // Posts
     remove_menu_page('upload.php'); // Media
     remove_menu_page('link-manager.php'); // Links
     remove_menu_page('edit.php?post_type=page'); // Pages
     remove_menu_page('edit-comments.php'); // Comments
     remove_menu_page('themes.php'); // Appearance
     remove_menu_page('plugins.php'); // Plugins
     remove_menu_page('users.php'); // Users
     remove_menu_page('tools.php'); // Tools
     remove_menu_page('options-general.php'); // Settings
}

/*
 * only permit admins to see the admin bar
 */
if (!current_user_can('manage_options')) {
	add_filter('show_admin_bar', '__return_false');
}

/*
 * prevent any submissions from unregistered users
 */

add_filter('gform_validation', 'custom_validation');
function custom_validation($validation_result){
	if(! is_user_logged_in())
		$validation_result['is_valid'] = false;
	return $validation_result;
}

/*
 * fix the comment header 
 */
function comment_reform ($arg) {
	$arg['title_reply'] = __('Comment on This Page');
	return $arg;
}
add_filter('comment_form_defaults','comment_reform');

function change_message($message, $form){
	return "Failed Validation - " . $form["title"];
}

/*
 * custom post type for a library review
 */
add_action(
	"gform_post_submission_5", 
	"review_handler",
	10, 2
);

function review_handler($entry, $form)
{
	//echo "entry = " .  print_r($entry) . "<br/>";
	$post_id = $entry['post_id'];
	if(get_post_type($post_id) != 'bi_review')
		return;
	if(! is_user_logged_in())
		return;
	$post = get_post($post_id);
	$post->comment_status = 'open';
	$post->post_title = $entry[13];
	$post->post_status = 'pending';
	$post->post_parent = get_post_meta($post_id, 'post_parent', true);
    //echo "post->parent = " . $post->post_parent . "<br/>";
	wp_update_post( $post );
}

add_action( 'init', 'library_review_post_type' );
function library_review_post_type() {
	$labels = array(
		'name' => _x('Review', 'post type general name'),
		'singular_name' => _x('Review', 'post type singular name'),
		'add_new' => _x('Add New', 'Review'),
		'add_new_item' => __('Add New Review'),
		'edit_item' => __('Edit Review'),
		'new_item' => __('New Review'),
		'all_items' => __('All Reviews'),
		'view_item' => __('View Reviews'),
		'search_items' => __('Search Reviews'),
		'not_found' =>  __('No reviews found'),
		'not_found_in_trash' => __('No reviews found in Trash'), 
		'parent_item_colon' => '',
		'menu_name' => __('Reviews')		
	  );
	  $args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'has_archive' => true, 
		'hierarchical' => false,
		'menu_position' => null,
		'hierarchical' => true,		
		'supports' => array( 
			'title',
			'author', 
			'comments',
			'trackbacks',
			'custom-fields',
			'page-attributes'		
		),
		//'taxonomies' => array(
		//	'post_tag'
		//)
	);
	register_post_type('bi_review',$args);
}
add_filter( 'post_updated_messages', 'codex_reviews_updated_messages' );

function codex_reviews_updated_messages( $messages ) {
	global $post, $post_ID;
	
	$messages['review'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __('Review updated. <a href="%s">View review</a>'), esc_url( get_permalink($post_ID) ) ),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Review updated.'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('Library restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Review published. <a href="%s">View review</a>'), esc_url( get_permalink($post_ID) ) ),
		7 => __('Review saved.'),
		8 => sprintf( __('Review submitted. <a target="_blank" href="%s">Preview review</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __('Review scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview review</a>'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __('Review draft updated. <a target="_blank" href="%s">Preview library</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);
	
	return $messages;
}
add_action( 'contextual_help', 'bi_review_help_text', 10, 3 );

function bi_review_help_text( $contextual_help, $screen_id, $screen ) { 
  //$contextual_help .= var_dump( $screen ); // use this to help determine $screen->id
  if ( 'library' == $screen->id ) {
    $contextual_help =
      '<p>' . __('Things to remember when adding or editing a review:') . '</p>' .
      '<p>' . __('If you want to schedule the review to be published in the future:') . '</p>' .
      '<ul>' .
      '<li>' . __('Under the Publish module, click on the Edit link next to Publish.') . '</li>' .
      '<li>' . __('Change the date to the date to actual publish this review, then click on Ok.') . '</li>' .
      '</ul>';
  } elseif ( 'edit-review' == $screen->id ) {
    $contextual_help = 
      '<p>' . __('This is the help screen displaying the table of review blah blah blah.') . '</p>' ;
  }
  return $contextual_help;
}
add_filter( 'pre_get_posts', 'get_reviews' );

function get_reviews( $query ) {

	//if ( is_home() && $query->is_main_query() )
	//if(! is_page() )
		//$query->set( 'post_type', array( 'bi_library' ) );
	return $query;
}

/*
 * update data. for library submission
 */

add_action(
	"gform_post_submission_1", 
	"library_submission_handler",
	10, 2
);

function library_submission_handler($entry, $form)
{
	$post_id = $entry['post_id'];
	//echo "entry = " .  print_r($entry) . "<br/>";
	if(get_post_type($post_id) != 'bi_library')
		return;
	if(! is_user_logged_in())
		return;
	$post = get_post($post_id);
	$post->post_excerpt = $entry["10"]; 
	wp_set_post_tags($post_id, $entry["32"], false);
	//$post->post_status = 'pending';
	$post->comment_status = 'open';
	wp_update_post( $post );
}

add_filter(
	"gform_validation_message", 
	"change_message", 10, 2
);

/*
 * custom post type for a library submission
*/
add_action( 'init', 'library_submission_post_type' );
function library_submission_post_type() {
	$labels = array(
		'name' => _x('Libraries', 'post type general name'),
		'singular_name' => _x('Library', 'post type singular name'),
		'add_new' => _x('Add New', 'library'),
		'add_new_item' => __('Add New Library'),
		'edit_item' => __('Edit Library'),
		'new_item' => __('New Library'),
		'all_items' => __('All Libraries'),
		'view_item' => __('View Librares'),
		'search_items' => __('Search Libraries'),
		'not_found' =>  __('No libraries found'),
		'not_found_in_trash' => __('No libraries found in Trash'), 
		'parent_item_colon' => '',
		'menu_name' => __('Libraries')		
	  );
	  $args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		//'rewrite' => array(
		//	'slug'=>'libraries',
		//	'with_front' => false
		//),
		'rewrite' => true,
		'capability_type' => 'post',
		'has_archive' => true, 
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array( 
			'title',
			'editor', 
			'author', 
			'excerpt', 
			'comments',
			'custom-fields',
			'page-attributes'		
		),
		'taxonomies' => array(
			'post_tag'
		)
	);
	register_post_type('bi_library',$args);
}

//add filter to ensure the text Book, or book, is displayed when user updates a book 

add_filter( 'post_updated_messages', 'codex_library_updated_messages' );

function codex_library_updated_messages( $messages ) {
	global $post, $post_ID;
	
	$messages['library'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __('Library updated. <a href="%s">View library</a>'), esc_url( get_permalink($post_ID) ) ),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Library updated.'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('Library restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Library published. <a href="%s">View library</a>'), esc_url( get_permalink($post_ID) ) ),
		7 => __('Library saved.'),
		8 => sprintf( __('Library submitted. <a target="_blank" href="%s">Preview library</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __('Library scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview library</a>'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __('Library draft updated. <a target="_blank" href="%s">Preview library</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);
	
	return $messages;
}

add_action( 'contextual_help', 'bi_add_help_text', 10, 3 );

function bi_add_help_text( $contextual_help, $screen_id, $screen ) { 
  //$contextual_help .= var_dump( $screen ); // use this to help determine $screen->id
  if ( 'library' == $screen->id ) {
    $contextual_help =
      '<p>' . __('Things to remember when adding or editing alibrary:') . '</p>' .
      '<p>' . __('If you want to schedule the library review to be published in the future:') . '</p>' .
      '<ul>' .
      '<li>' . __('Under the Publish module, click on the Edit link next to Publish.') . '</li>' .
      '<li>' . __('Change the date to the date to actual publish this article, then click on Ok.') . '</li>' .
      '</ul>';
  } elseif ( 'edit-library' == $screen->id ) {
    $contextual_help = 
      '<p>' . __('This is the help screen displaying the table of library blah blah blah.') . '</p>' ;
  }
  return $contextual_help;
}

add_filter( 'pre_get_posts', 'get_libraries' );

function get_libraries( $query ) {
	//if ( is_home() && $query->is_main_query() )
	//if(! is_page() )
		//$query->set( 'post_type', array( 'bi_library' ) );
	return $query;
}

add_action('init', 'bi_register_shortcodes' );

function bi_register_shortcodes() {
	add_shortcode('html_include', 'html_include');
	add_shortcode('html_include2', 'html_include2');
	add_shortcode('libraries_by_name', 'bi_libraries_by_name');
	add_shortcode('libraries_by_category', 'bi_libraries_by_category');
	add_shortcode('reviews_by_date', 'bi_reviews_by_date');
}


function bi_review_line($post_id){
	echo get_the_author_link();
	echo '</a> - ';
	echo get_the_title();
	echo '<a href="review?gform_post_id=' . $post_id . '"> - more ...</a>';
	echo '<br/>';

	$rating = 0;
	echo "post_id = " . $post_id . "<br/>";
	$count = get_post_meta($post_id, 'design_count', true);
	echo "count = " . $count . "<br/>";
	if(NULL == $count)
		$count = 0;
	if($count > 0){
		$text = get_post_meta($post_id, 'design_rating_total', true);
		$rating = $text[0] / $count;

	}
	bi_stars($rating, $count);
	echo ' Design</br>';

	$rating = 0;
	$count = get_post_meta($post_id, 'implementation_count', true);
	if(NULL == $count)
		$count = 0;
	if($count > 0){
		$text= get_post_meta($post_id, 'implementation_rating_total', true);
		$rating = $text[0] / $count;
	}
	bi_stars($rating, $count);
	echo ' Implementation</br>';

	$rating = 0;
	$count = get_post_meta($post_id, 'documentation_count', true);
	if(NULL == $count)
		$count = 0;
	if($count > 0){
		$text = get_post_meta($post_id, 'documentation_rating_total', true);
		$rating = $text[0] / $count;
	}
	bi_stars($rating, $count);
	echo ' Documentation</br>';

	$rating = 0;
	$count = get_post_meta($post_id, 'accept_count', true);
	if(NULL == $count)
		$count = 0;
	if($count > 0){
		$text = get_post_meta($post_id, 'accept_rating_total', true);
		$rating = $text[0] / $count;
	}
	bi_stars($rating, $count);
	echo ' Accept</br>';
}

function bi_stars_line($post_id, $axis, $axis_name){
	$text = get_post_meta($post_id, $axis, true);
	if(NULL == $text)
		return;
	$rating = $text[0] * 100 / 5;
	echo '<div class="star-holder" title="(based on 24 ratings)">' .
	     '<div class="star star-rating" style="width: ' .
	     $rating .
	     'px"></div></div>' . 
	     $axis_name . '</br>';
}

function bi_review_summary($post_id){
    echo '<p>';
	echo get_the_author_link();
	echo '</a> - ';
	echo get_the_title();
	echo '<a href="review?gform_post_id=' . $post_id . '"> - more ...</a>';
	echo '<br/>';
    echo '<div class="review-line">';
	bi_stars_line($post_id, 'knowledge_rating', 'Reviewer Knowledge');
	bi_stars_line($post_id, 'effort_rating', 'Reviewer Effort');
	bi_stars_line($post_id, 'usefulness_rating', 'Potential Usefulness');
	bi_stars_line($post_id, 'design_rating', 'Design');
	bi_stars_line($post_id, 'implementation_rating', 'Implementation');
	bi_stars_line($post_id, 'documentation_rating', 'Documentation');
    echo '</div></p>';
}

function bi_reviews_by_date() {
	$library_post_id = $_GET['library_post_id'];
	$library_post = get_post($library_post_id);
	echo "<h3>" . $library_post->post_title . "</h3><br/>";
	$args = array(
		'post_parent'   => $library_post_id ,
		'post_type'   => 'bi_review',
		'post_status' => 'publish', 
		'nopaging'    => 'true',
		'orderby'     => 'date',
		'order'       => 'ASC'
	);
		
	$loop = new WP_Query($args);
	$count = 0;
	while ( $loop->have_posts() ){
		$loop->the_post();
		bi_review_summary(get_the_ID());
		$count++;
	}
	if(0 == $count)
		echo "No reviews yet for this library";
    if(! is_user_logged_in()){
        ?>
        <p>
        Login as a registered user if you want to add your own review.
        </p>
        <?php
        return;
    }
    else{
        ?>
        <p>
        <a class="blincubator_button" href="review?library_post_id=<?php echo $library_post_id; ?>">Add you're own review</a>
        </p>
        <?php
    }
	wp_reset_postdata();
}

function callback($matches){
	# static $baseurl = 'http://www.blincubator.com';
    $baseurl = home_url( $path);
        // if there is no '.' - must be a wordpress permalink
        if('' == $matches[4]){
                // return unchanged.
                return $matches[0];
        }
        // if it's a non-local url
        if('' != $matches[2] ){
                // return base of url
                return $matches[1] . "=\"" . "http://www." . $matches[3] . '"';
        }
        // otherwise it's a local - prefix with local base
        return $matches[1] . "=\"" . $baseurl . '/' . $matches[3] . '"';

}

function html_include($attributes){
	static $pattern = '%(href|src)="(http://www\.|http://|www\.|)([^"\.]*(\.)[^"]*)"%';
    $count = -1;
	$file = $attributes['file'];
	$page = file_get_contents($file);
	if(null == $page){
		echo $file . ' not found\n';
	}
	$page = preg_replace_callback(
		$pattern,
		'callback',
                $page,
                -1,
                $count
	);
	return $page;
}

function html_include2($attributes){
	static $pattern = '%(href|src)="(http://www\.|http://|www\.|)([^"\.]*(\.)[^"]*)"%';
    $count = -1;
	$file = $attributes['file'];

	$page = file_get_contents( home_url() . '/' . $file );
	if(null == $page){
		echo $file . ' not found\n';
	}
	$page = preg_replace_callback(
		$pattern,
		'callback',
                $page,
                -1,
                $count
	);
	return $page;
}

/* didn't work - but leave it here as a reminder
add_filter("gform_field_value_post_tags", "populate_post_tags");
function populate_post_tags($value){
	global $post;
	return wp_get_post_tags( $post->ID, array('fields' => 'names'));
}
*/

function bi_library_line(){
	global $post;
	$post_id = $post->ID;
	$output = null;
    $output .= '<dt><a href="' . get_permalink() . '?gform_post_id=' . $post_id . '">';
    $output .= get_the_title();
	$output .= '</a></dt>';
    $output .= "<dd>" . $post->post_excerpt;
	$output .= ' by ';
	$output .= get_the_author_link();
	$output .= '</dd>';
	return $output;
}

function bi_libraries_by_name() {
	$args = array(
		'post_type'   => 'bi_library',
		//'post_status' => 'publish', 
		'nopaging'    => 'true',
		'orderby'     => 'title',
		'order'       => 'ASC'
	);
		
	$output = '';
	$loop = new WP_Query($args);
	while ( $loop->have_posts() ){
		$loop->the_post();
		$output .= bi_library_line();
	}
	wp_reset_postdata();
	return $output;
}

function bi_libraries_by_category() {
	$args = array(
		'post_type'   => 'bi_library',
		//'post_status' => 'publish', 
		'nopaging'    => 'true',
		'orderby'     => 'title',
		'order'       => 'ASC'
	);
		
	$output = '';
	$loop = new WP_Query($args);
	while ( $loop->have_posts() ){
		$loop->the_post();
		$output .= bi_library_line();
	}
	return $output;
}

?>