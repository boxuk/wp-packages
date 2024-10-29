import type { Flag } from '../types';
import type { View } from '@wordpress/dataviews';

export const filterData = ( data: Flag[], view: View ): Flag[] => {
	let filteredData = [ ...data ];

	if ( view.search && view.search.length > 2 ) {
		filteredData = filteredData.filter(
			( flag ) =>
				flag.name
					?.toLowerCase()
					.includes( view.search?.toLowerCase() ?? '' ) ||
				flag.description
					?.toLowerCase()
					.includes( view.search?.toLowerCase() ?? '' ) ||
				flag.group
					?.toLowerCase()
					.includes( view.search?.toLowerCase() ?? '' )
		);
	}

	if ( view.filters && view.filters.length > 0 ) {
		filteredData = filteredData.filter(
			( flag ) =>
				view.filters
					?.filter(
						( filter ) => filter.value && filter.value.length > 0
					)
					.forEach( ( filter ) => {
						switch ( filter.operator ) {
							case 'isAny':
								return filter.value.includes(
									flag[ filter.field ]
								);
							case 'isNone':
								return ! filter.value.includes(
									flag[ filter.field ]
								);
							case 'is':
								return filter.value === flag[ filter.field ];
							case 'isNot':
								return filter.value !== flag[ filter.field ];
							case 'isAll':
								return filter.value.every( ( value ) =>
									flag[ filter.field ].includes( value )
								);
							case 'isNotAll':
								return ! filter.value.every( ( value ) =>
									flag[ filter.field ].includes( value )
								);
							default:
								return true;
						}
					} )
		);
	}

	if ( view.sort && view.sort.field ) {
		filteredData = filteredData.sort( ( a, b ) => {
			const aValue = a[ view.sort?.field ?? '' ];
			const bValue = b[ view.sort?.field ?? '' ];

			if ( aValue === bValue ) {
				return 0;
			}

			if ( aValue > bValue ) {
				return view.sort?.direction === 'asc' ? 1 : -1;
			}

			return view.sort?.direction === 'asc' ? -1 : 1;
		} );
	}

	return filteredData;
};
