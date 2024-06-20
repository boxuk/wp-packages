/* WordPress Dependencies */
import { registerBlockType } from '@wordpress/blocks';

/* Internal deps */
import metadata from './block.json';
import { Edit } from './Edit';
import { Save } from './Save';
import { ReactComponent as AddReactionOutlined } from '../Icon.svg';

registerBlockType( metadata, {
	icon: AddReactionOutlined,
	edit: Edit,
	save: Save,
} );
