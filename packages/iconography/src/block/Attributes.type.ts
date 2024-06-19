import type { WPFormat } from '@wordpress/rich-text/build-types/register-format-type';

export type Attributes = Partial< {
	className: WPFormat[ 'className' ];
	tagName: WPFormat[ 'tagName' ];
	iconContent: string;
} >;
