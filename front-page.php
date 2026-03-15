<?php get_header(); ?>

<main class="site-main front-page">

<?php
while ( have_posts() ) :
    the_post();
    the_content();
endwhile;
?>

<?php get_template_part('template-parts/shop-home-entry'); ?>

</main>

<?php get_footer(); ?>