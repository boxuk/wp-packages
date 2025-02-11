import React from 'react';

/* WordPress Dependencies */
import {
	Button,
	__experimentalGrid as Grid, // eslint-disable-line @wordpress/no-unsafe-wp-apis -- experimental package, but we know the risks!
} from '@wordpress/components';

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
		<Grid gap={ 3 } columns={ 8 } style={ { marginTop: '1rem' } }>
			{ filteredIcons.map( ( Icon ) => (
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
			) ) }
		</Grid>
	);
};

export default IconPanel;
