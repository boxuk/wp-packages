/* WordPress Dependencies */
import { registerFormatType } from '@wordpress/rich-text';

/* Internal Dependencies */
import { IconToolbarButton } from './IconToolbarButton';
import { getIconOptions, selectIconAtCurrentCursor } from './utils';

/* Types */
import type { IconGroup } from './types';
import domReady from '@wordpress/dom-ready';

/**
 * The supported icon groups.
 */
export const iconGroups: IconGroup[] = [
	{
		title: 'Outlined',
		name: 'content-only/ms-outlined',
		className: 'material-symbols-outlined',
		tagName: 'span',
		edit: IconToolbarButton,
		options: getIconOptions( 'Outlined' ),
		interactive: false,
	},
	{
		title: 'Rounded',
		name: 'content-only/ms-rounded',
		className: 'material-symbols-rounded',
		tagName: 'span',
		edit: () => {}, // only render the button on the first icon group. It contains markup for evertything in a grouped layout.
		options: getIconOptions( 'Rounded' ),
		interactive: false,
	},
	{
		title: 'Sharp',
		name: 'content-only/ms-sharp',
		className: 'material-symbols-sharp',
		tagName: 'span',
		options: getIconOptions( 'Sharp' ),
		edit: () => {}, // only render the button on the first icon group. It contains markup for evertything in a grouped layout.
		interactive: false,
	},
];

/**
 * Keydown event listener for handling the selection of an icon at the current cursor.
 * @param {KeyboardEvent} event The keyboard event.
 */
export const handleKeyDown = ( event: KeyboardEvent ) => {
	switch ( event.key ) {
		case 'ArrowLeft':
		case 'Backspace':
		case 'Delete':
			selectIconAtCurrentCursor();
			break;
		default:
			break;
	}
};

/**
 * Handle keyup event for ArrowRight.
 * @param {KeyboardEvent} event The keyboard event.
 */
export const handleKeyUp = ( event: KeyboardEvent ) => {
	if ( 'ArrowRight' === event.key ) {
		const { selection, icon } = selectIconAtCurrentCursor();
		// Move the cursor one-right after the icon.
		if ( selection && icon ) {
			selection.setPosition( icon, 1 );
			selection.selectAllChildren( icon );
		}
	}
};

/**
 * Handles the key event for Iconography.
 * @param {KeyboardEvent} event The keyboard event.
 */
export const handleKeyEvent = ( event: KeyboardEvent ) => {
	switch ( event.type ) {
		case 'keydown':
			handleKeyDown( event );
			break;
		case 'keyup':
			handleKeyUp( event );
			break;
		default:
			break;
	}
};

/**
 * Registers the formats, and the event listeners required for Iconography.
 */
const registerIconography = () => {
	iconGroups.forEach( ( iconGroup ) => {
		registerFormatType( iconGroup.name, iconGroup );
	} );

	// ArrowLeft, Backspace, and Delete need to be handled on KeyDown, because the selection is updated before the event fires.
	document.addEventListener( 'keydown', handleKeyEvent );
	// ArrowRight needs a special case for KeyUp, because the selection needs to happen after the event has moved the cursor into the next character.
	document.addEventListener( 'keyup', handleKeyEvent );
};

domReady( registerIconography );
