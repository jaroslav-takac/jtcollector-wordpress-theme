<?php
/**
 * Theme functions and definitions
 *
 * @package JTCollector
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Theme setup
 */
function jtcollector_theme_setup(): void
{
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('html5', [
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	]);

	register_nav_menus([
		'primary' => __('Primary Menu', 'jtcollector'),
	]);
}
add_action('after_setup_theme', 'jtcollector_theme_setup');

/**
 * Enqueue theme assets
 */
function jtcollector_enqueue_assets(): void
{
	$theme_version = wp_get_theme()->get('Version');

	wp_enqueue_style(
		'jtcollector-main-style',
		get_template_directory_uri() . '/assets/css/main.css',
		[],
		$theme_version
	);

	wp_enqueue_script(
		'jtcollector-main-script',
		get_template_directory_uri() . '/assets/js/main.js',
		[],
		$theme_version,
		true
	);
}
add_action('wp_enqueue_scripts', 'jtcollector_enqueue_assets');