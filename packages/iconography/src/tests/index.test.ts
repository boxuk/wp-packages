import { describe, expect, jest, test } from '@jest/globals';
import { registerFormatType } from '@wordpress/rich-text';

/* Internal Dependencies */
import { selectIconAtCurrentCursor } from '../utils';
import { handleKeyEvent } from '../';

jest.mock( '@wordpress/rich-text', () => ( {
	registerFormatType: jest.fn(),
} ) );

jest.mock( '../IconToolbarButton', () => ( {
	IconToolbarButton: jest.fn(),
} ) );

jest.mock( '../utils', () => ( {
	getIconOptions: jest.fn(),
	selectIconAtCurrentCursor: jest
		.fn()
		.mockReturnValue( { selection: null, icon: null } ),
} ) );

test( 'iconography should register all 3 types', () => {
	// just by having imported the file, the registerFormatType should be called 3 times
	expect( registerFormatType ).toBeCalledTimes( 3 );
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
			handleKeyEvent( new KeyboardEvent( eventType, { key } ) );
			expect( selectIconAtCurrentCursor ).toBeCalledTimes(
				shouldCall ? 1 : 0
			);
		}
	);

	test( 'ignores other key events', () => {
		jest.clearAllMocks(); // reset the 'called' count for all mocks before each test
		handleKeyEvent( new KeyboardEvent( 'keypress', { key: 'Enter' } ) );
		expect( selectIconAtCurrentCursor ).toBeCalledTimes( 0 );
	} );
} );
