import { useSelect } from '@wordpress/data';
import {
	type Context,
	store as CoreStore,
	type User,
} from '@wordpress/core-data';

export const useUsers = < C extends Context >(
	{ context }: { context: C } = { context: 'edit' as C }
) =>
	useSelect(
		( select ) => select( CoreStore ).getUsers( { context } ),
		[ context ]
	) as User< C >[];
