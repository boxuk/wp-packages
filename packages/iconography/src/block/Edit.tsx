import React from 'react';

import { IconToolbarButton } from '../IconToolbarButton';
import {
	BlockControls,
	InspectorControls,
	useBlockProps,
} from '@wordpress/block-editor';
import { store as RichTextStore } from '@wordpress/rich-text';
import { useSelect } from '@wordpress/data';
import { useState } from '@wordpress/element';
import { Button, Panel, PanelBody } from '@wordpress/components';

/* Types */
import type { Attributes } from './Attributes.type';
import type { BlockEditProps } from '@wordpress/blocks';
import type { RichTextValue } from '@wordpress/rich-text';
import type { WPFormat } from '@wordpress/rich-text/build-types/register-format-type';
import { IconModal } from '../IconModal';

export const Edit = ( {
	attributes,
	setAttributes,
}: BlockEditProps< Attributes > ) => {
	const [ showIconModal, setShowIconModal ] = useState( false );
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
			tagName: format.tagName,
			className: format.className,
		} );
	};

	const TagName =
		( attributes.tagName as keyof HTMLElementTagNameMap ) ?? 'span';

	const ShowModalButton = () => (
		<Button
			variant={ 'primary' }
			onClick={ () => setShowIconModal( ! showIconModal ) }
		>
			Select Icon
		</Button>
	);

	return (
		<>
			<InspectorControls>
				<Panel>
					<PanelBody>
						<ShowModalButton />
					</PanelBody>
				</Panel>
			</InspectorControls>
			<div { ...blockProps }>
				{ attributes.iconContent && (
					<TagName className={ attributes.className ?? '' }>
						{ attributes.iconContent }
					</TagName>
				) }
				{ ! attributes.iconContent && <ShowModalButton /> }
				{ showIconModal && (
					<IconModal
						onRequestClose={ () => setShowIconModal( false ) }
						onChange={ handleChange }
						value={ {
							text: '',
							formats: [],
							replacements: [],
							start: 0,
							end: 0,
						} }
					/>
				) }
			</div>
		</>
	);
};
