/* Internal dependencies */
import { updateConsent } from './consents';
import { translations } from './translations';

/* Types */
import type { Category, CookieConsentConfig } from 'vanilla-cookieconsent';
import type { ConsentParams } from './gtag.type';

/* A helper function to create the accept/reject handlers for a consent key */
const acceptRejectHandlers = ( key: keyof ConsentParams ) => ( {
	onAccept: () => updateConsent( key, true ),
	onReject: () => updateConsent( key, false ),
} );

/* The consent categories */
const consentCategories: { [ key: string ]: Category } = {
	necessary: {
		enabled: true,
		readOnly: true,
		services: {
			functionality_storage: {
				label: translations.functionalityStorageLabel,
				...acceptRejectHandlers( 'functionality_storage' ),
			},
			security_storage: {
				label: translations.securityStorageLabel,
				...acceptRejectHandlers( 'security_storage' ),
			},
		},
	},
	analytics: {
		services: {
			analytics_storage: {
				label: translations.analyticsStorageLabel,
				...acceptRejectHandlers( 'analytics_storage' ),
			},
		},
	},
	personalization: {
		services: {
			personlization_storage: {
				label: translations.personalizationStorageLabel,
				...acceptRejectHandlers( 'personalization_storage' ),
			},
		},
	},
	ads: {
		services: {
			ad_storage: {
				label: translations.adStorageLabel,
				...acceptRejectHandlers( 'ad_storage' ),
			},
			ad_user_data: {
				label: translations.adUserDataLabel,
				...acceptRejectHandlers( 'ad_user_data' ),
			},
			ad_personalization: {
				label: translations.adPersonalizationLabel,
				...acceptRejectHandlers( 'ad_personalization' ),
			},
		},
	},
};

/* The cookie consent configuration */
export const cookieConsentConfig: CookieConsentConfig = {
	disablePageInteraction: true,
	guiOptions: {
		consentModal: {
			layout: 'box',
			position: 'middle center',
			flipButtons: false,
			equalWeightButtons: true,
		},
		preferencesModal: {
			layout: 'box',
			flipButtons: true,
			equalWeightButtons: false,
		},
	},
	categories: consentCategories,
	language: {
		default: 'en',
		translations: {
			en: {
				consentModal: translations.modal,
				preferencesModal: {
					...translations.preferences,
					sections: [
						{
							...translations.main,
						},
						{
							...translations.necessary,
							linkedCategory: 'necessary',
						},
						{
							...translations.analytics,
							linkedCategory: 'analytics',
						},
						{
							...translations.personalisation,
							linkedCategory: 'personalization',
						},
						{
							...translations.advertising,
							linkedCategory: 'ads',
						},
					],
				},
			},
		},
	},
};
