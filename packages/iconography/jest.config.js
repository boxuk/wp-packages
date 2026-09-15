const defaultConfig = require( '@wordpress/scripts/config/jest-unit.config' );

module.exports = {
	...defaultConfig,
	// Allow Jest to transform ESM files from WordPress packages and uuid
	// untransformed imports cause parse errors from newer WP packages.
	transform: {
		...defaultConfig.transform,
		'^.+\\.mjs$': defaultConfig.transform[ '\\.[jt]sx?$' ],
	},
	transformIgnorePatterns: [ '/node_modules/(?!(?:@wordpress|uuid)/)' ],
};
