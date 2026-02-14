<?php

declare( strict_types=1 );

namespace GnosisBlocks;

use GnosisBlocks\Custom_Field;

$block_content = $attributes['content'] ?? '';

if ( empty( $block_content ) ) {
	return '';
}

// Get block wrapper attributes.
$wrapper_attributes = get_block_wrapper_attributes( array(
	'class' => 'wp-block-gnosis-wp-meta-field-block',
) );

// Create the custom field instance to process placeholders.
$custom_field = new Custom_Field();
$processed_content = $custom_field->process_block_placeholders( $block_content );

echo sprintf(
	'<div %s>%s</div>',
	$wrapper_attributes,
	$processed_content	
);