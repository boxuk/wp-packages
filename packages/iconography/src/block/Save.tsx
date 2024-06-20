import React from 'react';

/* WordPress Dependencies */
import { useBlockProps } from '@wordpress/block-editor';

/* Types */
import type { Attributes } from './Attributes.type';
import type { BlockSaveProps } from '@wordpress/blocks';

export const Save = ( { attributes }: BlockSaveProps< Attributes > ) => {
	const { iconClass, iconTag, iconContent } = attributes;
	const TagName = ( iconTag as keyof HTMLElementTagNameMap ) ?? 'span';

	return (
		<div { ...useBlockProps.save() }>
			<TagName className={ iconClass ?? '' }>{ iconContent }</TagName>
		</div>
	);
};
