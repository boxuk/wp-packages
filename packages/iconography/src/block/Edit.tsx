import React from 'react';

/* WordPress Dependencies */
import { useBlockProps } from '@wordpress/block-editor';
import { store as RichTextStore } from '@wordpress/rich-text';
import { useSelect } from '@wordpress/data';
import { Icon, Spinner } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { replace } from '@wordpress/icons';

/* Internal deps */
import { IconToolbarButton } from '../shared';
import './style.scss';

/* Types */
import type { Attributes } from './Attributes.type';
import type { BlockEditProps } from '@wordpress/blocks';
import type { RichTextValue } from '@wordpress/rich-text';
import type { WPFormat } from '@wordpress/rich-text/build-types/register-format-type';

export const Edit = ( {
	attributes,
	setAttributes,
}: BlockEditProps< Attributes > ) => {
	const blockProps = useBlockProps();
	const { getFormatType } = useSelect(
		( select ) =>
			select( RichTextStore ) as {
				getFormatType: ( type: string ) => WPFormat;
			},
		[]
	);

	const handleChange = ( value: RichTextValue ) => {
		const formats = value.formats.shift();
		if ( ! formats?.length ) {
			return;
		}

		const format = getFormatType( formats[ 0 ].type );

		setAttributes( {
			iconContent: value.text,
			iconTag: format.tagName,
			iconClass: format.className,
		} );
	};

	const TagName =
		( attributes.iconTag as keyof HTMLElementTagNameMap ) ?? 'span';

	return (
		<>
			<IconToolbarButton
				icon={ <Icon icon={ replace } /> }
				onChange={ handleChange }
				value={ {
					text: '',
					formats: [],
					replacements: [],
					start: 0,
					end: 0,
				} }
				initialOpen={ ! attributes.iconContent }
			/>
			<div { ...blockProps }>
				{ attributes.iconContent && (
					<TagName className={ attributes.iconClass ?? '' }>
						{ attributes.iconContent }
					</TagName>
				) }
				{ ! attributes.iconContent && <Spinner /> }
			</div>
		</>
	);
};
