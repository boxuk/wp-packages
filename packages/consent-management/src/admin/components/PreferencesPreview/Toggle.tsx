import React from 'react';

/* Internal Deps */
import { ReactComponent as ToggleOffIcon } from '../icons/toggle-off.svg';
import { ReactComponent as ToggleOnIcon } from '../icons/toggle-on.svg';

export const Toggle = ( {
	id,
	disabled = false,
	isChecked = false,
	setIsChecked = () => {},
	className = '',
}: {
	id: string;
	disabled?: boolean;
	isChecked?: boolean;
	setIsChecked?: ( isChecked: boolean ) => void;
	className?: string;
} ) => {
	const fullClassName = 'section__toggle-wrapper ' + className;

	/* eslint-disable jsx-a11y/label-has-associated-control -- The label is associated with the input using the `id` attribute */
	return (
		<label htmlFor={ id } className={ fullClassName }>
			<input
				id={ id }
				type="checkbox"
				className="section__toggle"
				disabled={ disabled }
				checked={ isChecked }
				onChange={ () => setIsChecked( ! isChecked ) }
			/>
			<span className="toggle__icon" aria-hidden="true">
				<span className="toggle__icon-circle">
					<span className="toggle__icon-off">
						<ToggleOffIcon />
					</span>
					<span className="toggle__icon-on">
						<ToggleOnIcon />
					</span>
				</span>
			</span>
		</label>
	);
	/* eslint-enable jsx-a11y/label-has-associated-control */
};
