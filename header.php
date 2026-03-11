<?php
/**
 * The header for our theme
 *
 * @package JTCollector
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
	<div class="site-container site-header__inner">
		<div class="site-branding">
			<a class="site-branding__link" href="<?php echo esc_url(home_url('/')); ?>">
				<?php bloginfo('name'); ?>
			</a>
			<p class="site-branding__tagline"><?php bloginfo('description'); ?></p>
		</div>

		<nav class="site-navigation" aria-label="<?php esc_attr_e('Primary navigation', 'jtcollector'); ?>">
			<?php
			wp_nav_menu([
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'site-navigation__menu',
				'fallback_cb'    => false,
			]);
			?>
		</nav>
	</div>
</header>

<main class="site-main">