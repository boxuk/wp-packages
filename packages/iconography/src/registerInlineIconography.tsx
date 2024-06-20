import React, { ComponentProps } from 'react';

/* WordPress Dependencies */
import { registerFormatType } from '@wordpress/rich-text';

/* Internal deps */
import { IconToolbarButton } from './shared';
import { getIconGroups, selectIconAtCurrentCursor } from './utils';

/* Types */
import type { IconGroup } from './types';

const handleKeyDown =
	( iconGroups: IconGroup[] | undefined ) => ( event: KeyboardEvent ) => {
		switch ( event.key ) {
			case 'ArrowLeft':
			case 'Backspace':
			case 'Delete':
				selectIconAtCurrentCursor( iconGroups );
				break;
			default:
				break;
		}
	};

const handleKeyUp =
	( iconGroups: IconGroup[] | undefined ) => ( event: KeyboardEvent ) => {
		if ( 'ArrowRight' === event.key ) {
			const { selection, icon } = selectIconAtCurrentCursor( iconGroups );
			// Move the cursor one-right after the icon.
			if ( selection && icon ) {
				selection.setPosition( icon, 1 );
				selection.selectAllChildren( icon );
			}
		}
	};

export const handleKeyEvent =
	( iconGroups: IconGroup[] | undefined ) => ( event: KeyboardEvent ) => {
		switch ( event.type ) {
			case 'keydown':
				handleKeyDown( iconGroups )( event );
				break;
			case 'keyup':
				handleKeyUp( iconGroups )( event );
				break;
			default:
				break;
		}
	};

export const registerInlineIconography = () => {
	const iconGroups = getIconGroups();
	if ( ! iconGroups ) {
		return;
	}

	iconGroups.forEach( ( iconGroup, index ) => {
		if ( index === 0 ) {
			iconGroup.edit = (
				props: ComponentProps< typeof IconToolbarButton >
			) => <IconToolbarButton { ...props } />;
		}
		registerFormatType( iconGroup.name, iconGroup );
	} );

	// ArrowLeft, Backspace, and Delete need to be handled on KeyDown, because the selection is updated before the event fires.
	document.addEventListener( 'keydown', handleKeyEvent( iconGroups ) );
	// ArrowRight needs a special case for KeyUp, because the selection needs to happen after the event has moved the cursor into the next character.
	document.addEventListener( 'keyup', handleKeyEvent( iconGroups ) );
};

export default registerInlineIconography;
