import React from 'react';

/* WordPress Dependencies */
import { useBlockProps } from '@wordpress/block-editor';

/* Internal deps */
import { toTitleCase } from '../utils';

/* Types */
import type { Attributes } from './Attributes.type';
import type { BlockSaveProps } from '@wordpress/blocks';

export const Save = ( { attributes }: BlockSaveProps< Attributes > ) => {
	const { iconClass, iconTag, iconContent, ariaHidden, ariaLabel } =
		attributes;
	const TagName = ( iconTag as keyof HTMLElementTagNameMap ) ?? 'span';
	const defaultAriaLabel = attributes.iconContent
		? toTitleCase( attributes.iconContent ) + ' icon'
		: undefined;

	return (
		<div { ...useBlockProps.save() }>
			<TagName
				className={ iconClass ?? '' }
				aria-label={ ariaLabel || defaultAriaLabel }
				aria-hidden={ ariaHidden || undefined }
			>
				{ iconContent }
			</TagName>
		</div>
	);
};
