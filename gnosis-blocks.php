<?php

declare( strict_types=1 );

/**
 * Plugin Name: Gnosis Blocks
 * Plugin URI: https://webtechgnosis.com
 * Description: Dynamic custom blocks with placeholder-based content pulling from ACF fields and custom meta.
 * Version: 1.0.0
 * Author: WebTech Gnosis
 * License: GPL-2.0+
 * Text Domain: gnosis-blocks
 * Domain Path: /languages
 */

namespace GnosisBlocks;

// Prevent direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GNOSIS_BLOCKS_VERSION', '1.0.0' );
define( 'GNOSIS_BLOCKS_PATH', plugin_dir_path( __FILE__ ) );
define( 'GNOSIS_BLOCKS_URL', plugin_dir_url( __FILE__ ) );

/**
 * Initialize the plugin.
 *
 * @return void
 */
function initialize() {
	// Load text domain for translations.
	load_plugin_textdomain(
		'gnosis-blocks',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages'
	);

	// Include custom field class.
	require_once GNOSIS_BLOCKS_PATH . 'inc/class-custom-field.php';

	// Register blocks from block.json.
	register_blocks();

}

/**
 * Register blocks from block.json metadata.
 *
 * @return void
 */
function register_blocks(): void {
	$block_path = GNOSIS_BLOCKS_PATH . 'build/custom-field-block/block.json';
	
	// Check if the block.json file exists
	if ( file_exists( $block_path  ) ) {
		register_block_type( $block_path );
	} else {
		error_log( 'Gnosis Blocks: block.json not found at ' . $block_path );
	}
}

/**
 * Initialize custom field handler on init hook.
 *
 * @return void
 */
function init_custom_field_handler() {
	$custom_field = new Custom_Field();
	$custom_field->run();
}

// Initialize the plugin on WordPress init hook.
add_action( 'init', __NAMESPACE__ . '\\initialize' );
