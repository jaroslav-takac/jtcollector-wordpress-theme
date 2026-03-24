<?php get_header(); ?>

<main class="site-main front-page">

<?php
while ( have_posts() ) :
	the_post();
	the_content();
endwhile;
?>

<?php get_template_part('template-parts/home-featured-products'); ?>

<?php get_template_part('template-parts/home-blog'); ?>

<div class="front-page-promo-slider">
  <?php echo do_shortcode('[smartslider3 slider="3"]'); ?>
</div>

</main>

<?php get_footer(); ?>