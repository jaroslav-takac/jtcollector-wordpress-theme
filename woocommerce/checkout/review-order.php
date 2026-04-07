<?php
/**
 * Custom Review Order Table
 * JTCollector
 */

defined( 'ABSPATH' ) || exit;

$chosen_shipping_html = '';
$chosen_payment_title = '';

$chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods', [] );
$packages                = WC()->shipping()->get_packages();

if ( ! empty( $packages ) ) {
	foreach ( $packages as $package_index => $package ) {
		$chosen_method = $chosen_shipping_methods[ $package_index ] ?? '';

		if ( ! empty( $package['rates'] ) ) {
			foreach ( $package['rates'] as $rate_id => $rate ) {
				if ( $rate_id === $chosen_method ) {
					$chosen_shipping_html = wc_cart_totals_shipping_method_label( $rate );
					break 2;
				}
			}
		}
	}
}

$available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
$chosen_payment     = WC()->session->get( 'chosen_payment_method' );

if ( $chosen_payment && isset( $available_gateways[ $chosen_payment ] ) ) {
	$chosen_payment_title = $available_gateways[ $chosen_payment ]->get_title();
}
?>

<table class="shop_table woocommerce-checkout-review-order-table">
	<thead>
		<tr>
			<th class="product-name"><?php esc_html_e( 'Produkt', 'woocommerce' ); ?></th>
			<th class="product-total"><?php esc_html_e( 'Medzisúčet', 'woocommerce' ); ?></th>
		</tr>
	</thead>

	<tbody>
		<?php
		do_action( 'woocommerce_review_order_before_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) {
				?>
				<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
					<td class="product-name">
						<?php
						echo wp_kses_post( $_product->get_name() );
						echo '&nbsp;<strong class="product-quantity">&times;&nbsp;' . esc_html( $cart_item['quantity'] ) . '</strong>';
						?>
					</td>
					<td class="product-total">
						<?php echo wp_kses_post( WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ) ); ?>
					</td>
				</tr>
				<?php
			}
		}

		do_action( 'woocommerce_review_order_after_cart_contents' );
		?>
	</tbody>

	<tfoot>
		<tr class="cart-subtotal">
			<th><?php esc_html_e( 'Medzisúčet', 'woocommerce' ); ?></th>
			<td><?php wc_cart_totals_subtotal_html(); ?></td>
		</tr>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
				<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( $chosen_shipping_html ) : ?>
			<tr class="shipping">
				<th><?php esc_html_e( 'Doprava', 'woocommerce' ); ?></th>
				<td><?php echo wp_kses_post( $chosen_shipping_html ); ?></td>
			</tr>
		<?php endif; ?>

		<?php if ( $chosen_payment_title ) : ?>
			<tr class="payment-method">
				<th><?php esc_html_e( 'Platba', 'jtcollector' ); ?></th>
				<td><?php echo esc_html( $chosen_payment_title ); ?></td>
			</tr>
		<?php endif; ?>

		<tr class="order-total">
			<th><?php esc_html_e( 'Cena spolu', 'jtcollector' ); ?></th>
			<td><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>
	</tfoot>
</table>