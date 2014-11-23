<?php
/*
 * Template Name: contact_author
 */
wp_enqueue_script("jquery"); 
get_header("popup");
?>
<body>
<div id="container">
	<div id="content" role="main">
        <hr>
        <p>
        Use this form to contact the author about enhancements, support, placement of your logo on the library page or anything else you'd like to communicate privately.
        </p>
        <?php
        $library_id = $_GET['library_id'];
        echo 'Library id: ' . $library_id . '<br />';
        $library = get_post($library_id);
        $author_id = $library->post_author;
        $author = get_user_by('id', $author_id);
        Echo 'Author: ' . print_r($author);

        $to_email = get_user_meta('user_email', $author_id);
        $to_name = get_user_meta('display name', $author_id);
        gravity_form(
            8,      // form id
            true,  // display title
            true,  // don't display description
            true,  // don't display inactive
            $field_values
        );
        while ( have_posts() ){
            the_post();
            the_content();
        }
        ?>
	</div><!-- #content -->
</div><!-- #container -->
<?php
get_footer(); 
?>
</body
