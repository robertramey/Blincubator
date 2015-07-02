<?php

//include_once('gravityforms-update-post/gravityforms-update-post.php');
add_filter('gform_update_post/public_edit', 'functions_return_true');
function functions_return_true(){
    //echo "functions:gform_update_post/public_edit\r";
    return true;
}

// Custom widget area.
function extra_widgets_init(){
    register_sidebar(
        array(
            'name' => __( 'Tertiary Widget Area'),
            'id' => 'tertiary-widget-area',
            'description' => __( 'widget area for sponsor logos', 'twentyten' ),
            'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
            'after_widget' => "</li>",
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        )
    );
}
add_action( 'widgets_init', 'extra_widgets_init' );

/*
 * remove items from admin menu - not currently used
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
add_action( 'admin_menu', 'remove_links_menu' );
*/

/*
 * only permit admins to see the admin bar
 */
function hide_admin_bar() {
	if( ! current_user_can('manage_options') )
		add_filter('show_admin_bar', '__return_false');
    add_theme_support( 'html5', array('comment-form') );
}
add_action( 'after_setup_theme', 'hide_admin_bar' );
 
/*
 * only display dashboard for admins
function hide_dashboard(){
	if ( ! current_user_can( 'manage_options' ) ){
		wp_redirect( home_url() );
		exit;
	}
}
add_action( 'admin_init', 'hide_dashboard' );
 */

/*
 * install script for autosizing text area
 */

function intialize_autosize(){
    wp_register_script( 'autosize', get_stylesheet_directory_uri() . '/jquery.autosize.js', array('jquery'), 0, false );
    wp_enqueue_script('autosize');
}
add_action('wp_enqueue_scripts', 'intialize_autosize');

/**
 * enhanced comment editor
add_filter( 'comment_form_field_comment', 'comment_editor' );
 
function comment_editor() {
  global $post;
 
  ob_start();
 
  wp_editor( '', 'comment', array(
    'textarea_rows' => 15,
    'teeny' => true,
    'quicktags' => false,
    'media_buttons' => false
  ) );
 
  $editor = ob_get_contents();
 
  ob_end_clean();
 
  //make sure comment media is attached to parent post
  $editor = str_replace( 'post_id=0', 'post_id='.get_the_ID(), $editor );
 
  return $editor;
}
 
// wp_editor doesn't work when clicking reply. Here is the fix.
add_action( 'wp_enqueue_scripts', '__THEME_PREFIX__scripts' );
function __THEME_PREFIX__scripts() {
    wp_enqueue_script('jquery');
}
add_filter( 'comment_reply_link', '__THEME_PREFIX__comment_reply_link' );
function __THEME_PREFIX__comment_reply_link($link) {
    return str_replace( 'onclick=', 'data-onclick=', $link );
}
add_action( 'wp_head', '__THEME_PREFIX__wp_head' );
function __THEME_PREFIX__wp_head() {
?>
<script type="text/javascript">
  jQuery(function($){
    $('.comment-reply-link').click(function(e){
      e.preventDefault();
      var args = $(this).data('onclick');
      args = args.replace(/.*\(|\)/gi, '').replace(/\"|\s+/g, '');
      args = args.split(',');
      tinymce.EditorManager.execCommand('mceRemoveEditor', true, 'comment');
      addComment.moveForm.apply( addComment, args );
      tinymce.EditorManager.execCommand('mceAddEditor', true, 'comment');
    });
  });
</script>
<?php
}
*/

function comment_form_shortcode( $content, $atts )
{
    ob_start();
    comment_form();
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
add_shortcode( 'comment_form', 'comment_form_shortcode', 10, 2 );

/*
 * alter instructions on comment form 
 add_filter('comment_form_defaults', 'change_allowed_fields');

function change_allowed_fields($defaults) 
{
    //All the comment form fields are available in the $defaults array
    $defaults['comment_notes_after'] = "All html5 markup permitted";
    return $defaults;
}
 */

/*
 * shortcode to add a link with the 'popup' class with data attributes for height, width and scrollbars
 */

function intialize_scripts(){
    wp_register_script( 'popup', content_url() . "/themes/" . basename(dirname(__FILE__)) . '/popup.js', array('jquery'), 0, false );
    wp_enqueue_script('popup');
}
add_action('wp_enqueue_scripts', 'intialize_scripts');

function popup_shortcode( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'url' => '#',
		'width' => '400',
		'height' => '400',
		'scrollbars' => 'yes',
		'alt' => ''
	), $atts ) );
	
	$showscrollbars = esc_attr( $scrollbars );
	
	if (strtolower( $showscrollbars ) == 'no') {
		$showscrollbars = '0'; 
	}
	
	if ($showscrollbars != '0') {
		$showscrollbars = '1'; 
	}
	
	return '<a href="' . esc_url( $url ) . '" class="popup" data-width="' . absint( $width ) . '" data-height="' . absint( $height ) . '" data-scrollbars="' . $showscrollbars . '" alt="' . esc_attr( $alt ) . '">' . $content . '</a>';
}
add_shortcode( 'popup', 'popup_shortcode', 10, 2 );

