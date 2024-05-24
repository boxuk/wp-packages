/* Data */
import IconData from './icons.json';
import { iconGroups } from './index';

/* Types */
import type { IconGroupIcon, IconGroup } from './types';
import type { RichTextValue } from '@wordpress/rich-text';

/**
 * Generates the appropriate WordPress RichTextValue for the given icon.
 *
 * @param {IconGroupIcon} icon
 * @param {IconGroup}     iconGroup
 *
 * @return {RichTextValue} The RichTextValue for the icon.
 */
export function generateRichTextFormat(
	icon: IconGroupIcon,
	iconGroup: IconGroup
): RichTextValue {
	return {
		text: icon.value,
		formats: new Array( icon.value.length ).fill( [
			{ type: iconGroup.name },
		] ),
		start: 0,
		end: icon.value.length,
		replacements: [],
	};
}

/**
 * Gets all icons from the data set with a given suffix.
 *
 * @param {string} suffix The suffix to filter by.
 *
 * @return {IconGroupIcon[]} Array of { name, value } objects.
 */
export const getIconOptions = ( suffix: string ): IconGroupIcon[] => {
	const iconNames = IconData.filter( ( iconName: string ) =>
		iconName.endsWith( suffix )
	);

	return iconNames.map( ( name: string ) => {
		const value = snakeCase( name.replace( suffix, '' ) );
		return { name, value };
	} );
};

/**
 * Selects the icon in full that the cursor is currently in.
 *
 * Beacse the icon is usually made up of a `<span class="icon-type">icon_name</icon>`, the usual behaviours when selecting text don't work as expected.
 * This function will select the entire icon, so that the event that's bubbling up handles the icon as one selection.
 *
 * @return {{ selection: Selection|null|undefined, icon: HTMLElement|null|undefined }} { selection, icon } - The selection and icon element.
 */
export const selectIconAtCurrentCursor = (): {
	selection: Selection | null | undefined;
	icon: HTMLElement | null | undefined;
} => {
	const selection = document.defaultView?.getSelection();
	const icon = iconGroups
		.map( ( iconGroup ) => iconGroup.className )
		.includes( selection?.anchorNode?.parentElement?.className ?? '' )
		? selection?.anchorNode?.parentElement
		: null;

	if ( icon ) {
		selection?.selectAllChildren( icon );
	}

	return { selection, icon };
};

/**
 * PascalCase to snake_case
 *
 * @param {string} str
 *
 * @return {string} The snake_cased string.
 */
export const snakeCase = ( str: string ): string => {
	return str
		.replace( /([A-Z])/g, ' $1' ) // add space before all uppercase letters
		.replace( /([0-9]+)/g, ' $1' ) // add space before all numbers (including consecutive nums)
		.replace( /-/g, ' ' ) // replace dashes with spaces
		.trim() // remove leading and trailing whitespace
		.toLowerCase() // convert to lowercase
		.replace( / /g, '_' ); // convert spaces to underscores
};
