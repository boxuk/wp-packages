<?php

declare(strict_types=1);

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\Config\MBConfig;

return static function ( MBConfig $config ): void {
	$config->packageDirectories( [ __DIR__ . '/packages' ] );
	$config->packageDirectoriesExcludes( [ __DIR__ . '/packages/vendor' ] );

	$config->dataToAppend([ 
		ComposerJsonSection::AUTHORS => [
			[
				'name' => 'BoxUK',
				'email' => 'developers@boxuk.com'
			],
		],
		ComposerJsonSection::REPOSITORIES => [ 
			[
				'type' => 'composer',
				'url' => 'https://wpackagist.org',
				'only' => ['wpackagist-plugin/*', 'wpackagist-theme/*'],
			],
		],
		ComposerJsonSection::REQUIRE_DEV => [
			'automattic/vipwpcs' => '^3.0',
			'permafrost-dev/coverage-check' => '^2.0',
			'phpcompatibility/phpcompatibility-wp' => '^2.1',
			'symplify/monorepo-builder' => '^11.2',
			'szepeviktor/phpstan-wordpress' => '^1.3',
			'wpackagist-plugin/sqlite-database-integration' => '^2.0',
			'wpackagist-theme/twentytwentyfour' => '^1.0',
		],
		ComposerJsonSection::CONFIG => [
			'vendor-dir' => 'packages/vendor',
			'allow-plugins' => [
				'dealerdirect/phpcodesniffer-composer-installer' => true,
				'composer/installers' => true,
			],
		],
		ComposerJsonSection::EXTRA => [ 
			'installer-paths' => [
				"docker/wordpress/mu-plugins/{\$name}" => [
					"type:wordpress-plugin",
					"type:wordpress-muplugin"
				],
				"docker/wordpress/themes/{\$name}" => [
					"type:wordpress-theme"
				]
			]
		]
	]);
};
