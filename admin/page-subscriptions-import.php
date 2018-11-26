<?php
/**
 * Page subscriptions import.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

global $pronamic_pay_import_results;

if ( filter_has_var( INPUT_GET, 'message' ) ) {
	$message_id = filter_input( INPUT_GET, 'message', FILTER_SANITIZE_STRING );

	switch ( $message_id ) {
		case 'pages-generated':
			printf(
				'<div id="message" class="updated"><p>%s</p></div>',
				esc_html__( 'The default payment status pages are created.', 'pronamic_ideal' )
			);

			break;
	}
}

?>

<div class="wrap pronamic-pay-subscriptions-import">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Import', 'pronamic_ideal' ); ?></h1>

	<hr class="wp-header-end">

	<form method="post" enctype="multipart/form-data">
		<?php wp_nonce_field( 'pronamic_pay_subscriptions_import', 'pronamic_pay_nonce' ); ?>

		<?php if ( isset( $pronamic_pay_import_results ) && is_array( $pronamic_pay_import_results ) ) : ?>

			<pre><?php echo implode( PHP_EOL, $pronamic_pay_import_results ); // WPCS: xss ok. ?></pre>

		<?php else : ?>

		<h2><?php esc_html_e( 'Upload', 'pronamic_ideal' ); ?></h2>

		<p>
			<?php esc_html_e( 'Upload a CSV file to import.' ); ?>
		</p>

		<p>
			<?php

			printf(
				'<label class="pronamic-pay-form-control-file-button button">%s <input type="file" name="%s" /></label>',
				esc_html__( 'Select file', 'pronamic_ideal' ),
				'pronamic_subscriptions_import_file'
			);

			?>
		</p>

		<?php submit_button( __( 'Upload and import', 'pronamic_ideal' ) ); ?>

		<?php endif; ?>
	</form>
</div>
