import React from 'react';
import { describe, expect, jest, test } from '@jest/globals';
import { render, screen } from '@testing-library/react';
import { generateRichTextFormat } from '../utils';

import { IconPanel } from '../IconPanel';

jest.mock( '../utils', () => ( {
	generateRichTextFormat: jest.fn(),
} ) );

const iconGroup = {
	title: 'Filled',
	name: 'content-only/ms-filled',
	className: 'material-symbols-filled',
	tagName: 'span',
	edit: () => {},
	options: [
		{
			title: 'Accessibility',
			name: 'accessibility',
			value: 'accessibility',
			interactive: false,
		},
		{
			title: 'Add',
			name: 'add',
			value: 'add',
			interactive: false,
		},
	],
	interactive: false,
};

describe( 'IconPanel', () => {
	test( 'rendered IconPanel matches snapshot', () => {
		expect(
			render(
				<IconPanel
					iconGroup={ iconGroup }
					onClick={ jest.fn() }
					searchTerm={ '' }
				/>
			)
		).toMatchSnapshot();
	} );

	test( 'search term filters icons', () => {
		render(
			<IconPanel
				iconGroup={ iconGroup }
				onClick={ jest.fn() }
				searchTerm={ 'add' }
			/>
		);

		expect( screen.queryByText( 'accessibility' ) ).toBe( null );
		expect( screen.queryByText( 'add' ) ).not.toBe( null );
	} );

	test( 'clicking icon triggers onClick', () => {
		const onClick = jest.fn();
		render(
			<IconPanel
				iconGroup={ iconGroup }
				onClick={ onClick }
				searchTerm={ '' }
			/>
		);
		const iconButton = screen.getByText( 'accessibility' );

		generateRichTextFormat.mockReturnValue( {
			type: 'content-only/ms-filled',
			attributes: { value: 'accessibility' },
			text: 'accessibility',
		} );

		iconButton.click();

		expect( generateRichTextFormat ).toHaveBeenCalledWith(
			{
				title: 'Accessibility',
				name: 'accessibility',
				value: 'accessibility',
				interactive: false,
			},
			iconGroup
		);
		expect( onClick ).toHaveBeenCalledWith( {
			type: 'content-only/ms-filled',
			attributes: { value: 'accessibility' },
			text: 'accessibility',
		} );
	} );
} );
