import type { RichTextValue } from '@wordpress/rich-text';
import type { WPFormat } from '@wordpress/rich-text/build-types/register-format-type';

export type IconGroupIcon = {
	name: string;
	value: string;
};
export type IconGroup = WPFormat & {
	options: IconGroupIcon[];
};

export type IconToolbarButtonProps = {
	onChange: ( value: RichTextValue ) => void;
	value: RichTextValue;
	initialOpenState?: boolean;
};

export type IconPanelProps = {
	iconGroup: IconGroup;
	onClick: ( icon: RichTextValue ) => void;
	searchTerm: string;
};
