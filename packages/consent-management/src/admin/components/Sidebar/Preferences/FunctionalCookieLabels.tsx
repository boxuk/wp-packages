import React from 'react';

import { PanelBody, TextControl, TextareaControl } from '@wordpress/components';
import { __, sprintf } from '@wordpress/i18n';

import type { translationsData } from '../../../data/types';

export const FunctionalCookieLabels = ( {
	translations,
	setTranslations,
}: {
	translations: NonNullable< translationsData >;
	setTranslations: ( translations: translationsData ) => void;
} ) => (
	<PanelBody initialOpen={ false } title={ translations.necessary.title }>
		<p>
			{ sprintf(
				// translators: %1$s and %2$s are the name of the consent keys
				__(
					'This section controls the %1$s and %1$s consent keys from Google Tag Manager. These settings are enforced to be enabled for all users.',
					'boxuk'
				),
				'"functional_storage"',
				'"security_storage"'
			) }
		</p>
		<TextControl
			label={ __( 'Title', 'boxuk' ) }
			value={ translations.necessary.title }
			onChange={ ( value ) =>
				setTranslations( {
					...translations,
					necessary: {
						...translations.necessary,
						title: value,
					},
				} )
			}
		/>
		<TextareaControl
			rows={ 3 }
			label={ __( 'Functionality Storage Label', 'boxuk' ) }
			value={ translations.functionalityStorageLabel }
			onChange={ ( value ) =>
				setTranslations( {
					...translations,
					functionalityStorageLabel: value,
				} )
			}
		/>
		<TextareaControl
			rows={ 3 }
			label={ __( 'Security Storage Label', 'boxuk' ) }
			value={ translations.securityStorageLabel }
			onChange={ ( value ) =>
				setTranslations( {
					...translations,
					securityStorageLabel: value,
				} )
			}
		/>
		<TextareaControl
			rows={ 10 }
			label={ __( 'Description', 'boxuk' ) }
			value={ translations.necessary.description }
			onChange={ ( value ) =>
				setTranslations( {
					...translations,
					necessary: {
						...translations.necessary,
						description: value,
					},
				} )
			}
		/>
	</PanelBody>
);
