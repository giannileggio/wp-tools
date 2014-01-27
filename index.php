<?php
/*
Plugin Name: WP Tools
Plugin URI:
Description: Basic useful tools
Version: 0.3
Author: Lucidity Digital
Author URI: http://lucidity.ie
License:
*/

require_once 'inc/base.php';
require_once 'inc/functions.php';
define ('PLUGIN_URL', plugin_dir_url( __FILE__ ));

$tools = new WP_Tools();

// start editing here

/**
 * comment the scripts you don't need
 * @var array
 */
$tools->scripts = array(
	// 'fred_carousel' => 'jquery.carouFred.min.js',
	'owl_carousel' => 'vendor/jquery.owl.carousel.min.js',
	'validate' => 'vendor/jquery.validate.min.js',
	'mixitup' => 'vendor/jquery.mixitup.min.js',
	'fancybox' => 'vendor/jquery.fancybox.js',
	'easytabs' => 'vendor/jquery.easytabs.min.js',
	'maxlength' => 'vendor/jquery.maxlength.min.js'
	);

/**
 * comment the styles you don't need
 * @var array
 */
$tools->styles = array(
	'fancybox' => 'jquery.fancybox.css',
	'owl_carousel' => 'owl.carousel.css',
	// 'owl_carousel_theme' => 'owl.theme.css'
 );

// the image must be in assets/img folder
$tools->logo = "logo-image.png";

// stop editing here

$tools->init();
