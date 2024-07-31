import React from 'react';

/* WordPress Deps */
import { useSelect, useDispatch } from '@wordpress/data';
import { TextControl, PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/* Internal Deps */
import { store } from '../../data';

export const TagConfigSidebar = () => {
	const data = useSelect( ( select ) => select( store ).getTagData(), [] );
	const { setGtmConfig } = useDispatch( store );

	return (
		<PanelBody>
			<TextControl
				label={ __( 'Tag ID', 'boxuk' ) }
				placeholder="GTM-XXXXXXX"
				value={ data.id }
				onChange={ ( value ) =>
					setGtmConfig( {
						...data,
						id: value,
					} )
				}
				required
			/>
			<TextControl
				label={ __( 'Authorisation (optional)', 'boxuk' ) }
				value={ data.auth }
				placeholder=""
				onChange={ ( value ) =>
					setGtmConfig( {
						...data,
						auth: value,
					} )
				}
			/>
			<TextControl
				label={ __( 'Preview ID (optional)', 'boxuk' ) }
				value={ data.preview }
				placeholder="env-123"
				onChange={ ( value ) =>
					setGtmConfig( {
						...data,
						preview: value,
					} )
				}
			/>
		</PanelBody>
	);
};
