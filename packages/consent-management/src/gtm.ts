import { DEFAULT_STATE } from './admin/data/default';
import type { Command } from './gtag.type';

import { addQueryArgs } from '@wordpress/url';

/* Declare global type for window.dataLayer */
declare global {
	interface Window {
		dataLayer?: unknown[];
		gtag?: Command;
		consentManagement: typeof DEFAULT_STATE;
	}
}

/* Gets the GTM URL based on the hostname */
const getGtmUrl = () => {
	const gtmBase = 'https://www.googletagmanager.com/gtm.js';
	const data = window.consentManagement.tagConfig;

	if ( ! data || ! data.id || data.id === '' ) {
		return false;
	}

	return addQueryArgs( gtmBase, {
		id: data.id,
		gtm_auth: data.auth ? data.auth : undefined,
		gtm_preview: data.preview ? data.preview : undefined,
		gtm_cookies_win: 'x',
	} );
};

export const push: Command = function () {
	window.dataLayer = window.dataLayer || [];
	window.dataLayer.push( arguments ); // eslint-disable-line prefer-rest-params
};

/* Initialise the Google Tag Manager */
const initialiseGoogleTagManager = ( gtmUrl: string ) => {
	window.dataLayer = window.dataLayer || [];
	window.dataLayer.push( {
		'gtm.start': new Date().getTime(),
		event: 'gtm.js',
	} );
	const firstScript = document.getElementsByTagName( 'script' )[ 0 ];
	const newScript = document.createElement( 'script' );
	newScript.setAttribute( 'async', 'true' );
	newScript.setAttribute( 'src', gtmUrl );
	firstScript.parentNode?.insertBefore( newScript, firstScript );
};

/* Initialise the Google Tag Manager */
export default function initDefaultGtm() {
	const gtmUrl = getGtmUrl();
	if ( gtmUrl ) {
		initialiseGoogleTagManager( gtmUrl );
	}
}
