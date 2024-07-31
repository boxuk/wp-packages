import React from 'react';

import { PanelBody, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import type { translationsData } from '../../../data/types';

export const ButtonsLabels = ( {
	translations,
	setTranslations,
}: {
	translations: NonNullable< translationsData >;
	setTranslations: ( translations: translationsData ) => void;
} ) => (
	<PanelBody initialOpen={ false } title={ 'Buttons' }>
		<TextControl
			label={ __( 'Accept All', 'boxuk' ) }
			value={ translations.preferences.acceptAllBtn }
			onChange={ ( value ) =>
				setTranslations( {
					...translations,
					preferences: {
						...translations.preferences,
						acceptAllBtn: value,
					},
				} )
			}
		/>
		<TextControl
			label={ __( 'Accept Necessary', 'boxuk' ) }
			value={ translations.preferences.acceptNecessaryBtn }
			onChange={ ( value ) =>
				setTranslations( {
					...translations,
					preferences: {
						...translations.preferences,
						acceptNecessaryBtn: value,
					},
				} )
			}
		/>
		<TextControl
			label={ __( 'Save Preferences', 'boxuk' ) }
			value={ translations.preferences.savePreferencesBtn }
			onChange={ ( value ) =>
				setTranslations( {
					...translations,
					preferences: {
						...translations.preferences,
						savePreferencesBtn: value,
					},
				} )
			}
		/>
	</PanelBody>
);
