import React, { type ComponentProps } from 'react';

/* WordPress Deps */
import { TabPanel, Panel } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/* Internal Deps */
import { ModalSidebar } from './ModalSidebar';
import { PreferencesSidebar } from './PreferencesSidebar';
import { TagConfigSidebar } from './TagConfigSidebar';

/**
 * The Tab type isn't exposed from the TabPanel component, so we have to
 * define it here. Extended with a content property to hold the JSX content.
 */
type Tab = ComponentProps< typeof TabPanel >[ 'tabs' ][ 0 ] &
	Partial< {
		content: JSX.Element;
	} >;

export const Sidebar = ( {
	setCurrentTab,
}: {
	setCurrentTab: ( tab: string ) => void;
} ) => {
	const tabs: Tab[] = [
		{
			title: __( 'Modal', 'boxuk' ),
			name: 'modal',
			content: <ModalSidebar />,
		},
		{
			title: __( 'Preference Panel', 'boxuk' ),
			name: 'preferences',
			content: <PreferencesSidebar />,
		},
		{
			title: __( 'Tag Configuration', 'boxuk' ),
			name: 'tags',
			content: <TagConfigSidebar />,
		},
	];

	return (
		<div className={ 'interface-complementary-area' }>
			<TabPanel
				onSelect={ ( name ) =>
					'tags' !== name && setCurrentTab( name )
				}
				tabs={ tabs }
			>
				{ ( { content }: Tab ) => <Panel>{ content }</Panel> }
			</TabPanel>
		</div>
	);
};
