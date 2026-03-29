<?php get_header(); ?>

	<div class="front-page-hero-slider">
		<?php echo do_shortcode('[smartslider3 slider="2"]'); ?>
	</div>

<main class="site-main front-page site-page">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<section class="page-entry front-page-entry">
			<div class="page-entry__inner">
				<div class="page-entry__content">
					<?php the_content(); ?>
				</div>
			</div>
		</section>
	<?php endwhile; ?>

	<?php get_template_part('template-parts/home-featured-products'); ?>

	<?php get_template_part('template-parts/home-blog'); ?>

	<div class="front-page-promo-slider">
		<?php echo do_shortcode('[smartslider3 slider="3"]'); ?>
	</div>

	<div class="front-page-note">
		<div class="site-container">
			<p><strong>Poznámka:</strong></p>
			<p>Predaj kariet na tejto stránke prebieha v rámci súkromnej zbierky. Ponúkané položky predstavujú duplicitné alebo nevyužité karty (spravidla 1 kus z položky) a stránka neslúži ako podnikateľský e-shop.</p>
			<p>Predaj má charakter príležitostnej výmeny alebo odpredaja medzi zberateľmi v zmysle § 9 ods. 1 písm. a) zákona č. 595/2003 Z. z. o dani z príjmov.</p>
		</div>
	</div>
</main>

<?php get_footer(); ?>