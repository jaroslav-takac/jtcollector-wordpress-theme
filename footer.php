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
		<div class="site-footer__inner">

			<div class="site-footer__left">
				<p class="site-footer__copy">
					© <?php echo esc_html(date_i18n('Y')); ?> JTCollector. All rights reserved.
				</p>

				<?php
				wp_nav_menu(
					[
						'theme_location' => 'footer',
						'container'      => 'nav',
						'container_class'=> 'site-footer__nav',
						'menu_class'     => 'site-footer__menu',
						'fallback_cb'    => false,
						'depth'          => 1,
					]
				);
				?>
			</div>

			<p class="site-footer__credit">
				Created by <a href="https://cv.jaroslavtakac.sk" target="_blank" rel="noopener">Jaroslav Takáč</a>
			</p>

		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>