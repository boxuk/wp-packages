import React from 'react';

/* WordPress Deps */
import { createRoot } from '@wordpress/element';
import domReady from '@wordpress/dom-ready';

/* Internal Deps */
import { registerStore } from './data';
import { Main } from './Main';

domReady( () => {
	const root = document.getElementById( 'consent-settings-root' );
	if ( root ) {
		registerStore();
		createRoot( root ).render( <Main /> );
	}
} );
