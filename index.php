<?php
/**
 * Main template file
 *
 * @package JTCollector
 */

get_header();
?>

<section class="content-area">
	<div class="site-container">
		<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class('entry-card'); ?>>
					<h1 class="entry-title">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</h1>

					<div class="entry-content">
						<?php the_content(); ?>
					</div>
				</article>
			<?php endwhile; ?>
		<?php else : ?>
			<article class="entry-card">
				<h1 class="entry-title"><?php esc_html_e('Nothing found', 'jtcollector'); ?></h1>
				<p><?php esc_html_e('It looks like there is no content yet.', 'jtcollector'); ?></p>
			</article>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();