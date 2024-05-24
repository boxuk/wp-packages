import { describe, expect, jest, test } from '@jest/globals';

import { generateRichTextFormat, getIconOptions, snakeCase } from '../utils';

jest.mock( '@wordpress/rich-text', () => ( {} ) );
jest.mock( '../index', () => ( {
	iconGroups: [],
} ) );

jest.mock( '../IconToolbarButton', () => ( {
	IconToolbarButton: jest.fn(),
} ) );

describe( 'Should generate a rich-text format from icon', () => {
	const dataProvider = [
		{
			icon: {
				name: 'ZoomOutOutlined',
				value: 'zoom_out',
			},
			iconGroup: {
				name: 'content-only/ms-outlined',
			},
			expected: {
				text: 'zoom_out',
				formats: new Array( 8 ).fill( [
					{ type: 'content-only/ms-outlined' },
				] ) as { type: 'content-only/ms-outlined' }[],
				start: 0,
				end: 8,
				replacements: [],
			},
		},
		{
			icon: {
				name: 'SomethingElseOutlined',
				value: 'something_else',
			},
			iconGroup: {
				name: 'content-only/ms-outlined',
			},
			expected: {
				text: 'something_else',
				formats: new Array( 14 ).fill( [
					{ type: 'content-only/ms-outlined' },
				] ) as { type: 'content-only/ms-outlined' }[],
				start: 0,
				end: 14,
				replacements: [],
			},
		},
	];

	test.each( dataProvider )(
		'should generate the correct rich-text format for $icon.name',
		( { icon, iconGroup, expected } ) => {
			expect( generateRichTextFormat( icon, iconGroup ) ).toEqual(
				expected
			);
		}
	);
} );

describe( 'Get icon options', () => {
	const dataProvider = [
		{ suffix: 'Outlined', expected: 2017 },
		{ suffix: 'Rounded', expected: 2017 },
		{ suffix: 'Sharp', expected: 2014 },
	];

	test.each( dataProvider )(
		'should return the correct icon count for $suffix',
		( { suffix, expected } ) => {
			expect( getIconOptions( suffix ).length ).toEqual( expected );
		}
	);
} );

describe( 'snake case', () => {
	const dataProvider = [
		{ input: 'thisIsNotSnakeCase', expected: 'this_is_not_snake_case' },
		{ input: 'this1hasNumbers', expected: 'this_1has_numbers' },
		{ input: 'this1HasNumbers', expected: 'this_1_has_numbers' },
		{ input: 'this-also-has-dashes', expected: 'this_also_has_dashes' },
		{ input: 'this has spaces', expected: 'this_has_spaces' },
	];

	test.each( dataProvider )(
		'should convert $input to $expected',
		( { input, expected } ) => {
			expect( snakeCase( input ) ).toEqual( expected );
		}
	);
} );
