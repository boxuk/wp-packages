import React from 'react';

import { useDispatch } from '@wordpress/data';
import { Flex, Button } from '@wordpress/components';
import { drawerRight } from '@wordpress/icons';
import { __ } from '@wordpress/i18n';

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
	const { save } = useDispatch( store );

	return (
		<div className="editor-header edit-post-header">
			<Flex
				direction="row"
				justify="space-between"
				align="center"
				style={ { padding: '1em' } }
			>
				<h2>{ __( 'Cookie Consent', 'boxuk' ) }</h2>
				{ hasFinishedResolution && (
					<Flex direction="row" justify="flex-end" gap="1em">
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
						<Button variant="primary" onClick={ save }>
							{ __( 'Save', 'boxuk' ) }
						</Button>
					</Flex>
				) }
			</Flex>
		</div>
	);
};
