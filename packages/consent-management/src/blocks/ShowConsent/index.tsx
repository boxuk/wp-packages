import React from 'react';

/* WordPress deps */
import { registerBlockType } from '@wordpress/blocks';
import { RichText, useBlockProps } from '@wordpress/block-editor';
import type { BlockEditProps, BlockSaveProps } from '@wordpress/blocks';
import { useSelect } from '@wordpress/data';
import { store as RichTextStore } from '@wordpress/rich-text';

/* Internal deps */
import metadata from './block.json';
import { ReactComponent as icon } from './icon.svg';

type Attributes = { text: string };

type WP_Format = {
	name: string;
	title: string;
	tagName: string;
	className?: string;
	attributes?: Record< string, string >;
};

const Edit = ( {
	attributes,
	setAttributes,
}: BlockEditProps< Attributes > ) => {
	// Get all the formats available in the editor, so we can remove the link format
	const allFormats: Array< WP_Format > | undefined = useSelect(
		( select ) => select( RichTextStore ).getFormatTypes(),
		[]
	);

	return (
		<div { ...useBlockProps() }>
			<RichText
				value={ attributes.text }
				onChange={ ( text ) => setAttributes( { text } ) }
				allowedFormats={ ( allFormats ?? [] )
					.filter( ( format ) => format.name !== 'core/link' )
					.map( ( format ) => format.name ) }
			/>
		</div>
	);
};

const Save = ( { attributes }: BlockSaveProps< Attributes > ) => (
	<div { ...useBlockProps.save() }>
		<RichText.Content
			tagName="a"
			value={ attributes.text }
			data-cc="show-preferencesModal"
		/>
	</div>
);

registerBlockType( metadata, {
	icon,
	edit: Edit,
	save: Save,
} );
