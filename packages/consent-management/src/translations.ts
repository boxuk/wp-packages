/** WordPress dependancies */
import { __ } from '@wordpress/i18n';

export const translations = window.consentManagement?.translations ?? {
	functionalityStorageLabel: __(
		'Enables storage that supports the functionality of the website',
		'boxuk'
	),
	securityStorageLabel: __(
		'Enables storage related to security such as authentication functionality, fraud prevention, and other user protection',
		'boxuk'
	),
	analyticsStorageLabel: __(
		'Enables storage, such as cookies, related to analytics (for example, visit duration)',
		'boxuk'
	),
	personalizationStorageLabel: __(
		'Enables storage related to personalization',
		'boxuk'
	),
	adStorageLabel: __(
		'Enables storage, such as cookies, related to advertising',
		'boxuk'
	),
	adUserDataLabel: __(
		'Sets consent for sending user data related to advertising to Google',
		'boxuk'
	),
	adPersonalizationLabel: __(
		'Enables storage related to personalization such as video recommendations',
		'boxuk'
	),
	modal: {
		title: __( 'Privacy', 'boxuk' ),
		description: __(
			'Our website uses cookies and similar technologies to provide you with a better service while searching or placing an order, for analytical purposes and to personalise our advertising.',
			'boxuk'
		),
		acceptAllBtn: __( 'Accept all', 'boxuk' ),
		acceptNecessaryBtn: __( 'Accept necessary', 'boxuk' ),
		showPreferencesBtn: __( 'Privacy preferences', 'boxuk' ),
	},
	preferences: {
		title: __( 'Privacy preferences', 'boxuk' ),
		acceptAllBtn: __( 'Accept all', 'boxuk' ),
		acceptNecessaryBtn: __( 'Accept only necessary', 'boxuk' ),
		savePreferencesBtn: __( 'Save preferences', 'boxuk' ),
		closeIconLabel: __( 'Dismiss', 'boxuk' ),
	},
	main: {
		title: __( 'Consent Management', 'boxuk' ),
		description: __(
			'When you visit any website, it may store or retrieve information on your browser, mostly in the form of cookies. This information might be about you, your preferences or your device and is mostly used to make the site work as you expect it to. The information does not usually directly identify you, but it can give you a more personalized web experience. Because we respect your right to privacy, you can choose not to allow some types of cookies. Click on the different category headings to find out more and change our default settings. However, blocking some types of cookies may impact your experience of the site and the services we are able to offer',
			'boxuk'
		),
	},
	necessary: {
		title: __( 'Necessary', 'boxuk' ),
		description: __(
			'These cookies are necessary for the site to function properly.',
			'boxuk'
		),
	},
	analytics: {
		title: __( 'Analytics', 'boxuk' ),
		description: __(
			'These are used to track user interaction and detect potential problems. These help us improve our services by providing analytical data on how users use this site.',
			'boxuk'
		),
	},
	personalisation: {
		title: __( 'Personalization', 'boxuk' ),
		description: __(
			'These cookies allow the provision of enhance functionality and personalization, such as videos and live chats. They may be set by us or by third party providers whose services we have added to our pages. If you do not allow these cookies, then some or all of these functionalities may not function properly.',
			'boxuk'
		),
	},
	advertising: {
		title: __( 'Advertising', 'boxuk' ),
		description: __(
			'These cookies are used to display relevant advertising to visitors, as well as to track the volume of visitors. They track details about visitors such as the number of unique visitors, number of times particular ads have been displayed, the number of clicks the ads have received, and are also used to measure the effectiveness of ad campaigns by building up user profiles. These are set by trusted third party networks, and are generally persistent in nature.',
			'boxuk'
		),
	},
};
