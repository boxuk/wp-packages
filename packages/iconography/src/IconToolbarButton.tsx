import React from 'react';

/* WordPress Dependencies */
import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';
import { BlockControls } from '@wordpress/block-editor';
import {
	ToolbarGroup,
	Modal,
	ToolbarButton,
	TabPanel,
	SearchControl,
} from '@wordpress/components';
import { insert } from '@wordpress/rich-text';

/* Internal Dependencies */
import { IconPanel } from './IconPanel';
import { iconGroups } from './index';
import { ReactComponent as AddReactionOutlined } from './AddReactionOutlined.svg';

/* Types */
import type { IconGroup, IconToolbarButtonProps } from './types';
import type { RichTextValue } from '@wordpress/rich-text';

export const IconToolbarButton = ( {
	onChange,
	value,
	initialOpenState = false,
}: IconToolbarButtonProps ) => {
	const [ open, setOpen ] = useState( initialOpenState );
	const [ searchTerm, setSearchTerm ] = useState( '' );

	return (
		<BlockControls controls={ [] }>
			<ToolbarGroup>
				<ToolbarButton
					icon={ <AddReactionOutlined /> }
					label={ __( 'Add an icon', 'okdo' ) }
					onClick={ () => setOpen( ! open ) }
					placeholder={ __( 'Add an icon', 'okdo' ) }
				/>
				{ open && (
					<Modal
						isFullScreen={ true }
						onRequestClose={ () => setOpen( false ) }
						title={ __( 'Select an icon', 'okdo' ) }
					>
						<SearchControl
							label={ __( 'Search' ) }
							value={ searchTerm }
							onChange={ ( newValue ) =>
								setSearchTerm( newValue )
							}
						/>
						<TabPanel
							tabs={ iconGroups.map( ( iconGroup ) => {
								return {
									title: iconGroup.title,
									name: iconGroup.name,
									iconGroup,
								};
							} ) }
							children={ ( tab ) => {
								const iconGroup: IconGroup = tab.iconGroup;
								return (
									<IconPanel
										iconGroup={ iconGroup }
										searchTerm={ searchTerm }
										onClick={ (
											richText: RichTextValue
										) => {
											onChange(
												insert(
													value,
													richText,
													value.start,
													value.end
												)
											);
											setOpen( false );
										} }
									/>
								);
							} }
						/>
					</Modal>
				) }
			</ToolbarGroup>
		</BlockControls>
	);
};
