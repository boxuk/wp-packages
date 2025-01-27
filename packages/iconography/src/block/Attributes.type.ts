import type { WPFormat } from '@wordpress/rich-text/build-types/register-format-type';

export type Attributes = Partial< {
	iconClass: WPFormat[ 'className' ];
	iconTag: WPFormat[ 'tagName' ];
	iconContent: string;
	ariaLabel?: string;
	ariaHidden?: boolean;
} >;
