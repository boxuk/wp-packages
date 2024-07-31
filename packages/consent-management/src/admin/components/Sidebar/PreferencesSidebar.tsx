import React from 'react';

/* WordPress Deps */
import { useSelect, useDispatch } from '@wordpress/data';
import { TextControl, TextareaControl, PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/* Internal Deps */
import { store } from '../../data';
import { FunctionalCookieLabels } from './Preferences/FunctionalCookieLabels';
import { AnalyticsCookieLabels } from './Preferences/AnalyticsCookieLabels';
import { PersonalisationCookieLabels } from './Preferences/PersonalisationCookieLabels';
import { AdvertisingCookieLabels } from './Preferences/AdvertisingCookieLabels';
import { ButtonsLabels } from './Preferences/ButtonsLabels';

export const PreferencesSidebar = () => {
	const translations = useSelect(
		( select ) => select( store ).getTranslations(),
		[]
	);
	const { setTranslations } = useDispatch( store );

	return (
		<>
			<PanelBody>
				<TextControl
					label={ __( 'Popup Title', 'boxuk' ) }
					value={ translations.preferences.title }
					onChange={ ( value ) =>
						setTranslations( {
							...translations,
							preferences: {
								...translations.preferences,
								title: value,
							},
						} )
					}
				/>
			</PanelBody>
			<PanelBody initialOpen={ false } title={ 'Introduction' }>
				<TextControl
					label={ __( 'Title', 'boxuk' ) }
					value={ translations.main.title }
					onChange={ ( value ) =>
						setTranslations( {
							...translations,
							main: { ...translations.main, title: value },
						} )
					}
				/>
				<TextareaControl
					rows={ 10 }
					label={ __( 'Description', 'bouk' ) }
					value={ translations.main.description }
					onChange={ ( value ) =>
						setTranslations( {
							...translations,
							main: { ...translations.main, description: value },
						} )
					}
				/>
			</PanelBody>
			<FunctionalCookieLabels
				translations={ translations }
				setTranslations={ setTranslations }
			/>
			<AnalyticsCookieLabels
				translations={ translations }
				setTranslations={ setTranslations }
			/>
			<PersonalisationCookieLabels
				translations={ translations }
				setTranslations={ setTranslations }
			/>
			<AdvertisingCookieLabels
				translations={ translations }
				setTranslations={ setTranslations }
			/>

			<ButtonsLabels
				translations={ translations }
				setTranslations={ setTranslations }
			/>
		</>
	);
};
