<?php

declare(strict_types=1);

use Symplify\MonorepoBuilder\Config\MBConfig;

return static function ( MBConfig $config ): void {
	$config->packageDirectories( array( __DIR__ . '/packages' ) );
};
