<?php
/*
 * Template Name: simple_page
 */
get_header();
?>
<div id="container">
	<div id="content" role="main">
	<?php
	get_sidebar(); 
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