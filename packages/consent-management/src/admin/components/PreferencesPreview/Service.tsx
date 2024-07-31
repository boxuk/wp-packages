import React from 'react';

/* WordPress Deps */
import { RichText } from '@wordpress/block-editor';

/* Internal Deps */
import { Toggle } from './Toggle';

export const Service = ( {
	value,
	placeholder,
	setValue,
	disabled,
	checked,
	setIsChecked,
}: {
	value: string;
	placeholder: string;
	setValue: ( newValue: string ) => void;
	disabled: boolean;
	checked: boolean;
	setIsChecked: ( isChecked: boolean ) => void;
} ) => {
	const id = value.replace( /\s/g, '-' );

	return (
		<div className="pm__service">
			<div className="pm__service-header">
				<div className="pm__service-icon"></div>
				<RichText
					tagName="div"
					className="pm__service-title"
					value={ value }
					placeholder={ placeholder }
					onChange={ setValue }
				/>
			</div>
			<Toggle
				id={ id }
				className="toggle-service"
				disabled={ disabled }
				isChecked={ checked }
				setIsChecked={ setIsChecked }
			/>
		</div>
	);
};
