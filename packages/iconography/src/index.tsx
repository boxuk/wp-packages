import React, { ComponentProps } from 'react';

/* WordPress Dependencies */
import domReady from '@wordpress/dom-ready';
import { registerFormatType } from '@wordpress/rich-text';
import { registerBlockType } from '@wordpress/blocks';
import { BlockControls } from '@wordpress/block-editor';

/* Internal deps */
import metadata from './block/block.json';
import { Edit } from './block/Edit';
import { Save } from './block/Save';
import { IconToolbarButton } from './IconToolbarButton';
import { getIconGroups, selectIconAtCurrentCursor } from './utils';

/* Types */
import type { IconGroup } from './types';

export const handleKeyDown =
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

export const handleKeyUp =
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

export const registerIconography = () => {
	const iconGroups = getIconGroups();
	if ( ! iconGroups ) {
		return;
	}

	iconGroups.forEach( ( iconGroup, index ) => {
		if ( index === 0 ) {
			iconGroup.edit = (
				props: ComponentProps< typeof IconToolbarButton >
			) => (
				<BlockControls group="inline">
					<IconToolbarButton { ...props } />
				</BlockControls>
			);
		}
		registerFormatType( iconGroup.name, iconGroup );
	} );

	// ArrowLeft, Backspace, and Delete need to be handled on KeyDown, because the selection is updated before the event fires.
	document.addEventListener( 'keydown', handleKeyEvent( iconGroups ) );
	// ArrowRight needs a special case for KeyUp, because the selection needs to happen after the event has moved the cursor into the next character.
	document.addEventListener( 'keyup', handleKeyEvent( iconGroups ) );
};

domReady( () => {
	registerIconography();
} );

registerBlockType( metadata, {
	edit: Edit,
	save: Save,
} );
