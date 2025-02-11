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
 * Selects the icon in full that the cursor is currently in.
 *
 * Beacse the icon is usually made up of a `<span class="icon-type">icon_name</icon>`, the usual behaviours when selecting text don't work as expected.
 * This function will select the entire icon, so that the event that's bubbling up handles the icon as one selection.
 *
 * @param {IconGroup[]} iconGroups - The icon groups.
 *
 * @return {{ selection: Selection|null|undefined, icon: HTMLElement|null|undefined }} { selection, icon } - The selection and icon element.
 */
export const selectIconAtCurrentCursor = (
	iconGroups: IconGroup[] | undefined
): {
	selection?: Selection | null;
	icon?: HTMLElement | null;
} => {
	const selection = document.defaultView?.getSelection();
	const icon = iconGroups
		?.map( ( iconGroup ) => iconGroup.className )
		.includes( selection?.anchorNode?.parentElement?.className ?? '' )
		? selection?.anchorNode?.parentElement
		: null;

	if ( icon ) {
		selection?.selectAllChildren( icon );
	}

	return { selection, icon };
};

export const getIconGroups = (): IconGroup[] | undefined =>
	window.boxIconography?.iconGroups.map( ( value ): IconGroup => {
		const { icons, ...rest } = value;
		return {
			...rest,
			options: icons.map(
				( icon ): IconGroupIcon => ( {
					name: icon.label,
					value: icon.content,
				} )
			),
			interactive: false,
			edit: () => {},
		};
	} );
