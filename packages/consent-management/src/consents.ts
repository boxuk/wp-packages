import { run } from 'vanilla-cookieconsent';
import { cookieConsentConfig } from './config';
import type { ConsentParams } from './gtag.type';

import { push } from './gtm';

/* Default values for consents */
const consentDefaults: ConsentParams = {
	functionality_storage: 'granted',
	security_storage: 'granted',
	ad_storage: 'denied',
	ad_user_data: 'denied',
	ad_personalization: 'denied',
	analytics_storage: 'denied',
	personalization_storage: 'denied',
};

/* Sends consent data to the dataLayer */
export const updateConsents = ( consents: ConsentParams ) => {
	push( 'consent', 'update', consents );
};

/* Sets the default consents */
export const initDefaultConsents = () => {
	push( 'consent', 'default', consentDefaults );
};

/* Updates a single consent */
export const updateConsent = ( key: keyof ConsentParams, value: boolean ) => {
	const consents: ConsentParams = { [ key ]: value ? 'granted' : 'denied' };
	updateConsents( consents );
};

export const initConsentManager = () => {
	run( cookieConsentConfig );
};
