<?php
/*
 * Template Name: popup
 */
wp_enqueue_script("jquery"); 
	while ( have_posts() ){
		the_post();
		the_content();
	}
?>