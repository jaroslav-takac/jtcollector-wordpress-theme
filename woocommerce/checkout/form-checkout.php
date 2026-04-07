<?php
/**
 * Custom Checkout Form
 * JTCollector
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Skryjeme default coupon toggle nad checkoutom
 * a presunieme formulár do pravého stĺpca.
 */
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

/**
 * V order review nechceme ešte raz renderovať payment blok,
 * lebo ho zobrazujeme samostatne vpravo.
 */
remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );

do_action( 'woocommerce_before_checkout_form', $checkout );

if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html(
		apply_filters(
			'woocommerce_checkout_must_be_logged_in_message',
			__( 'You must be logged in to checkout.', 'woocommerce' )
		)
	);
	return;
}
?>

<form name="checkout"
	method="post"
	class="checkout woocommerce-checkout jt-checkout"
	action="<?php echo esc_url( wc_get_checkout_url() ); ?>"
	enctype="multipart/form-data"
	aria-label="<?php echo esc_attr__( 'Checkout', 'woocommerce' ); ?>">

	<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

	<div class="jt-checkout__stack">

		<div class="jt-checkout__top">
			<div class="jt-checkout__main-col">

				<div class="jt-checkout__box jt-checkout__box--contact">
					<h2 class="jt-checkout__title">Kontaktné údaje</h2>
					<?php do_action( 'woocommerce_checkout_billing' ); ?>
				</div>

				<div class="jt-checkout__box jt-checkout__box--shipping-address">
					<h2 class="jt-checkout__title">Nastavenie dopravy</h2>

					<div class="jt-checkout__shipping-note">
						<p><strong>Doprava Packeta (pre SK) / Zásilkovna (pre ČR)*</strong><br>
						* Do poznámky napíšte adresu Z-Boxu alebo výdajného miesta.</p>
					</div>

					<?php do_action( 'woocommerce_checkout_shipping' ); ?>
				</div>

			</div>

			<aside class="jt-checkout__side-col">

				<div class="jt-checkout__box jt-checkout__box--coupon">
					<h2 class="jt-checkout__title">Zľavový kupón</h2>

					<?php if ( wc_coupons_enabled() ) : ?>
						<div class="jt-checkout__coupon-wrap">
							<?php woocommerce_checkout_coupon_form(); ?>
						</div>
					<?php endif; ?>
				</div>

				<div class="jt-checkout__box jt-checkout__box--shipping-methods">
					<h2 class="jt-checkout__title">Možnosti dopravy</h2>

					<div class="jt-checkout__methods jt-checkout__methods--shipping">
						<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
						<?php wc_cart_totals_shipping_html(); ?>
						<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
					</div>
				</div>

				<div class="jt-checkout__box jt-checkout__box--payment">
					<h2 class="jt-checkout__title">Možnosti platby</h2>
					<?php woocommerce_checkout_payment(); ?>
				</div>

			</aside>
		</div>

		<div class="jt-checkout__box jt-checkout__box--summary">
			<h2 class="jt-checkout__title">Zhrnutie objednávky</h2>

			<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

			<div id="order_review" class="woocommerce-checkout-review-order jt-checkout__review">
				<?php do_action( 'woocommerce_checkout_order_review' ); ?>
			</div>

			<div class="jt-checkout__actions">
				<a class="button jt-back-to-cart" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
					Späť na košík
				</a>

				<button type="submit" class="button alt jt-place-order" name="woocommerce_checkout_place_order" id="jt_place_order_bottom" value="<?php echo esc_attr__( 'Odoslať objednávku', 'jtcollector' ); ?>">
					Odoslať objednávku
				</button>
			</div>

			<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
		</div>

	</div>

	<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

</form>

<?php
do_action( 'woocommerce_after_checkout_form', $checkout );

/**
 * Vrátime payment hook späť pre prípad ďalších template častí.
 */
add_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
?>