import React from 'react';

/* WordPress Deps */
import { useState } from '@wordpress/element';
import { RichText } from '@wordpress/block-editor';

/* Internal Deps */
import { ReactComponent as ArrowIcon } from '../icons/arrow.svg';
import { Service } from './Service';
import { Toggle } from './Toggle';

export const Section = ( {
	title,
	setTitle,
	titlePlaceholder,
	description,
	setDescription,
	descriptionPlaceholder,
	disabled = false,
	checked = false,
	services = [],
}: {
	title: string;
	setTitle: ( newValue: string ) => void;
	titlePlaceholder: string;
	description: string;
	setDescription: ( newValue: string ) => void;
	descriptionPlaceholder: string;
	disabled?: boolean;
	checked?: boolean;
	services?: {
		value: string;
		setValue: ( newValue: string ) => void;
		placeholder: string;
	}[];
} ) => {
	const [ isExpanded, setIsExpanded ] = useState( false );
	const [ isChecked, setIsChecked ] = useState( checked );

	const id = title.replace( /\s/g, '-' );
	const classNames = [
		'pm__section--toggle',
		'pm__section--expandable',
		isExpanded ? 'is-expanded' : '',
	];

	return (
		<div className={ classNames.join( ' ' ) }>
			<div className="pm__section-title-wrapper">
				<RichText
					tagName="button"
					className="pm__section-title"
					value={ title }
					placeholder={ titlePlaceholder }
					onChange={ setTitle }
					onClick={ () => setIsExpanded( ! isExpanded ) }
					aria-expanded={ isExpanded }
					aria-controls={ 'section-' + id }
				/>
				<span className="pm__section-arrow">
					<ArrowIcon />
				</span>
				<Toggle
					id={ id }
					disabled={ disabled }
					isChecked={ isChecked }
					setIsChecked={ setIsChecked }
				/>
			</div>
			<div
				className="pm__section-desc-wrapper"
				aria-hidden={ ! isExpanded }
				id={ 'section-' + id }
			>
				<div className="pm__section-services">
					{ services.map( ( service, index ) => (
						<Service
							key={ index }
							value={ service.value }
							placeholder={ service.placeholder }
							setValue={ service.setValue }
							disabled={ disabled }
							checked={ isChecked }
							setIsChecked={ setIsChecked }
						/>
					) ) }
				</div>
				<RichText
					tagName="p"
					className="pm__section-desc"
					value={ description }
					placeholder={ descriptionPlaceholder }
					onChange={ setDescription }
				/>
			</div>
		</div>
	);
};
