<?php

/**
 * Title: WordPress admin install
 * Description:
 * Copyright: Copyright (c) 2005 - 2015
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 3.7.0
 * @since 3.7.0
 */
class Pronamic_WP_Pay_Admin_Install {
	/**
	 * Database updates
	 */
	private $db_updates = array(
		'2.0.0',
		'2.0.1',
		'3.3.0',
		'3.7.0',
	);

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an install object
	 *
	 * @see https://github.com/woothemes/woocommerce/blob/2.4.3/includes/class-wc-install.php
	 */
	public function __construct( $admin ) {
		$this->admin = $admin;

		// Actions
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Admin intialize
	 */
	public function admin_init() {
		// Install
		global $pronamic_pay_version;

		if ( get_option( 'pronamic_pay_version' ) !== $pronamic_pay_version ) {
			$this->install();
		}

		// Maybe update database
		if ( filter_has_var( INPUT_GET, 'pronamic_pay_update_db' ) && wp_verify_nonce( filter_input( INPUT_GET, 'pronamic_pay_nonce', FILTER_SANITIZE_STRING ), 'pronamic_pay_update_db' ) ) {
			$this->update_db();

			$this->admin->notices->remove_notice( 'update_db' );

			$this->redirect_to_about();
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Install
	 */
	private function install() {
		// Roles
		$this->create_roles();

		// Rewrite Rules
		flush_rewrite_rules();

		// Database update
		$current_db_version = get_option( 'pronamic_pay_db_version' );

		if ( $current_db_version && version_compare( $current_db_version, max( $this->db_updates ), '<' ) ) {
			$this->admin->notices->add_notice( 'update_db' );
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Create roles
	 */
	private function create_roles() {
		// Payer role
		add_role( 'payer', __( 'Payer', 'pronamic_ideal' ), array(
			'read' => true,
		) );
	}

	//////////////////////////////////////////////////

	/**
	 * Update database
	 */
	public function update_db() {
		global $pronamic_pay_version;

		$current_db_version = get_option( 'pronamic_pay_db_version', null );

		$updated = false;

		if ( $current_db_version ) {
			foreach ( $this->db_updates as $version ) {
				if ( version_compare( $current_db_version, $version, '<' ) ) {
					$file = plugin_dir_path( Pronamic_WP_Pay_Plugin::$file ) . 'includes/updates/update-' . $version . '.php';

					if ( is_readable( $file ) ) {
						include $file;

						update_option( 'pronamic_pay_db_version', $version );

						$updated = true;
					}
				}
			}
		}

		update_option( 'pronamic_pay_db_version', $pronamic_pay_version );
	}

	//////////////////////////////////////////////////

	/**
	 * Redirect to about
	 */
	private function redirect_to_about() {
		wp_safe_redirect( admin_url( 'index.php?page=pronamic-pay-about' ) );

		exit;
	}
}
