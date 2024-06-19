import React from 'react';

import { useBlockProps } from '@wordpress/block-editor';

/* Types */
import type { Attributes } from './Attributes.type';
import type { BlockSaveProps } from '@wordpress/blocks';

export const Save = ( { attributes }: BlockSaveProps< Attributes > ) => {
	const { className, tagName, iconContent } = attributes;
	const TagName = ( tagName as keyof HTMLElementTagNameMap ) ?? 'span';

	return (
		<div { ...useBlockProps.save() }>
			<TagName className={ className ?? '' }>{ iconContent }</TagName>
		</div>
	);
};
