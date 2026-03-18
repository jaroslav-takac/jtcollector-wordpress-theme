<?php
/**
 * Default page template
 *
 * @package JTCollector
 */

defined('ABSPATH') || exit;

get_header();
?>

<main class="site-main site-page">
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
			<section class="page-entry">
				<div class="page-entry__inner">
					<article <?php post_class('page-entry__main entry-card'); ?>>
						<header class="page-entry__header">
							<h1 class="page-entry__title"><?php the_title(); ?></h1>
						</header>

						<div class="page-entry__content entry-content">
							<?php the_content(); ?>
						</div>
					</article>
				</div>
			</section>
		<?php endwhile; ?>
	<?php endif; ?>
</main>

<?php get_footer(); ?>