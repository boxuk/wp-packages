/* Internal deps */
import { DEFAULT_STATE } from './default';

/* Types */
import type { dataResponse, translationsData } from './types';

export const getData = ( state: dataResponse ) => state;

export const getTranslations = ( state: dataResponse ) =>
	state?.translations ?? DEFAULT_STATE.translations;

export const getTagData = ( state: dataResponse ) =>
	state?.tagConfig ?? DEFAULT_STATE.tagConfig;

export const getTranslation = (
	state: dataResponse,
	key: keyof translationsData
) => state?.translations[ key ] ?? DEFAULT_STATE.translations[ key ];
