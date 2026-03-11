<?php
/**
 * The footer for our theme
 *
 * @package JTCollector
 */
?>
</main>

<footer class="site-footer">
	<div class="site-container">
		<p class="site-footer__copy">
			&copy; <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?>. All rights reserved.
		</p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>