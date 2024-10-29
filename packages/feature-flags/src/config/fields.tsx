import React from 'react';

import type { Fields } from '@wordpress/dataviews';
import { Flag } from '../types';
import { BooleanRender } from '../components/BooleanRender';
import { UsersRender } from '../components/UsersRender';
import { useSelect } from '@wordpress/data';
import { store } from '../data';
import { toDate } from '../utils';

export const useFields = (): Fields< Flag > => {
	const groups = useSelect( ( select ) => select( store ).getGroups(), [] );
	return [
		{
			id: 'key',
			label: 'Key',
			type: 'text',
		},
		{
			id: 'name',
			label: 'Name',
			enableSorting: true,
			enableGlobalSearch: true,
			enableHiding: true,
		},
		{
			id: 'description',
			label: 'Description',
			type: 'text',
		},
		{
			id: 'created',
			label: 'Created',
			type: 'datetime',
			render: ( row ) => toDate( row.item.created )?.toLocaleString(),
		},
		{
			id: 'group',
			label: 'Group',
			type: 'text',
			elements: groups.map( ( group: string ) => ( {
				label: group,
				value: group,
			} ) ),
		},
		{
			id: 'is_published',
			label: 'State',
			render: ( row ) => <BooleanRender flag={ row.item } />,
		},
		{
			id: 'users',
			label: 'Users',
			render: ( row ) => <UsersRender flag={ row.item } />,
		},
	];
};
