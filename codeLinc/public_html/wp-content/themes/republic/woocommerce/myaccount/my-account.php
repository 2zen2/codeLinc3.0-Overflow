<?php
/**
 * My Account page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wc_print_notices(); ?>

<p class="myaccount_user">
	<?php
	printf(
		__( '<i class="fa fa-user"></i> Hello <strong>%1$s</strong> (not %1$s? <a href="%2$s">Sign out</a>).', 'republic' ) . ' ',
		$current_user->display_name,
		wc_get_endpoint_url( 'customer-logout', '', wc_get_page_permalink( 'myaccount' ) )
	);

	printf( __( 'Manage You Account from Dashboard <a href="%s">Edit Your Account Details</a>.', 'republic' ),
		wc_customer_edit_account_url()
	);
	?>
</p>
<ul class="tabs vertical" data-tab>
  <li class="tab-title active"><a href="#panel11"><i class="fa fa-download"></i> <?php _e('Downloads', 'republic'); ?></a></li>
  <li class="tab-title"><a href="#panel21"><i class="fa fa-th-list"></i> <?php _e('My Orders', 'republic'); ?></a></li>
  <li class="tab-title"><a class="3" href="#panel31"><i class="fa fa-map-marker"></i> <?php _e('My Address', 'republic'); ?></a></li>
</ul>
<?php do_action( 'woocommerce_before_my_account' ); ?>
<div class="tabs-content">
  <div class="content active" id="panel11">
    <p><?php wc_get_template( 'myaccount/my-downloads.php' ); ?></p>
  </div>
  <div class="content" id="panel21">
    <p><?php wc_get_template( 'myaccount/my-orders.php', array( 'order_count' => $order_count ) ); ?></p>
  </div>
  <div class="content" id="panel31">
    <p><?php wc_get_template( 'myaccount/my-address.php' ); ?></p>
  </div>
 </div>

<?php do_action( 'woocommerce_after_my_account' ); ?>
