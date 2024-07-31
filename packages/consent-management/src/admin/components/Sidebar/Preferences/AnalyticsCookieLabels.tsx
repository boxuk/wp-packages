import React from 'react';

import { PanelBody, TextControl, TextareaControl } from '@wordpress/components';
import { __, sprintf } from '@wordpress/i18n';

import type { translationsData } from '../../../data/types';

export const AnalyticsCookieLabels = ( {
	translations,
	setTranslations,
}: {
	translations: NonNullable< translationsData >;
	setTranslations: ( translations: translationsData ) => void;
} ) => (
	<PanelBody initialOpen={ false } title={ translations.analytics.title }>
		<p>
			{ sprintf(
				// translators: %s is the name of the consent key
				__(
					'This section controls the %s consent key from Google Tag Manager',
					'boxuk'
				),
				'"analytics_storage"'
			) }
		</p>
		<TextControl
			label={ __( 'Title', 'boxuk' ) }
			value={ translations.analytics.title }
			onChange={ ( value ) =>
				setTranslations( {
					...translations,
					analytics: {
						...translations.analytics,
						title: value,
					},
				} )
			}
		/>
		<TextareaControl
			rows={ 3 }
			label={ __( 'Analytics Storage Label', 'boxuk' ) }
			value={ translations.analyticsStorageLabel }
			onChange={ ( value ) =>
				setTranslations( {
					...translations,
					analyticsStorageLabel: value,
				} )
			}
		/>
		<TextareaControl
			rows={ 10 }
			label={ __( 'Description', 'boxuk' ) }
			value={ translations.analytics.description }
			onChange={ ( value ) =>
				setTranslations( {
					...translations,
					analytics: {
						...translations.analytics,
						description: value,
					},
				} )
			}
		/>
	</PanelBody>
);
