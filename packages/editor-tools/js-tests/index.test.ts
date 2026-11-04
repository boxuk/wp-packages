import { describe, expect, test } from '@jest/globals';
import { applyFilters } from '@wordpress/hooks';
import { hideCommentBlocks } from '../assets';

describe( 'hideCommentBlocks', () => {
	test.each( [
		'core/comment-template',
		'core/comment-content',
		'core/comments',
		'core/comments-pagination',
		'core/post-comments',
		'core/post-comments-count',
		'core/latest-comments',
	] )( 'hides %s and preserves other settings', ( name ) => {
		const settings = {
			title: 'Comments',
			supports: { html: false, inserter: true },
		};
		const result = hideCommentBlocks( settings, name );
		expect( result ).toEqual( {
			...settings,
			supports: { html: false, inserter: false },
		} );
		expect( settings.supports.inserter ).toBe( true );
	} );

	test( 'handles missing supports', () => {
		expect( hideCommentBlocks( {}, 'core/comments' ) ).toEqual( {
			supports: { inserter: false },
		} );
	} );

	test.each( [
		'core/paragraph',
		'example/comments',
		'example/custom',
		'core/comment',
	] )( 'leaves %s unchanged', ( name ) => {
		const settings = { supports: { inserter: true } };
		expect( hideCommentBlocks( settings, name ) ).toBe( settings );
	} );

	test( 'registers the editor filter', () => {
		expect(
			applyFilters( 'blocks.registerBlockType', {}, 'core/comments' )
		).toEqual( {
			supports: { inserter: false },
		} );
	} );
} );
