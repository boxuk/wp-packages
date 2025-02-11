import React from 'react';

/* WordPress Dependencies */
import {
	Button,
	Disabled,
	Spinner,
	__experimentalGrid as Grid, // eslint-disable-line @wordpress/no-unsafe-wp-apis -- experimental package, but we know the risks!
} from '@wordpress/components';

/* Types */
import type { IconGroup } from '../types';
import type { RichTextValue } from '@wordpress/rich-text';

export type IconPanelProps = {
	iconGroup: IconGroup;
	onClick: ( icon: RichTextValue ) => void;
	searchTerm: string;
};

export const PlaceholderIconPanel = () => {
	return (
		<Grid gap={ 3 } columns={ 8 } style={ { marginTop: '1rem' } }>
			{ [ ...Array( 300 ) ].map( ( _: any, index: number ) => (
				<Disabled key={ 'placeholder-icon-grid' + index }>
					<Button style={ { fontSize: '3rem' } }>
						<Spinner />
					</Button>
				</Disabled>
			) ) }
		</Grid>
	);
};
