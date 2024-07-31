import React from 'react';

import { PanelBody, TextControl, TextareaControl } from '@wordpress/components';
import { __, sprintf } from '@wordpress/i18n';

import type { translationsData } from '../../../data/types';

export const PersonalisationCookieLabels = ( {
	translations,
	setTranslations,
}: {
	translations: NonNullable< translationsData >;
	setTranslations: ( translations: translationsData ) => void;
} ) => (
	<PanelBody
		initialOpen={ false }
		title={ translations.personalisation.title }
	>
		<p>
			{ sprintf(
				// translators: %s is the name of the
				__(
					'This section controls the %s consent key from Google Tag Manager.',
					'boxuk'
				),
				'"personalization_storage"'
			) }
		</p>
		<TextControl
			label={ __( 'Title', 'boxuk' ) }
			value={ translations.personalisation.title }
			onChange={ ( value ) =>
				setTranslations( {
					...translations,
					personalisation: {
						...translations.personalisation,
						title: value,
					},
				} )
			}
		/>
		<TextareaControl
			rows={ 3 }
			label={ __( 'Personalization Storage Label', 'boxuk' ) }
			value={ translations.personalizationStorageLabel }
			onChange={ ( value ) =>
				setTranslations( {
					...translations,
					personalizationStorageLabel: value,
				} )
			}
		/>
		<TextareaControl
			rows={ 10 }
			label={ __( 'Description', 'boxuk' ) }
			value={ translations.personalisation.description }
			onChange={ ( value ) =>
				setTranslations( {
					...translations,
					personalisation: {
						...translations.personalisation,
						description: value,
					},
				} )
			}
		/>
	</PanelBody>
);
