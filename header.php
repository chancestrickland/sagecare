<?php
/**
 * The template for displaying the header.
 *
 * @package sagecare
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>

<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="profile" href="http://gmpg.org/xfn/11">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php do_action( 'after_body' ); // WPCS: prefix ok. ?>

<div id="js-site-wrapper" class="site-wrapper">
	<?php get_template_part( 'parts/layout/header/masthead' ); ?>
	<?php get_template_part( 'parts/layout/header/page-header' ); ?>
	<div id="primary-content-area" class="content-area">
