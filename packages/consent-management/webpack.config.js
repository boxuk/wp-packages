const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

module.exports = {
	...defaultConfig,
	entry: {
		...defaultConfig.entry(),
		frontend: './src/index.ts',
		admin: './src/admin/index.tsx',
	},
};
