import React from 'react';

/* WordPress Dependencies */
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { store as RichTextStore } from '@wordpress/rich-text';
import { useSelect } from '@wordpress/data';
import {
	Icon,
	Spinner,
	PanelBody,
	TextControl,
	ToggleControl,
} from '@wordpress/components';
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
	const { ariaLabel, ariaHidden } = attributes;
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
			<InspectorControls>
				<PanelBody title={ __( 'ARIA Settings' ) }>
					<TextControl
						label={ __( 'ARIA Label' ) }
						value={ ariaLabel ?? '' }
						onChange={ ( value ) =>
							setAttributes( { ariaLabel: value } )
						}
					/>
					<ToggleControl
						label={ __(
							'Hide element from assistive technologies (aria-hidden)'
						) }
						checked={ ariaHidden }
						onChange={ ( value ) =>
							setAttributes( { ariaHidden: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>
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
					<TagName
						className={ attributes.iconClass ?? '' }
						aria-label={ ariaLabel || undefined }
						aria-hidden={ ariaHidden }
					>
						{ attributes.iconContent }
					</TagName>
				) }
				{ ! attributes.iconContent && <Spinner /> }
			</div>
		</>
	);
};
