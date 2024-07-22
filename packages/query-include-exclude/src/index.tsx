import React from 'react';

import { type BlockEditProps } from '@wordpress/blocks';
import { InspectorControls } from '@wordpress/block-editor';
import { createHigherOrderComponent } from '@wordpress/compose';
import { addFilter } from '@wordpress/hooks';

import { AdditionalControls } from './AdditionalControls';

addFilter(
	'editor.BlockEdit',
	'boxuk/query-include-exclude',
	createHigherOrderComponent(
		( BlockEdit: React.FC< BlockEditProps< any > > ) => {
			return ( props: BlockEditProps< Record< string, any > > ) => {
				if (
					'name' in props === false ||
					props.name !== 'core/query'
				) {
					return <BlockEdit { ...props } />;
				}

				return (
					<>
						<BlockEdit { ...props } />
						<InspectorControls>
							<AdditionalControls { ...props } />
						</InspectorControls>
					</>
				);
			};
		},
		'boxuk-query-include-exclude'
	)
);
