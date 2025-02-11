/* WordPress deps */
import { apiFetch } from '@wordpress/data-controls';

/* Internal deps */
import { hydrate } from './actions';
import { SETTINGS_KEY } from './constants';
import { DEFAULT_STATE } from './default';

export function* getData() {
	const data = yield apiFetch( { path: 'wp/v2/settings' } );
	return hydrate( data[ SETTINGS_KEY ] ?? DEFAULT_STATE );
}

export function* getTranslations() {
	yield getData();
}

// eslint-disable-next-line @typescript-eslint/no-unused-vars -- this method has to match the signature of the selector.
export function* getTranslation( key: string ) {
	yield getData();
}

export function* getGtmConfig() {
	yield getData();
}
