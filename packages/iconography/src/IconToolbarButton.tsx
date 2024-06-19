import React from 'react';

/* WordPress Dependencies */
import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';
import { ToolbarButton } from '@wordpress/components';

/* Internal Dependencies */
import { ReactComponent as AddReactionOutlined } from './AddReactionOutlined.svg';

/* Types */
import type { RichTextValue } from '@wordpress/rich-text';
import { IconModal } from './IconModal';

export type IconToolbarButtonProps = {
	onChange: ( value: RichTextValue ) => void;
	value: RichTextValue;
};

export const IconToolbarButton = ( {
	onChange,
	value,
}: IconToolbarButtonProps ) => {
	const [ open, setOpen ] = useState( false );

	return (
		<>
			<ToolbarButton
				icon={ <AddReactionOutlined /> }
				label={ __( 'Add an icon', 'boxuk' ) }
				onClick={ () => setOpen( ! open ) }
			/>
			{ open && (
				<IconModal
					onRequestClose={ () => setOpen( false ) }
					onChange={ onChange }
					value={ value }
				/>
			) }
		</>
	);
};
