import React, { useState } from 'react';

import { useDispatch } from '@wordpress/data';
import { Flex, Button, Spinner } from '@wordpress/components';
import { drawerRight, check } from '@wordpress/icons';
import { __ } from '@wordpress/i18n';

import { ReactComponent as Icon } from './icons/icon.svg';

import { store } from '../data';

export const Header = ( {
	showSidebar,
	setShowSidebar,
	hasFinishedResolution = true,
}: {
	showSidebar: boolean;
	setShowSidebar: ( show: boolean ) => void;
	hasFinishedResolution?: boolean;
} ) => {
	const [ isSaving, setIsSaving ] = useState( false );
	const [ hasJustSaved, setHasJustSaved ] = useState( false );

	const { save } = useDispatch( store );

	const onSave = () => {
		setIsSaving( true );
		save().then( () => {
			setIsSaving( false );
			setHasJustSaved( true );
			setTimeout( () => setHasJustSaved( false ), 3000 );
		} );
	};

	const buttonIcon = () => {
		if ( hasJustSaved ) {
			return check;
		}
		if ( isSaving ) {
			return <Spinner />;
		}
		return null;
	};
	const buttonText = () => {
		if ( hasJustSaved ) {
			return __( 'Saved', 'boxuk' );
		}
		if ( isSaving ) {
			return __( 'Saving', 'boxuk' );
		}
		return __( 'Save', 'boxuk' );
	};

	return (
		<div className="editor-header">
			<div>
				<Icon style={ { padding: '1em' } } />
			</div>
			<h2>{ __( 'Cookie Consent', 'boxuk' ) }</h2>
			<Flex justify="end">
				{ hasFinishedResolution && (
					<Button
						variant="primary"
						icon={ buttonIcon() }
						onClick={ onSave }
						isBusy={ isSaving }
					>
						{ buttonText() }
					</Button>
				) }
			</Flex>
			<div>
				<Button
					onClick={ () => setShowSidebar( ! showSidebar ) }
					icon={ drawerRight }
					label={
						showSidebar
							? __( 'Hide Settings', 'boxuk' )
							: __( 'Show Settings', 'boxuk' )
					}
					className={ showSidebar ? 'is-pressed' : '' }
				/>
			</div>
		</div>
	);
};
