import React from 'react';

import { useState } from '@wordpress/element';
import { type BlockEditProps } from '@wordpress/blocks';
import { Panel, PanelBody, Button, Popover } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { help } from '@wordpress/icons';

import { SelectPostsControl } from './SelectPostsControl';

export const AdditionalControls = ( {
	setAttributes,
	attributes,
}: BlockEditProps< {
	query?: {
		inherit?: boolean;
		include?: number[];
		exclude?: number[];
		postType?: string;
	};
} > ) => {
	const { query } = attributes;
	const [ isHelpOpen, setIsHelpOpen ] = useState( false );

	/**
	 * Includes needs to be undefined if empty
	 * @param includes
	 */
	const setQueryIncludes = ( includes: number[] ) => {
		setAttributes( {
			query: {
				...query,
				include: includes.length ? includes : undefined,
			},
		} );
	};

	/**
	 * Excludes needs to be an empty array if empty
	 * @param excludes
	 */
	const setQueryExcludes = ( excludes: number[] ) => {
		setAttributes( {
			query: {
				...query,
				exclude: excludes,
			},
		} );
	};

	if ( query?.inherit ) {
		return null;
	}

	return (
		<Panel>
			<PanelBody title="Specified Posts">
				<SelectPostsControl
					label={ __( 'Include', 'boxuk-query-include-exclude' ) }
					selected={ query?.include || [] }
					onChange={ setQueryIncludes }
					postType={ query?.postType }
				/>
				<SelectPostsControl
					label={ __( 'Exclude', 'boxuk-query-include-exclude' ) }
					selected={ query?.exclude || [] }
					onChange={ setQueryExcludes }
					postType={ query?.postType }
				/>

				<Button
					onClick={ () => setIsHelpOpen( ! isHelpOpen ) }
					icon={ help }
				>
					{ __( 'More Info', 'boxuk-query-include-exclude' ) }
					{ isHelpOpen && (
						<Popover>
							<div style={ { minWidth: '200px' } }>
								<PanelBody>
									{ __(
										'Marking posts to include will override any other query settings and force only these posts to be displayed. Using the exclude option will remove these posts from the query results but will respect other query settings.',
										'boxuk-query-include-exclude'
									) }
								</PanelBody>
							</div>
						</Popover>
					) }
				</Button>
			</PanelBody>
		</Panel>
	);
};
