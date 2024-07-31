import React from 'react';

/* WordPress Deps */
import { useSelect, useDispatch } from '@wordpress/data';
import { RichText } from '@wordpress/block-editor';

/* Internal Deps */
import { store } from '../data';

export const ModalPreview = () => {
	const translations = useSelect(
		( select ) => select( store ).getTranslations(),
		[]
	);

	const { setTranslations } = useDispatch( store );

	return (
		<div className="cm-wrapper cc--anim">
			<div
				className="cm cm--box cm--middle cm--center"
				aria-describedby="cm__desc"
				aria-labelledby="cm__title"
				style={ { position: 'relative', transform: 'unset' } }
			>
				<div className="cm__body">
					<div className="cm__texts">
						<RichText
							tagName="h2"
							value={ translations.modal.title }
							className="cm__title"
							onChange={ ( newValue: string ) => {
								setTranslations( {
									...translations,
									modal: {
										...translations.modal,
										title: newValue,
									},
								} );
							} }
						/>
						<RichText
							tagName="p"
							className="cm__desc"
							value={ translations.modal.description }
							onChange={ ( newValue: string ) => {
								setTranslations( {
									...translations,
									modal: {
										...translations.modal,
										description: newValue,
									},
								} );
							} }
						/>
					</div>
					<div className="cm__btns">
						<div className="cm__btn-group">
							<button className="cm__btn">
								<RichText
									tagName="span"
									value={ translations.modal.acceptAllBtn }
									onChange={ ( newValue: string ) => {
										setTranslations( {
											...translations,
											modal: {
												...translations.modal,
												acceptAllBtn: newValue,
											},
										} );
									} }
								/>
							</button>
							<button className="cm__btn">
								<RichText
									tagName="span"
									value={
										translations.modal.acceptNecessaryBtn
									}
									onChange={ ( newValue: string ) => {
										setTranslations( {
											...translations,
											modal: {
												...translations.modal,
												acceptNecessaryBtn: newValue,
											},
										} );
									} }
								/>
							</button>
						</div>
						<div className="cm__btn-group">
							<button className="cm__btn cm__btn--secondary">
								<RichText
									tagName="span"
									value={
										translations.modal.showPreferencesBtn
									}
									onChange={ ( newValue: string ) => {
										setTranslations( {
											...translations,
											modal: {
												...translations.modal,
												showPreferencesBtn: newValue,
											},
										} );
									} }
								/>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	);
};
