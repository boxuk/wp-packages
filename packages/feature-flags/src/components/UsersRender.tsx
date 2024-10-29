import React from 'react';

import { useSelect } from '@wordpress/data';
import { store as CoreStore, type User } from '@wordpress/core-data';

import type { Flag } from '../types';

export const UsersRender = ( { flag }: { flag: Flag } ) => {
	if ( ! flag.users || 0 === flag.users.length ) {
		return null;
	}

	return (
		<ul style={ { margin: 0 } }>
			{ flag.users?.map( ( userId ) => (
				<li key={ userId }>
					<UserRender userId={ userId } />
				</li>
			) ) }
		</ul>
	);
};

const UserRender = ( { userId }: { userId: string } ) => {
	const user = useSelect(
		( select ) =>
			select( CoreStore ).getEntityRecord< User< 'edit' > >(
				'root',
				'user',
				userId
			),
		[ userId ]
	);

	return user?.email ?? '';
};
