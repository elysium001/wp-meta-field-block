<?php

declare( strict_types=1 );

namespace GnosisBlocks;

/**
 * Custom Field Handler Class
 *
 * Handles dynamic content replacement in blocks using placeholder syntax
 * like {custom_field_name} to pull from ACF fields or custom meta.
 */
class Custom_Field {

	/**
	 * Run the custom field handler.
	 *
	 * @return void
	 */
	public function run(): void {
		// Hook into block rendering to process placeholders.
		add_filter( 'render_block', array( $this, 'process_block_placeholders' ), 10, 2 );

		// Hook into template rendering in the full site editor.
		add_filter( 'the_content', array( $this, 'process_content_placeholders' ), 5 );
	}

	/**
	 * Process placeholders in individual blocks (public interface).
	 *
	 * @param string $block_content The block content.
	 *
	 * @return string The processed block content.
	 */
	public function process_block_placeholders( string $block_content ): string {
		if ( is_admin() && ! wp_doing_ajax() ) {
			return $block_content;
		}

		// Only process in frontend rendering.
		if ( is_admin() ) {
			return $block_content;
		}

		return $this->replace_placeholders( $block_content );
	}

	/**
	 * Process placeholders in content (templates).
	 *
	 * @param string $content The content to process.
	 *
	 * @return string The processed content.
	 */
	public function process_content_placeholders( string $content ): string {
		if ( is_admin() ) {
			return $content;
		}

		return $this->replace_placeholders( $content );
	}

	/**
	 * Replace all placeholders in content.
	 *
	 * Supports patterns like:
	 * - {field_name} - For custom field names
	 * - {acf:field_name} - For ACF fields
	 * - {meta:field_name} - For custom post meta
	 *
	 * @param string $content The content to process.
	 *
	 * @return string The processed content with placeholders replaced.
	 */
	private function replace_placeholders( string $content ): string {
		// Match placeholders like {field_name}, {acf:field_name}, {meta:field_name}
		$pattern = '/\{([a-zA-Z_][a-zA-Z0-9_:]*)\}/';

		return preg_replace_callback(
			$pattern,
			array( $this, 'replace_placeholder_value' ),
			$content
		);
	}

	/**
	 * Replace a single placeholder with its value.
	 *
	 * @param array $matches The regex matches.
	 *
	 * @return string The replacement value or original placeholder if not found.
	 */
	private function replace_placeholder_value( array $matches ): string {
		$placeholder = $matches[1];
		$value       = '';

		// Parse placeholder format: {type:field_name} or {field_name}
		if ( strpos( $placeholder, ':' ) !== false ) {
			list( $type, $field_name ) = explode( ':', $placeholder, 2 );
		} else {
			$type       = 'meta';
			$field_name = $placeholder;
		}

		$post_id = get_the_ID();

		if ( ! $post_id ) {
			return $matches[0];
		}

		switch ( $type ) {
			case 'acf':
				// Get ACF field value.
				if ( function_exists( 'get_field' ) ) {
					$value = get_field( $field_name, $post_id );
				}
				break;

			case 'meta':
				// Get custom post meta.
				$value = get_post_meta( $post_id, $field_name, true );
				break;

			default:
				// Default to custom post meta for backward compatibility.
				$value = get_post_meta( $post_id, $placeholder, true );
		}

		// Ensure value is a string and sanitized for output.
		if ( is_array( $value ) ) {
			$value = implode( ', ', array_map( 'strval', $value ) );
		}

		$value = is_scalar( $value ) ? (string) $value : '';

		return ! empty( $value ) ? esc_html( $value ) : $matches[0];
	}
}
