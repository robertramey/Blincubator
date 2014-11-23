<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url') ; ?>" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo plugins_url() . '/gravityforms/css/formsmain.css'; ?>" />
</head>
<body>
<div id="wrapper">
<div id="branding" role="banner">
    <div>
        <?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div'; ?>
        <<?php echo $heading_tag; ?> id="site-title">
            <span>
                <a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
            </span>
        </<?php echo $heading_tag; ?>>
        <div id="site-description"><?php bloginfo( 'description' ); ?></div>
    </div>
    <?php
    $left_image = get_stylesheet_directory_uri() . '/images/headers/pre-boost.jpg' ;
    $right_image = get_stylesheet_directory_uri() . '/images/headers/logo.jpg' ;
    ?>
    <div>
        <div id="left_logo" >
            <a href="http://www.blincubator.com">
                <img  height="164px" src="<?php echo $left_image  ; ?>" alt="Boost Incubator Homepage" border="none" />
            </a>
        </div>
        <div id="right_logo" >
            <img src="<?php echo $right_image  ; ?>" alt="Boost Incubator Logo" border="none" />
        </div>
    </div>
</div><!-- #branding -->
</div><!== #wrapper -->
</body>

