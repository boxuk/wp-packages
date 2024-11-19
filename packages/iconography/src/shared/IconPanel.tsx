import React from 'react';

/* WordPress Dependencies */
import { __ } from '@wordpress/i18n';
import { Button, Flex, FlexItem } from '@wordpress/components';

/* Internal Dependencies */
import { generateRichTextFormat } from '../utils';

/* Types */
import type { IconGroup } from '../types';
import type { RichTextValue } from '@wordpress/rich-text';

export type IconPanelProps = {
	iconGroup: IconGroup;
	onClick: ( icon: RichTextValue ) => void;
	searchTerm: string;
};

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
		<Flex
			wrap={ true }
			gap={ 3 }
			style={ { marginTop: '1rem', overflowY: 'auto' } }
		>
			{ filteredIcons.map( ( Icon ) => (
				<FlexItem key={ Icon.value }>
					<Button
						key={ Icon.value }
						onClick={ () =>
							onClick( generateRichTextFormat( Icon, iconGroup ) )
						}
						label={ Icon.name }
						style={ { fontSize: '2.5rem' } }
					>
						<span className={ iconGroup.className }>
							{ Icon.value }
						</span>
					</Button>
				</FlexItem>
			) ) }
		</Flex>
	);
};

export default IconPanel;
