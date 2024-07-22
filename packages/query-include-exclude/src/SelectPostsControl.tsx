import React, { type ComponentProps } from 'react';

import { FormTokenField } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { sprintf } from '@wordpress/i18n';
import { store as coreStore, type Post } from '@wordpress/core-data';

type FormTokenFieldOnChange = ComponentProps<
	typeof FormTokenField
>[ 'onChange' ];

export const SelectPostsControl = ( {
	onChange,
	label,
	selected,
	postType,
}: {
	onChange: ( selected: number[] ) => void;
	label?: string;
	selected?: number[];
	postType?: string;
} ) => {
	const allPosts =
		useSelect(
			( select ) =>
				select( coreStore ).getEntityRecords< Post >(
					'postType',
					postType ?? 'post',
					{
						per_page: -1,
						_fields: [ 'id', 'title' ],
						context: 'edit',
					}
				),
			[ postType ]
		) ?? [];

	const selectedPosts = allPosts.filter(
		( post ) => selected?.includes( post.id ?? 0 )
	);

	const handleChange: FormTokenFieldOnChange = ( tokens ) =>
		onChange(
			tokens
				.map( ( token ) => {
					if ( 'string' === typeof token ) {
						const values = idAndTitleFromToken( token );
						if ( values && ! isNaN( values.id ) ) {
							return values.id;
						}
					}
					return false;
				} )
				.filter( ( id ) => id !== false )
		);

	const postToTitleWithId = ( post: Post ) =>
		sprintf( '%1$s (ID: %2$d)', post.title.rendered, post.id );

	const idAndTitleFromToken = ( token: string ) => {
		const matches = token.match( /(.+)\(ID: (\d+)\)/ );
		if ( matches ) {
			return {
				id: parseInt( matches[ 2 ], 10 ),
				title: matches[ 1 ].trim(),
			};
		}

		return false;
	};

	return (
		<FormTokenField
			label={ label }
			value={ selectedPosts.map( postToTitleWithId ) }
			suggestions={ allPosts.map( postToTitleWithId ) }
			onChange={ handleChange }
			tokenizeOnBlur={ false }
		/>
	);
};