/*
 * disable form create of email form
 */
add_filter("gform_disable_post_creation_8", "disable_email_post_creation", 10, 3);
function disable_email_post_creation($is_disabled, $form, $entry){
    echo '<br/><br/><br/><br/><br/><br/>';

    $library_id = $_GET['library_id'];
    $library = get_post($library_id);
    $author_id = $library->post_author;
    //echo 'Library id: ' . $library_id . '<br />';

    $author = get_user_by('id', $author_id);
    $to_email = $author->user_email;
    $to_name = $author->display_name;

    $user = wp_get_current_user();
    $from_email = $user->user_email;
    $from_name = $user->display_name;

    $subject = 'Sponsorship Inquiry - ' . $library->post_title;
    $message =  '' ;
    if( $entry['5.1'] ){
        $message = $message . "We are looking for an Enhancement" . "\r\n";
    }
    if( $entry['5.2'] ){
        $message = $message . "We are looking for Support" . "\r\n";
    }
    if( $entry['5.3'] ){
        $message = $message . "We want to display our logo on your library web page" . "\r\n";
    }
    if( $entry['6'] ){
        $message = $message . "Payment:" . $entry['6'] . "\r\n";
    }
    if( $entry['2'] ){
        $message = $message . "\r\n" . $entry['2'] . "\r\n";
    }
    //echo $message;

    $headers = array(
        'From:' . $from_name . ' <' . $from_email . '>',
        'Return-path: <' . $from_email . '>',
        'Reply-to: <' . $from_email . '>'
    );

    /*
    echo 'To email: ' . $to_email . '<br/>';
    echo 'From email: ' . $from_email . '<br/>';
    echo 'subject: ' . $subject . '<br/>';
    echo 'message: ' . $message . '<br/>';
    echo 'headers:';
    print_r($headers);
    */

    wp_mail( $to_email, $subject, $message, $headers);
    //wp_mail( 'ramey@rrsd.com', 'Sponsorship Inquiry', $message, $headers);
    return true;
}

/*
 * prevent any submissions from unregistered users
 */

