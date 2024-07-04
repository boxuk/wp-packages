/* WordPress Dependencies */
import domReady from '@wordpress/dom-ready';

/* Internal deps */
import registerInlineIconography from './registerInlineIconography';

domReady( () => {
	registerInlineIconography();
} );

/* Export for other packages to consume */
export * from './types';
export * from './shared';
export * from './utils';
