<?php
/**
 * Home - Blog section
 *
 * @package JTCollector
 */

defined('ABSPATH') || exit;

$home_blog_query = new WP_Query([
	'post_type'           => 'post',
	'posts_per_page'      => 4,
	'post_status'         => 'publish',
	'ignore_sticky_posts' => true,
]);

if (!$home_blog_query->have_posts()) {
	return;
}
?>

<section class="home-blog">
	<div class="home-blog__inner">
		<div class="home-blog__main">
			<header class="home-blog__header">
			<h2 class="home-blog__title">Novinky z blogu</h2>

			<a href="<?php echo get_permalink(get_option('page_for_posts')); ?>" class="home-blog__button">
				Pozrieť všetky články
			</a>
			</header>

			<div class="home-blog__grid">
				<?php while ($home_blog_query->have_posts()) : $home_blog_query->the_post(); ?>
					<?php
					$categories = get_the_category();
					$category_name = !empty($categories) ? $categories[0]->name : '';
					?>
					<article <?php post_class('home-blog-card'); ?>>
						<a class="home-blog-card__media" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(get_the_title()); ?>">
							<?php if (has_post_thumbnail()) : ?>
								<?php the_post_thumbnail('large', ['class' => 'home-blog-card__image']); ?>
							<?php else : ?>
								<div class="home-blog-card__image home-blog-card__image--placeholder"></div>
							<?php endif; ?>
						</a>

						<div class="home-blog-card__content">
							<div class="home-blog-card__meta">
								<span class="home-blog-card__date"><?php echo esc_html(get_the_date('j. n. Y')); ?></span>

								<?php if ($category_name) : ?>
									<span class="home-blog-card__meta-separator">•</span>
									<span class="home-blog-card__category"><?php echo esc_html($category_name); ?></span>
								<?php endif; ?>
							</div>

							<h3 class="home-blog-card__title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h3>

							<a class="jt-button jt-button--pill" href="<?php the_permalink(); ?>">
								Čítať celé
							</a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>

		</div>
	</div>
</section>

<?php wp_reset_postdata(); ?>