/* WordPress deps */
import { apiFetch } from '@wordpress/data-controls';
import { resolveSelect, select } from '@wordpress/data';

/* Internal deps */
import { store } from '.';
import { SETTINGS_KEY, STORE_KEY, TYPES } from './constants';

/* Types */
import type { dataResponse, gtmConfigData, translationsData } from './types';

const {
	SET_TRANSLATIONS,
	HYDRATE,
	SAVE_SUCCESS,
	SAVE_FAILURE,
	SET_GTM_CONFIG,
} = TYPES;

export function* setTranslation( key: string, value: string ) {
	const canonicalData: NonNullable< dataResponse > = yield resolveSelect(
		STORE_KEY,
		'getData'
	);
	const translations = { ...canonicalData.translations, [ key ]: value };
	return setTranslations( translations );
}

export function* setTranslations( translations: translationsData ) {
	return {
		type: SET_TRANSLATIONS,
		translations,
	};
}

export function* setGtmConfig( tagConfig: gtmConfigData ) {
	return {
		type: SET_GTM_CONFIG,
		tagConfig,
	};
}

export const hydrate = ( data: dataResponse ) => {
	return {
		type: HYDRATE,
		data,
	};
};

export function* save() {
	const data = select( store ).getData();
	const payload = { [ SETTINGS_KEY ]: data };
	try {
		const response: NonNullable< dataResponse > = yield apiFetch( {
			path: 'wp/v2/settings',
			method: 'PUT',
			body: JSON.stringify( payload ),
		} );

		return {
			type: SAVE_SUCCESS,
			data: response[ SETTINGS_KEY ],
		};
	} catch ( error: unknown ) {
		if ( ! ( error instanceof Error ) ) {
			throw error;
		}

		console.error( { payload, error } ); // eslint-disable-line no-console -- We want to log the error

		return {
			type: SAVE_FAILURE,
		};
	}
}
