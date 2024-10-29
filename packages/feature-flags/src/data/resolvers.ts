/* WordPress deps */
import { apiFetch } from '@wordpress/data-controls';
import { setFlags } from './actions';

export function* getFlags() {
	const data = yield apiFetch( { path: 'feature-flags/v1/flags' } );
	return setFlags( data );
}
