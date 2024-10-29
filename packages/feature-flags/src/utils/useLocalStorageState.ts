import { useState } from 'react';

export const useLocalStorageState = < T >(
	key: string,
	defaultValue: T
): [ T, ( value: T ) => void ] => {
	const [ state, setState ] = useState( () => {
		const storedValue = localStorage.getItem( key );
		return storedValue ? JSON.parse( storedValue ) : defaultValue;
	} );

	const setStoredState = ( value: T ) => {
		setState( value );
		localStorage.setItem( key, JSON.stringify( value ) );
	};

	return [ state, setStoredState ];
};
