import React from 'react';

/* WordPress Deps */
import { useSelect, useDispatch } from '@wordpress/data';
import { TextControl, TextareaControl, PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/* Internal Deps */
import { store } from '../../data';

export const ModalSidebar = () => {
	const translations = useSelect(
		( select ) => select( store ).getTranslations(),
		[]
	);
	const { setTranslations } = useDispatch( store );

	return (
		<PanelBody>
			<TextControl
				label={ __( 'Title', 'boxuk' ) }
				value={ translations.modal.title }
				onChange={ ( value ) =>
					setTranslations( {
						...translations,
						modal: { ...translations.modal, title: value },
					} )
				}
			/>
			<TextareaControl
				label={ __( 'Description', 'bouk' ) }
				rows={ 10 }
				value={ translations.modal.description }
				onChange={ ( value ) =>
					setTranslations( {
						...translations,
						modal: { ...translations.modal, description: value },
					} )
				}
			/>

			<TextControl
				label={ __( 'Accept All Button', 'boxuk' ) }
				value={ translations.modal.acceptAllBtn }
				onChange={ ( value ) =>
					setTranslations( {
						...translations,
						modal: { ...translations.modal, acceptAllBtn: value },
					} )
				}
			/>

			<TextControl
				label={ __( 'Accept Necessary Button', 'boxuk' ) }
				value={ translations.modal.acceptNecessaryBtn }
				onChange={ ( value ) =>
					setTranslations( {
						...translations,
						modal: {
							...translations.modal,
							acceptNecessaryBtn: value,
						},
					} )
				}
			/>

			<TextControl
				label={ __( 'Show Preferences Button', 'boxuk' ) }
				value={ translations.modal.showPreferencesBtn }
				onChange={ ( value ) =>
					setTranslations( {
						...translations,
						modal: {
							...translations.modal,
							showPreferencesBtn: value,
						},
					} )
				}
			/>
		</PanelBody>
	);
};
