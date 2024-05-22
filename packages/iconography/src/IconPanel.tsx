import React from 'react';

/* WordPress Dependencies */
import { __ } from '@wordpress/i18n';
import { Flex, Button } from '@wordpress/components';

/* Internal Dependencies */
import { generateRichTextFormat } from './utils';

/* Types */
import type { IconPanelProps } from './types';

export const IconPanel = ( {
	iconGroup,
	onClick,
	searchTerm,
}: IconPanelProps ) => {
	// Filter the icons based on the search term (if there is one)
	const filteredIcons =
		searchTerm.length > 0
			? iconGroup.options.filter( ( icon ) => {
					return icon.name
						.toLowerCase()
						.includes( searchTerm.toLowerCase() );
			  } )
			: iconGroup.options;

	return (
		<>
			<Flex
				gap={ 3 }
				wrap={ true }
				direction={ 'row' }
				justify={ 'start' }
				style={ { marginTop: '2rem' } }
			>
				{ filteredIcons.map( ( Icon ) => (
					<Button
						key={ Icon.value }
						onClick={ () => {
							const richText = generateRichTextFormat(
								Icon,
								iconGroup
							);
							onClick( richText );
						} }
						style={ { fontSize: '1.5rem' } }
						// icon={ <Icon.svg /> }
						label={ Icon.name }
					>
						<span className={ iconGroup.className ?? '' }>
							{ Icon.value }
						</span>
					</Button>
				) ) }
			</Flex>
		</>
	);
};
