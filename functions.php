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
	add_theme_support('custom-logo');

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
		'header_about_menu' => __('Header About Menu', 'jtcollector'),
		'header_info_menu'  => __('Header Info Menu', 'jtcollector'),
		'header_blog_menu'  => __('Header Blog Menu', 'jtcollector'),
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

	wp_enqueue_style(
		'jtcollector-header-style',
		get_template_directory_uri() . '/assets/css/header.css',
		['jtcollector-main-style'],
		$theme_version
	);

	wp_enqueue_script(
		'jtcollector-header-script',
		get_template_directory_uri() . '/assets/js/header.js',
		[],
		$theme_version,
		true
	);
}
add_action('wp_enqueue_scripts', 'jtcollector_enqueue_assets');