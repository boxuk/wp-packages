/* Internal deps */
import { TYPES } from './constants';

/* Types */
import type { dataResponse, gtmConfigData, translationsData } from './types';

const {
	SET_TRANSLATIONS,
	HYDRATE,
	SAVE_SUCCESS,
	SAVE_FAILURE,
	SET_GTM_CONFIG,
} = TYPES;

const reducer = (
	state: dataResponse,
	{
		type,
		translations,
		data,
		tagConfig,
	}: {
		type: string;
		enabled?: boolean;
		translations?: translationsData;
		tagConfig?: gtmConfigData;
		data?: dataResponse;
	}
) => {
	switch ( type ) {
		case SET_TRANSLATIONS:
			return {
				...state,
				translations,
			};
		case HYDRATE:
			return { ...data };
		case SET_GTM_CONFIG:
			return {
				...state,
				tagConfig,
			};
		case SAVE_SUCCESS:
			return { ...data };
		case SAVE_FAILURE:
		default:
			return state;
	}
};

export default reducer;
