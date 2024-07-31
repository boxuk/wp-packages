import domReady from '@wordpress/dom-ready';
/* Local Deps */
import initDefaultGtm from './gtm';
import { initDefaultConsents, initConsentManager } from './consents';
import './style.scss';

/* Initialise the cookie consent manager */
domReady( () => {
	initDefaultConsents();
	initDefaultGtm();
	initConsentManager();
} );
