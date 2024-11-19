import { useSelect } from '@wordpress/data';

import { store } from '.';

export const useFlags = () =>
	useSelect( ( select ) => select( store ).getFlags(), [] );
