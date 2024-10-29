import React from 'react';

import { Icon } from '@wordpress/components';
import {
	published,
	closeSmall,
	lockSmall,
	drafts,
	notAllowed,
} from '@wordpress/icons';
import { Flag } from '../types';

export const BooleanRender = ( { flag }: { flag: Flag } ) => {
	if ( flag.enforced ) {
		return (
			<>
				{ /* <Icon icon={ published } /> */ }
				<Icon icon={ lockSmall } />
				<span>Enforced</span>
			</>
		);
	}
	if ( ! flag.stable ) {
		return (
			<>
				<Icon icon={ notAllowed } />
				<span>Disabled</span>
			</>
		);
	}

	if ( ! flag.is_published && ( flag.users?.length ?? 0 > 0 ) ) {
		return (
			<>
				<Icon icon={ drafts } />
				<span>Published for { flag.users?.length } users</span>
			</>
		);
	}

	if ( flag.is_published ) {
		return (
			<>
				<Icon icon={ published } />
				<span>Published</span>
			</>
		);
	}

	if ( ! flag.is_published ) {
		return (
			<>
				<Icon icon={ closeSmall } />
				<span>Unpublished</span>
			</>
		);
	}

	return null;
};
