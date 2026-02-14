/**
 * Gnosis Custom Field Block
 * 
 * Block editor functionality for the custom field block.
 */

import { useBlockProps, BlockControls, InspectorControls } from '@wordpress/block-editor';
import { Button, PanelBody, ToolbarGroup } from '@wordpress/components';
import { registerBlockType } from '@wordpress/blocks';

function Edit( props ) {
	const blockProps = useBlockProps();
	const { attributes, setAttributes } = props;
	const { content, showPreview } = attributes;

	return (
		<div { ...blockProps }>
			<BlockControls>
				<ToolbarGroup>
					<Button
						icon={ showPreview ? 'edit' : 'visibility' }
						label={ showPreview ? 'Edit HTML' : 'Preview' }
						isPressed={ showPreview }
						onClick={ () => setAttributes( { showPreview: ! showPreview } ) }
						className="gnosis-preview-toggle"
					>
						{ showPreview ? 'Edit' : 'Preview' }
					</Button>
				</ToolbarGroup>
			</BlockControls>

			<InspectorControls>
				<PanelBody title="Custom Field Block Settings" initialOpen={ true }>
					<p style={ { fontSize: '12px', color: '#666' } }>
						Use placeholders like {'{field_name}'}, {'{acf:field_name}'}, or {'{meta:field_name}'} in your HTML markup.
					</p>
				</PanelBody>
			</InspectorControls>

			{ ! showPreview && (
				<textarea
					value={ content || '' }
					onChange={ ( e ) => setAttributes( { content: e.target.value } ) }
					placeholder="Enter your HTML markup here. Use {field_name} for placeholders..."
					style={ {
						width: '100%',
						minHeight: '300px',
						fontFamily: 'monospace',
						fontSize: '13px',
						padding: '10px',
						border: '1px solid #ddd',
						borderRadius: '4px',
					} }
				/>
			) }

			{ showPreview && (
				<div
					style={ {
						padding: '20px',
						border: '2px solid #ccc',
						borderRadius: '4px',
						backgroundColor: '#fff',
					} }
					dangerouslySetInnerHTML={ { __html: content || '<p style="color: #999;">Preview will display here...</p>' } }
				/>
			) }
		</div>
	);
}

registerBlockType( 'gnosis/wp-meta-field-block', {
	edit: Edit,
	save: () => {
		return null; // Rendered via PHP
	},
} );