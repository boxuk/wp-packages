import { describe, expect, jest, test } from '@jest/globals';
import { registerFormatType } from '@wordpress/rich-text';

/* Internal Dependencies */
import { selectIconAtCurrentCursor, getIconGroups } from '../utils';
import {
	handleKeyEvent,
	registerInlineIconography,
} from '../registerInlineIconography';

jest.mock( '@wordpress/rich-text', () => ( {
	registerFormatType: jest.fn(),
} ) );

jest.mock( '../shared', () => ( {
	IconToolbarButton: jest.fn(),
} ) );

jest.mock( '../utils', () => ( {
	getIconGroups: jest.fn().mockReturnValue( [ {}, {}, {} ] ),
	selectIconAtCurrentCursor: jest.fn().mockReturnValue( {
		selection: {
			setPosition: jest.fn(),
			selectAllChildren: jest.fn(),
		},
		icon: {},
	} ),
} ) );

describe( 'registering iconography', () => {
	test( 'should register all 3 types by default', () => {
		registerInlineIconography();
		expect( getIconGroups ).toBeCalledTimes( 1 );
		// just by having imported the file, the registerFormatType should be called 3 times as per the 3 mocked values.
		expect( registerFormatType ).toBeCalledTimes( 3 );
	} );

	test( 'should not resister any types if no icon groups are found', () => {
		jest.clearAllMocks();
		getIconGroups.mockReturnValue( undefined );
		expect( registerFormatType ).toBeCalledTimes( 0 );
		registerInlineIconography();
	} );
} );

describe( 'event listener', () => {
	const dataProvider = [
		{ eventType: 'keydown', key: 'ArrowLeft', shouldCall: true },
		{ eventType: 'keydown', key: 'Backspace', shouldCall: true },
		{ eventType: 'keydown', key: 'Delete', shouldCall: true },
		{ eventType: 'keydown', key: 'ArrowRight', shouldCall: false },
		{ eventType: 'keydown', key: 'Enter', shouldCall: false },
		{ eventType: 'keyup', key: 'ArrowLeft', shouldCall: false },
		{ eventType: 'keyup', key: 'Backspace', shouldCall: false },
		{ eventType: 'keyup', key: 'Delete', shouldCall: false },
		{ eventType: 'keyup', key: 'ArrowRight', shouldCall: true },
		{ eventType: 'keyup', key: 'Enter', shouldCall: false },
	];

	test.each( dataProvider )(
		'for $eventType handles $key correctly',
		( { eventType, key, shouldCall } ) => {
			jest.clearAllMocks(); // reset the 'called' count for all mocks before each test
			handleKeyEvent( [] )( new KeyboardEvent( eventType, { key } ) );
			expect( selectIconAtCurrentCursor ).toBeCalledTimes(
				shouldCall ? 1 : 0
			);
		}
	);

	test( 'ignores other key events', () => {
		jest.clearAllMocks(); // reset the 'called' count for all mocks before each test
		handleKeyEvent( [] )(
			new KeyboardEvent( 'keypress', { key: 'Enter' } )
		);
		expect( selectIconAtCurrentCursor ).toBeCalledTimes( 0 );
	} );

	test( 'ignores if no selection or icon are found', () => {
		jest.clearAllMocks();
		selectIconAtCurrentCursor.mockReturnValue( {
			selection: undefined,
			icon: undefined,
		} );
		handleKeyEvent( [] )(
			new KeyboardEvent( 'keyup', { key: 'ArrowRight' } )
		);
		expect( selectIconAtCurrentCursor ).toBeCalledTimes( 1 );
	} );
} );
