import React from 'react';

import { PanelBody, TextControl, TextareaControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import type { translationsData } from '../../../data/types';

export const AdvertisingCookieLabels = ( {
	translations,
	setTranslations,
}: {
	translations: NonNullable< translationsData >;
	setTranslations: ( translations: translationsDataw ) => void;
} ) => (
	<PanelBody initialOpen={ false } title={ translations.advertising.title }>
		<TextControl
			label={ __( 'Title', 'boxuk' ) }
			value={ translations.advertising.title }
			onChange={ ( value ) =>
				setTranslations( {
					...translations,
					advertising: {
						...translations.advertising,
						title: value,
					},
				} )
			}
		/>
		<TextareaControl
			rows={ 3 }
			label={ __( 'Advertising Storage Label', 'boxuk' ) }
			value={ translations.adStorageLabel }
			onChange={ ( value ) =>
				setTranslations( {
					...translations,
					adStorageLabel: value,
				} )
			}
		/>
		<TextareaControl
			rows={ 3 }
			label={ __( 'Advertising User Data Label', 'boxuk' ) }
			value={ translations.adUserDataLabel }
			onChange={ ( value ) =>
				setTranslations( {
					...translations,
					adUserDataLabel: value,
				} )
			}
		/>
		<TextareaControl
			rows={ 3 }
			label={ __( 'Advertising Personalization Label', 'boxuk' ) }
			value={ translations.adPersonalizationLabel }
			onChange={ ( value ) =>
				setTranslations( {
					...translations,
					adPersonalizationLabel: value,
				} )
			}
		/>

		<TextareaControl
			rows={ 10 }
			label={ __( 'Description', 'boxuk' ) }
			value={ translations.advertising.description }
			onChange={ ( value ) =>
				setTranslations( {
					...translations,
					advertising: {
						...translations.advertising,
						description: value,
					},
				} )
			}
		/>
	</PanelBody>
);
