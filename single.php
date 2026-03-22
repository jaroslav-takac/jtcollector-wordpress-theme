<?php
/**
 * Single post template
 *
 * @package JTCollector
 */

defined('ABSPATH') || exit;

get_header();
?>

<main class="site-main site-page site-single">
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
			<section class="page-entry single-entry">
				<div class="page-entry__inner">
					<article <?php post_class('page-entry__main single-entry__main entry-card'); ?>>

						<header class="page-entry__header single-entry__header">
							<div class="single-entry__hero">
								<div class="single-entry__hero-content">
									<h1 class="page-entry__title single-entry__title"><?php the_title(); ?></h1>

									<div class="single-entry__meta">
										<span class="single-entry__meta-item">
											<?php echo esc_html(get_the_date('j. F Y')); ?>
										</span>

										<span class="single-entry__meta-separator">•</span>

										<span class="single-entry__meta-item">
											<?php the_author(); ?>
										</span>

										<?php
										$categories = get_the_category();
										if (!empty($categories)) :
										?>
											<span class="single-entry__meta-separator">•</span>
											<span class="single-entry__meta-item">
												<?php echo esc_html($categories[0]->name); ?>
											</span>
										<?php endif; ?>
									</div>

									<?php if (has_excerpt()) : ?>
										<div class="single-entry__excerpt">
											<?php the_excerpt(); ?>
										</div>
									<?php endif; ?>
								</div>

								<?php if (has_post_thumbnail()) : ?>
									<div class="single-entry__thumbnail">
										<?php the_post_thumbnail('large', ['class' => 'single-entry__image']); ?>
									</div>
								<?php endif; ?>
							</div>
						</header>

						<div class="page-entry__content single-entry__content entry-content">
							<?php the_content(); ?>
						</div>

					</article>
				</div>
			</section>
		<?php endwhile; ?>
	<?php endif; ?>
</main>

<?php get_footer(); ?>