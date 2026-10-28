import { addFilter } from '@wordpress/hooks';

const COMMENT_BLOCK_PREFIXES = [
	'core/comment-',
	'core/comments',
	'core/post-comments',
	'core/latest-comments',
];

type BlockSettings = {
	supports?: Record< string, unknown >;
};

export function hideCommentBlocks( settings: BlockSettings, name: string ) {
	if (
		! COMMENT_BLOCK_PREFIXES.some( ( prefix ) => name.startsWith( prefix ) )
	) {
		return settings;
	}

	return {
		...settings,
		supports: { ...settings.supports, inserter: false },
	};
}

addFilter(
	'blocks.registerBlockType',
	'boxuk/editor-tools/hide-comment-blocks',
	hideCommentBlocks
);
