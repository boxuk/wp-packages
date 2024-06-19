import type { WPFormat } from '@wordpress/rich-text/build-types/register-format-type';

export type IconGroupIcon = {
	name: string;
	value: string;
};
export type IconGroup = WPFormat & {
	className: string;
	options: IconGroupIcon[];
};

declare global {
	interface Window {
		boxIconography?: {
			iconGroups: {
				title: string;
				name: string;
				tagName: string;
				className: string;
				icons: {
					label: string;
					content: string;
				}[];
			}[];
		};
	}
}