add_filter('gform_validation', 'user_validation');
function user_validation($validation_result){
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
 * custom post type for a library suggestion
 */
add_action(
	"gform_post_submission_9", 
	"suggestion_handler",
	10, 2
);

function suggestion_handler($entry, $form)
{
	if(! is_user_logged_in())
		return;
	$post_id = $entry['post_id'];
	$post = get_post($post_id);
    //echo "post = " .  print_r($post) . "<br/>";

    //$post_type = 'bi_suggestion';
	$post->comment_status = 'open';
	//$post->post_title = $entry[13];
	//$post->post_status = 'published';
	wp_update_post( get_object_vars($post) );
}

add_action( 'init', 'library_suggestion_post_type' );
function library_suggestion_post_type() {
	$labels = array(
		'name' => _x('Suggestion', 'post type general name'),
		'singular_name' => _x('Suggestion', 'post type singular name'),
		'add_new' => _x('Add New', 'Suggestion'),
		'add_new_item' => __('Add New Suggestion'),
		'edit_item' => __('Edit Suggestion'),
		'new_item' => __('New Suggestion'),
		'all_items' => __('All Suggestions'),
		'view_item' => __('View Suggestions'),
		'search_items' => __('Search Suggestions'),
		'not_found' =>  __('No suggestions found'),
		'not_found_in_trash' => __('No suggestions found in Trash'), 
		'parent_item_colon' => '',
		'menu_name' => __('Suggestions')
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
	register_post_type('bi_suggestion',$args);
}
add_filter( 'post_updated_messages', 'codex_suggestions_updated_messages' );

function codex_suggestions_updated_messages( $messages ) {
	global $post, $post_ID;
	
	$messages['suggestion'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __('suggestion updated. <a href="%s">View suggestion</a>'), esc_url( get_permalink($post_ID) ) ),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('suggestion updated.'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('Library restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('suggestion published. <a href="%s">View suggestion</a>'), esc_url( get_permalink($post_ID) ) ),
		7 => __('suggestion saved.'),
		8 => sprintf( __('suggestion submitted. <a target="_blank" href="%s">Psuggestion suggestion</a>'), esc_url( add_query_arg( 'psuggestion', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __('suggestion scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Psuggestion suggestion</a>'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __('suggestion draft updated. <a target="_blank" href="%s">Psuggestion library</a>'), esc_url( add_query_arg( 'psuggestion', 'true', get_permalink($post_ID) ) ) ),
	);
	
	return $messages;
}
add_action( 'contextual_help', 'bi_suggestion_help_text', 10, 3 );

function bi_suggestion_help_text( $contextual_help, $screen_id, $screen ) { 
  //$contextual_help .= var_dump( $screen ); // use this to help determine $screen->id
  if ( 'library' == $screen->id ) {
    $contextual_help =
      '<p>' . __('Things to remember when adding or editing a suggestion:') . '</p>' .
      '<p>' . __('If you want to schedule the suggestion to be published in the future:') . '</p>' .
      '<ul>' .
      '<li>' . __('Under the Publish module, click on the Edit link next to Publish.') . '</li>' .
      '<li>' . __('Change the date to the date to actual publish this suggestion, then click on Ok.') . '</li>' .
      '</ul>';
  } elseif ( 'edit-suggestion' == $screen->id ) {
    $contextual_help = 
      '<p>' . __('This is the help screen displaying the table of suggestion blah blah blah.') . '</p>' ;
  }
  return $contextual_help;
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
	wp_update_post( get_object_vars($post) );
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

add_action(
	"gform_post_submission_1", 
	"library_submission_handler",
	10, 2
);

function library_submission_handler($entry, $form)
{
	if(! is_user_logged_in())
		return;
    /*
    $url = parse_url( $entry['source_url']);
    echo "url = "; print_r($url); echo "<br/>";
    $query = $url['query'];
    echo "query = "; print_r($query); echo "<br/>";
    parse_str($query);
    $original_post_id = $gform_post_id;
    echo "original_post_id = "; print_r($original_post_id); echo "<br/>";
    */

	$new_post_id = $entry['post_id'];
	if(get_post_type($new_post_id) != 'bi_library')
		return;

    /*
    echo "post_id = " . $post_id . "<br/>";
    echo "entry = "; print_r($entry); echo "<br/>";
    echo "_POST = "; print_r($_POST); echo "<br/>";
    */

	$post = get_post($new_post_id);

    $post->ID = $original_post_id;
	$post->post_excerpt = $entry["10"]; 
	wp_set_post_tags($post_id, $entry["32"], false);
	$post->post_status = 'published';
	$post->comment_status = 'open';

    //echo "post = "; print_r($post); echo "<br/>";
    //return;

	wp_update_post( get_object_vars($post) );
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
		'name' => _x('Library', 'post type general name'),
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

function get_reviews_query($library_post_id) {
	$args = array(
		'post_parent' => $library_post_id ,
		'post_type'   => 'bi_review',
		'post_status' => 'published',
		'nopaging'    => 'true',
		'orderby'     => 'date',
		'order'       => 'ASC'
	);
	return new WP_Query($args);
}

function bi_reviews_by_date() {
	$library_id = $_GET['library_id'];
	$library_post = get_post($library_id);
	echo "<h3>" . $library_post->post_title . "</h3><br/>";

	$loop = get_reviews_query($library_id);
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
        <a class="blincubator_button" href="review?library_id=<?php echo $library_id; ?>">Add your own review</a>
        </p>
        <?php
    }
	wp_reset_postdata();
}

function callback($matches){
    $baseurl = home_url( $path);
        // if there is no '.' - must be a wordpress permalink
        if('' == $matches[4]){
                // return unchanged.
                return $matches[0];
        }
        // if it's a non-local url
        if('' != $matches[2] ){
                // return base of url
                return $matches[1] . "=\"" . $matches[2] . $matches[3] . '"';
        }
        // otherwise it's a local - prefix with local base
        return $matches[1] . "=\"" . $baseurl . '/' . $matches[3] . '"';

}

function html_include($attributes){
	static $pattern = '%(href|src)="(http://www\.|http://|https://|www\.|)([^"\.]*(\.)[^"]*)"%';
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
	static $pattern = '%(href|src)="(http://www\.|http://|https://|www\.|)([^"\.]*(\.)[^"]*)"%';
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
		//'post_status' => 'published',
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
		//'post_status' => 'published', 
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