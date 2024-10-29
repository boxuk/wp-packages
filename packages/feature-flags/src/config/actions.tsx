import React from 'react';

import { Icon } from '@wordpress/components';
import { published, closeSmall, commentAuthorAvatar } from '@wordpress/icons';
import type { Action } from '@wordpress/dataviews';
import { dispatch } from '@wordpress/data';
import { store } from '../data';

import { Flag } from '../types';
import { UsersModal } from '../components/UsersModal';

export const actions: Action< Flag >[] = [
	{
		id: 'pubish',
		label: 'Publish',
		isPrimary: true,
		icon: <Icon icon={ published } />,
		supportsBulk: true,
		isEligible: ( item ) =>
			!! ( ! item.is_published && item.stable && ! item.enforced ),
		callback: ( items ) => {
			items.map( ( item ) =>
				dispatch( store ).updateFlag( { ...item, is_published: true } )
			);
		},
		disabled: true,
	},
	{
		id: 'manage_users',
		label: 'Manage Flag Users',
		isPrimary: true,
		icon: <Icon icon={ commentAuthorAvatar } />,
		supportsBulk: true,
		isEligible: ( item ) => !! ( item.stable && ! item.enforced ),
		RenderModal: UsersModal,
	},
	{
		id: 'unpublish',
		label: 'Unpublish',
		isPrimary: true,
		icon: <Icon icon={ closeSmall } />,
		supportsBulk: true,
		isDestructive: true,
		isEligible: ( item ) =>
			!! ( item.is_published && item.stable && ! item.enforced ),
		callback: ( items ) => {
			items.map( ( item ) =>
				dispatch( store ).updateFlag( { ...item, is_published: false } )
			);
		},
	},
];
