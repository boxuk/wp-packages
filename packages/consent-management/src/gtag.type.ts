export interface GtagCommands {
	config: [
		targetId: string,
		config?: ControlParams | EventParams | ConfigParams | CustomParams,
	];
	set:
		| [ targetId: string, config: CustomParams | boolean | string ]
		| [ config: CustomParams ];
	js: [ config: Date ];
	event: [
		eventName: EventNames | ( string & {} ),
		eventParams?: ControlParams | EventParams | CustomParams,
	];
	get: [
		targetId: string,
		fieldName: FieldNames | string,
		callback?: ( field: string | CustomParams | undefined ) => any,
	];
	consent: [
		consentArg: ConsentArg | ( string & {} ),
		consentParams: ConsentParams,
	];
}

export interface Command {
	< GtagCommand extends keyof GtagCommands >(
		command: GtagCommand,
		...args: GtagCommands[ GtagCommand ]
	): void;
}

export interface ConfigParams {
	page_title?: string | undefined;
	page_location?: string | undefined;
	page_path?: string | undefined;
	send_page_view?: boolean | undefined;
}

export interface CustomParams {
	[ key: string ]: any;
}

export interface ControlParams {
	groups?: string | string[] | undefined;
	send_to?: string | string[] | undefined;
	event_callback?: ( () => void ) | undefined;
	event_timeout?: number | undefined;
}

export type EventNames =
	| 'add_payment_info'
	| 'add_shipping_info'
	| 'add_to_cart'
	| 'add_to_wishlist'
	| 'begin_checkout'
	| 'checkout_progress'
	| 'earn_virtual_currency'
	| 'exception'
	| 'generate_lead'
	| 'join_group'
	| 'level_end'
	| 'level_start'
	| 'level_up'
	| 'login'
	| 'page_view'
	| 'post_score'
	| 'purchase'
	| 'refund'
	| 'remove_from_cart'
	| 'screen_view'
	| 'search'
	| 'select_content'
	| 'select_item'
	| 'select_promotion'
	| 'set_checkout_option'
	| 'share'
	| 'sign_up'
	| 'spend_virtual_currency'
	| 'tutorial_begin'
	| 'tutorial_complete'
	| 'unlock_achievement'
	| 'timing_complete'
	| 'view_cart'
	| 'view_item'
	| 'view_item_list'
	| 'view_promotion'
	| 'view_search_results';

export interface EventParams {
	checkout_option?: string | undefined;
	checkout_step?: number | undefined;
	content_id?: string | undefined;
	content_type?: string | undefined;
	coupon?: string | undefined;
	currency?: string | undefined;
	description?: string | undefined;
	fatal?: boolean | undefined;
	items?: Item[] | undefined;
	method?: string | undefined;
	number?: string | undefined;
	promotions?: Promotion[] | undefined;
	screen_name?: string | undefined;
	search_term?: string | undefined;
	shipping?: Currency | undefined;
	tax?: Currency | undefined;
	transaction_id?: string | undefined;
	value?: number | undefined;
	event_label?: string | undefined;
	event_category?: string | undefined;
}

export type Currency = string | number;

/**
 * export interface of an item object used in lists for this event.
 *
 * Reference:
 * @see {@link https://developers.google.com/analytics/devguides/collection/ga4/reference/events#view_item_item view_item_item}
 * @see {@link https://developers.google.com/analytics/devguides/collection/ga4/reference/events#view_item_list_item view_item_list_item}
 * @see {@link https://developers.google.com/analytics/devguides/collection/ga4/reference/events#select_item_item select_item_item}
 * @see {@link https://developers.google.com/analytics/devguides/collection/ga4/reference/events#add_to_cart_item add_to_cart_item}
 * @see {@link https://developers.google.com/analytics/devguides/collection/ga4/reference/events#view_cart_item view_cart_item}
 */
export interface Item {
	item_id?: string | undefined;
	item_name?: string | undefined;
	affiliation?: string | undefined;
	coupon?: string | undefined;
	currency?: string | undefined;
	creative_name?: string | undefined;
	creative_slot?: string | undefined;
	discount?: Currency | undefined;
	index?: number | undefined;
	item_brand?: string | undefined;
	item_category?: string | undefined;
	item_category2?: string | undefined;
	item_category3?: string | undefined;
	item_category4?: string | undefined;
	item_category5?: string | undefined;
	item_list_id?: string | undefined;
	item_list_name?: string | undefined;
	item_variant?: string | undefined;
	location_id?: string | undefined;
	price?: Currency | undefined;
	promotion_id?: string | undefined;
	promotion_name?: string | undefined;
	quantity?: number | undefined;
}

export interface Promotion {
	creative_name?: string | undefined;
	creative_slot?: string | undefined;
	promotion_id?: string | undefined;
	promotion_name?: string | undefined;
}

export type FieldNames = 'client_id' | 'session_id' | 'gclid';

export type ConsentArg = 'default' | 'update';

/**
 * Reference:
 * @see {@link https://support.google.com/tagmanager/answer/10718549#consent-types consent-types}
 * @see {@link https://developers.google.com/tag-platform/devguides/consent consent}
 */
export interface ConsentParams {
	ad_storage?: ConsentTypeState;
	ad_user_data?: ConsentTypeState;
	ad_personalization?: ConsentTypeState;
	analytics_storage?: ConsentTypeState;
	functionality_storage?: ConsentTypeState;
	personalization_storage?: ConsentTypeState;
	security_storage?: ConsentTypeState;
	wait_for_update?: number | undefined;
	region?: string[] | undefined;
}

export type ConsentTypeState = 'granted' | 'denied' | undefined;