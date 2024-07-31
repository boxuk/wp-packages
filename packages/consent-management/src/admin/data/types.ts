/* Internal deps */
import { DEFAULT_STATE } from './default';

export type translationsData = typeof DEFAULT_STATE.translations | undefined;
export type gtmConfigData = typeof DEFAULT_STATE.tagConfig | undefined;
export type dataResponse = typeof DEFAULT_STATE | undefined;

export const isDataResponse = ( data: unknown ): data is dataResponse => {
	return (
		typeof data === 'object' &&
		data !== null &&
		Object.keys( DEFAULT_STATE ).every(
			( key ) =>
				key in data &&
				typeof data[ key ] === typeof DEFAULT_STATE[ key ]
		) &&
		'translations' in data &&
		typeof data.translations === 'object' &&
		data.translations !== null &&
		Object.keys( DEFAULT_STATE.translations ).every(
			( key ) =>
				key in data.translations &&
				typeof data.translations[ key ] === 'string'
		)
	);
};
