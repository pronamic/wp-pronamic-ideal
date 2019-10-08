<?php
/**
 * Exception
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

if ( is_a( $this, 'Pronamic\WordPress\Pay\PayException' ) ) : ?>

	<div class="error">
		<dl>
			<dt><?php esc_html_e( 'Code', 'pronamic_ideal' ); ?></dt>
			<dd><?php echo esc_html( $this->get_error_code() ); ?></dd>

			<dt><?php esc_html_e( 'Message', 'pronamic_ideal' ); ?></dt>
			<dd><?php echo esc_html( $this->get_message() ); ?></dd>

			<?php

			$data = $this->get_data();

			?>

			<?php if ( PRONAMIC_PAY_DEBUG && current_user_can( 'manage_options' ) && ! empty( $data ) ) : ?>

				<dt><?php esc_html_e( 'Data', 'pronamic_ideal' ); ?></dt>
				<dd>
					<?php

					echo '<pre>';

					// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
					print_r( $data );

					echo '</pre>';

					?>
				</dd>

			<?php endif; ?>
		</dl>
	</div>

<?php endif; ?>