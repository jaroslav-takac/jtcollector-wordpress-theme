<?php
/**
 * Blog - Category archive template
 *
 * @package JTCollector
 */

defined('ABSPATH') || exit;

get_header();
?>

<main class="site-main site-page site-archive site-category">
	<section class="page-entry archive-entry">
		<div class="page-entry__inner">
			<div class="page-entry__main archive-entry__main entry-card">

				<header class="page-entry__header archive-entry__header">
					<h1 class="page-entry__title archive-entry__title">
						<?php single_cat_title(); ?>
					</h1>

					<?php $category_description = category_description(); ?>
					<?php if (!empty($category_description)) : ?>
						<div class="archive-entry__intro page-entry__content">
							<?php echo wp_kses_post(wpautop($category_description)); ?>
						</div>
					<?php endif; ?>
				</header>

				<?php if (have_posts()) : ?>
					<div class="archive-list">
						<?php while (have_posts()) : the_post(); ?>
							<article <?php post_class('archive-card'); ?>>
								<a class="archive-card__media" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(get_the_title()); ?>">
									<?php if (has_post_thumbnail()) : ?>
										<?php the_post_thumbnail('large', ['class' => 'archive-card__image']); ?>
									<?php else : ?>
										<div class="archive-card__image archive-card__image--placeholder"></div>
									<?php endif; ?>
								</a>

								<div class="archive-card__content">
									<h2 class="archive-card__title">
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</h2>

									<div class="archive-card__meta">
										<span class="archive-card__meta-item">
											<?php echo esc_html(get_the_date('j. n. Y')); ?>
										</span>

										<span class="archive-card__meta-separator">•</span>

										<span class="archive-card__meta-item">
											<?php the_author(); ?>
										</span>

										<?php
										$categories = get_the_category();
										if (!empty($categories)) :
										?>
											<span class="archive-card__meta-separator">•</span>
											<span class="archive-card__meta-item">
												<?php echo esc_html($categories[0]->name); ?>
											</span>
										<?php endif; ?>
									</div>

									<div class="archive-card__excerpt">
										<?php
										if (has_excerpt()) {
											the_excerpt();
										} else {
											echo '<p>' . esc_html(wp_trim_words(get_the_excerpt(), 28, '...')) . '</p>';
										}
										?>
									</div>

									<a class="archive-card__link" href="<?php the_permalink(); ?>">
										Čítať článok
									</a>
								</div>
							</article>
						<?php endwhile; ?>
					</div>

					<?php the_posts_pagination([
						'mid_size'  => 1,
						'prev_text' => '← Predchádzajúca',
						'next_text' => 'Ďalšia →',
					]); ?>

				<?php else : ?>
					<div class="archive-entry__empty page-entry__content">
						<p>V tejto kategórii zatiaľ nie sú žiadne články.</p>
					</div>
				<?php endif; ?>

			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>