import React from 'react';

/* WordPress Deps */
import { useState } from '@wordpress/element';
import { EditorNotices, EditorSnackbars } from '@wordpress/editor';
import { InterfaceSkeleton } from '@wordpress/interface';
import { useViewportMatch } from '@wordpress/compose';
import { useSelect } from '@wordpress/data';
import { Spinner, Flex } from '@wordpress/components';

/* Internal Deps */
import { ModalPreview } from './components/ModalPreview';
import { PreferencesPreview } from './components/PreferencesPreview';
import { Sidebar } from './components/Sidebar';
import { Header } from './components/Header';

import { store } from './data';

export const Main = () => {
	// Call `getData` immediately to prime the data-store for all subsequent calls.
	// This will ensure that the data is available before the component renders, even
	// if we don't use the data in this component.
	const { data, dataHasLoaded } = useSelect( ( select ) => {
		const { getData, hasFinishedResolution } = select( store );
		return {
			data: getData(),
			dataHasLoaded: hasFinishedResolution( 'getData' ),
		};
	}, [] );

	const [ currentTab, setCurrentTab ] = useState( 'modal' );
	const isMobileViewport = useViewportMatch( 'medium', '<' );
	const [ showSidebar, setShowSidebar ] = useState( ! isMobileViewport );

	const Content = dataHasLoaded ? (
		<div
			className="show--consent show--preferences"
			style={ { padding: '48px 12px', height: '100%' } }
		>
			<div
				id="cc-main"
				style={ {
					position: 'relative',
					marginLeft: 'auto',
					marginRight: 'auto',
				} }
			>
				{ currentTab === 'preferences' ? (
					<PreferencesPreview />
				) : (
					<ModalPreview />
				) }
			</div>
		</div>
	) : (
		<Flex
			style={ { height: '100%', backgroundColor: 'white' } }
			align="center"
			justify="center"
		>
			<Spinner />
		</Flex>
	);

	return (
		<InterfaceSkeleton
			header={
				<Header
					showSidebar={ showSidebar && dataHasLoaded }
					setShowSidebar={ setShowSidebar }
					hasFinishedResolution={ dataHasLoaded }
				/>
			}
			sidebar={
				showSidebar &&
				dataHasLoaded && <Sidebar setCurrentTab={ setCurrentTab } />
			}
			content={ Content }
			notices={ <EditorSnackbars /> }
			editorNotices={ <EditorNotices /> }
		/>
	);
};
