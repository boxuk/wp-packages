import React, { ComponentProps } from 'react';

/* WordPress Dependencies */
import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';
import { BlockControls } from '@wordpress/block-editor';
import { ToolbarButton } from '@wordpress/components';

/* Internal Dependencies */
import { ReactComponent as Icon } from '../Icon.svg';
import { IconModal } from './IconModal';

/* Types */
import type { RichTextValue } from '@wordpress/rich-text';

export type IconToolbarButtonProps = {
	onChange: ( value: RichTextValue ) => void;
	value: RichTextValue;
	icon: ComponentProps< typeof ToolbarButton >[ 'icon' ];
	initialOpen?: boolean;
};

export const IconToolbarButton = ( {
	onChange,
	value,
	icon = <Icon />,
	initialOpen = false,
}: IconToolbarButtonProps ) => {
	const [ open, setOpen ] = useState( initialOpen );

	return (
		<BlockControls group="inline">
			<ToolbarButton
				icon={ icon }
				label={ __( 'Select icon', 'boxuk' ) }
				onClick={ () => setOpen( ! open ) }
			/>
			{ open && (
				<IconModal
					onRequestClose={ () => setOpen( false ) }
					onChange={ onChange }
					value={ value }
				/>
			) }
		</BlockControls>
	);
};
