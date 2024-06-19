import React, { Suspense, lazy } from 'react';

/* WordPress Dependencies */
import { __ } from '@wordpress/i18n';
import { Modal, TabPanel, SearchControl } from '@wordpress/components';
import { insert } from '@wordpress/rich-text';
import { useState, useMemo } from '@wordpress/element';

/* Internal Dependencies */
import { PlaceholderIconPanel } from './PlaceholderIconPanel';
import { getIconGroups } from './utils';

/* Types */
import type { RichTextValue } from '@wordpress/rich-text';

const IconPanel = lazy( () => import( './IconPanel' ) );

export const IconModal = ( {
	onChange,
	value,
	onRequestClose,
}: {
	onChange: ( value: RichTextValue ) => void;
	value: RichTextValue;
	onRequestClose: () => void;
} ) => {
	const [ searchTerm, setSearchTerm ] = useState( '' );
	const iconGroups = useMemo( () => getIconGroups( false ), [] );

	return (
		<Modal
			size="small"
			onRequestClose={ onRequestClose }
			title={ __( 'Select an icon', 'boxuk' ) }
		>
			<SearchControl
				label={ __( 'Search' ) }
				value={ searchTerm }
				onChange={ setSearchTerm }
			/>
			<TabPanel
				tabs={
					iconGroups?.map( ( iconGroup ) => ( {
						title: iconGroup.title,
						name: iconGroup.name,
						iconGroup,
					} ) ) ?? []
				}
			>
				{ ( tab ) => (
					<Suspense fallback={ <PlaceholderIconPanel /> }>
						<IconPanel
							iconGroup={ tab.iconGroup }
							searchTerm={ searchTerm }
							onClick={ ( richText ) => {
								onChange(
									insert(
										value,
										richText,
										value.start,
										value.end
									)
								);
								onRequestClose();
							} }
						/>
					</Suspense>
				) }
			</TabPanel>
		</Modal>
	);
};
