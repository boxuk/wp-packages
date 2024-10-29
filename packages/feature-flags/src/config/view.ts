import type { View, SupportedLayouts } from '@wordpress/dataviews';

export const defaultView: View = {
	fields: [
		'name',
		'description',
		'group',
		'created',
		'is_published',
		'users',
	],
	filters: [],
	page: 1,
	perPage: 20,
	search: '',
	type: 'table',
};

export const layouts: SupportedLayouts = {
	table: {
		fields: [ 'key', 'name', 'description', 'is_published', 'users' ],
	},
};
