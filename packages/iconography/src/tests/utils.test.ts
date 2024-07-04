import { describe, expect, jest, test } from '@jest/globals';

import {
	generateRichTextFormat,
	selectIconAtCurrentCursor,
	getIconGroups,
} from '../utils';
import { IconGroup } from '../types';

jest.mock( '@wordpress/rich-text', () => ( {} ) );

jest.mock( '../shared', () => ( {
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
			expect(
				generateRichTextFormat( icon, iconGroup as IconGroup )
			).toEqual( expected );
		}
	);
} );

describe( 'Should select icon at current cursor', () => {
	test( 'should return empty selction and icon if no current selection', () => {
		const spy = jest.spyOn( document, 'getSelection' );
		const { selection, icon } = selectIconAtCurrentCursor( [
			{ className: 'test' } as IconGroup,
		] );
		expect( spy ).toBeCalled();
		expect( selection?.rangeCount ).toBe( 0 );
		expect( icon ).toBe( null );
	} );

	test( 'should return icon if current selection contains icon', () => {
		const spy = jest.spyOn( document, 'getSelection' ).mockReturnValue( {
			anchorNode: {
				parentElement: {
					className: 'test',
				},
			},
			selectAllChildren: jest.fn(),
		} as unknown as Selection );

		const { icon } = selectIconAtCurrentCursor( [
			{ className: 'test' } as IconGroup,
		] );

		expect( spy ).toBeCalled();
		expect( icon ).toStrictEqual( { className: 'test' } );
	} );
} );

describe( 'Get Icon Groups', () => {
	test( 'should return icon groups from window', () => {
		const iconGroups = [
			{
				title: 'Test',
				name: 'test',
				tagName: 'div',
				className: 'test-class',
				icons: [ { label: 'Test', content: 'test' } ],
			},
		];

		window.boxIconography = { iconGroups };

		expect( getIconGroups() ).toStrictEqual( [
			{
				title: 'Test',
				name: 'test',
				tagName: 'div',
				className: 'test-class',
				options: [ { name: 'Test', value: 'test' } ],
				interactive: false,
				edit: expect.any( Function ),
			},
		] );
	} );

	test( 'should only render edit function once', () => {
		const iconGroups = [
			{
				title: 'Test',
				name: 'test',
				tagName: 'div',
				className: 'test-class',
				icons: [ { label: 'Test', content: 'test' } ],
			},
			{
				title: 'Test2',
				name: 'test2',
				tagName: 'div',
				className: 'test-class',
				icons: [ { label: 'Test2', content: 'test2' } ],
			},
		];

		window.boxIconography = { iconGroups };

		const result = getIconGroups();

		expect( result ).toStrictEqual( [
			{
				title: 'Test',
				name: 'test',
				tagName: 'div',
				className: 'test-class',
				options: [ { name: 'Test', value: 'test' } ],
				interactive: false,
				edit: expect.any( Function ),
			},
			{
				title: 'Test2',
				name: 'test2',
				tagName: 'div',
				className: 'test-class',
				options: [ { name: 'Test2', value: 'test2' } ],
				interactive: false,
				edit: expect.any( Function ),
			},
		] );

		if ( result ) {
			expect( result[ 1 ].edit ).not.toBe( result[ 0 ].edit );
			expect( result[ 1 ].edit() ).toBe( undefined );
		} else {
			throw new Error( 'result is undefined' );
		}
	} );
} );
