import React from 'react';

/* WordPress Deps */
import { useSelect, useDispatch } from '@wordpress/data';
import { RichText } from '@wordpress/block-editor';

/* Internal Deps */
import { store } from '../../data';
import { Section } from './Section';

export const PreferencesPreview = () => {
	const translations = useSelect(
		( select ) => select( store ).getTranslations(),
		[]
	);

	const { setTranslations } = useDispatch( store );

	return (
		<div className="pm-wrapper cc--anim">
			<div
				className="pm pm--box pm--flip"
				style={ { position: 'relative', transform: 'unset' } }
			>
				<div className="pm__header">
					<RichText
						tagName="h2"
						className="pm__title"
						placeholder="Modal Title"
						value={ translations.preferences.title }
						onChange={ ( newValue: string ) => {
							setTranslations( {
								...translations,
								preferences: {
									...translations.preferences,
									title: newValue,
								},
							} );
						} }
						allowedFormats={ [ 'core/link' ] }
					/>
				</div>

				<div className="pm__body">
					<div className="pm__section">
						<div className="pm__section-title-wrapper">
							<RichText
								className="pm__section-title"
								placeholder="Introduction Title"
								role="heading"
								aria-level={ 3 }
								value={ translations.main.title }
								onChange={ ( newValue: string ) => {
									setTranslations( {
										...translations,
										main: {
											...translations.main,
											title: newValue,
										},
									} );
								} }
							/>
						</div>
						<div className="pm__section-desc-wrapper">
							<RichText
								tagName="p"
								className="pm__section-desc"
								placeholder="Main description"
								value={ translations.main.description }
								onChange={ ( newValue: string ) => {
									setTranslations( {
										...translations,
										main: {
											...translations.main,
											description: newValue,
										},
									} );
								} }
							/>
						</div>
					</div>

					<div className="pm__section-toggles">
						<Section
							title={ translations.necessary.title }
							titlePlaceholder="Necessary"
							setTitle={ ( newValue: string ) => {
								setTranslations( {
									...translations,
									necessary: {
										...translations.necessary,
										title: newValue,
									},
								} );
							} }
							description={ translations.necessary.description }
							descriptionPlaceholder="Necessary description"
							setDescription={ ( newValue: string ) => {
								setTranslations( {
									...translations,
									necessary: {
										...translations.necessary,
										description: newValue,
									},
								} );
							} }
							disabled={ true }
							checked={ true }
							services={ [
								{
									value: translations.functionalityStorageLabel,
									placeholder: 'Functionality Storage',
									setValue: ( newValue: string ) => {
										setTranslations( {
											...translations,
											functionalityStorageLabel: newValue,
										} );
									},
								},
								{
									value: translations.securityStorageLabel,
									placeholder: 'Security Storage',
									setValue: ( newValue: string ) => {
										setTranslations( {
											...translations,
											securityStorageLabel: newValue,
										} );
									},
								},
							] }
						/>

						<Section
							title={ translations.analytics.title }
							titlePlaceholder="Analytics"
							setTitle={ ( newValue: string ) => {
								setTranslations( {
									...translations,
									analytics: {
										...translations.analytics,
										title: newValue,
									},
								} );
							} }
							description={ translations.analytics.description }
							descriptionPlaceholder="Analytics description"
							setDescription={ ( newValue: string ) => {
								setTranslations( {
									...translations,
									analytics: {
										...translations.analytics,
										description: newValue,
									},
								} );
							} }
							services={ [
								{
									value: translations.analyticsStorageLabel,
									placeholder: 'Analytics Storage',
									setValue: ( newValue: string ) => {
										setTranslations( {
											...translations,
											analyticsStorageLabel: newValue,
										} );
									},
								},
							] }
						/>

						<Section
							title={ translations.personalisation.title }
							titlePlaceholder="Personalisation"
							setTitle={ ( newValue: string ) => {
								setTranslations( {
									...translations,
									personalisation: {
										...translations.personalisation,
										title: newValue,
									},
								} );
							} }
							description={
								translations.personalisation.description
							}
							descriptionPlaceholder="Personalisation description"
							setDescription={ ( newValue: string ) => {
								setTranslations( {
									...translations,
									personalisation: {
										...translations.personalisation,
										description: newValue,
									},
								} );
							} }
							services={ [
								{
									value: translations.personalizationStorageLabel,
									placeholder: 'Personalization Storage',
									setValue: ( newValue: string ) => {
										setTranslations( {
											...translations,
											personalizationStorageLabel:
												newValue,
										} );
									},
								},
							] }
						/>

						<Section
							title={ translations.advertising.title }
							titlePlaceholder="Advertising"
							setTitle={ ( newValue: string ) => {
								setTranslations( {
									...translations,
									advertising: {
										...translations.advertising,
										title: newValue,
									},
								} );
							} }
							description={ translations.advertising.description }
							descriptionPlaceholder="Advertising description"
							setDescription={ ( newValue: string ) => {
								setTranslations( {
									...translations,
									advertising: {
										...translations.advertising,
										description: newValue,
									},
								} );
							} }
							services={ [
								{
									value: translations.adStorageLabel,
									placeholder: 'Ad Storage',
									setValue: ( newValue: string ) => {
										setTranslations( {
											...translations,
											adStorageLabel: newValue,
										} );
									},
								},
								{
									value: translations.adUserDataLabel,
									placeholder: 'Ad User Data',
									setValue: ( newValue: string ) => {
										setTranslations( {
											...translations,
											adUserDataLabel: newValue,
										} );
									},
								},
								{
									value: translations.adPersonalizationLabel,
									placeholder: 'Ad Personalization',
									setValue: ( newValue: string ) => {
										setTranslations( {
											...translations,
											adPersonalizationLabel: newValue,
										} );
									},
								},
							] }
						/>
					</div>
				</div>

				<div className="pm__footer">
					<div className="pm__btn-group">
						<RichText
							tagName="button"
							className="pm__btn"
							value={ translations.preferences.acceptAllBtn }
							onChange={ ( newValue: string ) => {
								setTranslations( {
									...translations,
									preferences: {
										...translations.preferences,
										acceptAllBtn: newValue,
									},
								} );
							} }
						/>
						<RichText
							tagName="button"
							value={
								translations.preferences.acceptNecessaryBtn
							}
							className="pm__btn pm__btn--secondary"
							onChange={ ( newValue: string ) => {
								setTranslations( {
									...translations,
									preferences: {
										...translations.preferences,
										acceptNecessaryBtn: newValue,
									},
								} );
							} }
						/>
					</div>
					<div className="pm__btn-group">
						<RichText
							tagName="button"
							className="pm__btn pm__btn--secondary"
							value={
								translations.preferences.savePreferencesBtn
							}
							onChange={ ( newValue: string ) => {
								setTranslations( {
									...translations,
									preferences: {
										...translations.preferences,
										savePreferencesBtn: newValue,
									},
								} );
							} }
						/>
					</div>
				</div>
			</div>
		</div>
	);
};
