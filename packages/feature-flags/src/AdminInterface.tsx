import React from 'react';

import { DataViews, type View } from '@wordpress/dataviews';
import { actions, defaultView, defaultLayouts, filterData } from './config';

import { useFlags } from './data/hooks';

import './styles.scss';
import { useLocalStorageState } from './utils/useLocalStorageState';
import { useFields } from './config/fields';

export const AdminInterface = () => {
	const [ view, setView ] = useLocalStorageState< View >(
		'featureFlagsView',
		defaultView
	);

	const data = useFlags();
	const fields = useFields();

	const { page = 1, perPage = 10 } = view;
	const filteredData = filterData( data ?? [], view );

	return (
		<>
			<DataViews
				header={
					<>
						{ data
							? filteredData.length.toLocaleString() + ' flags'
							: null }
					</>
				}
				isLoading={ undefined === data }
				actions={ actions }
				defaultLayouts={ defaultLayouts }
				fields={ fields }
				view={ view }
				data={ filteredData.slice(
					( page - 1 ) * perPage,
					page * perPage
				) }
				paginationInfo={ {
					totalItems: filteredData.length,
					totalPages: Math.ceil( filteredData.length / perPage ),
				} }
				onChangeView={ setView }
				getItemId={ ( item ) => ( item.key || 0 ).toString() }
			/>
		</>
	);
};
