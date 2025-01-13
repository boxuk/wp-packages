# Usage

Any plugins and themes should be installed via composer. Any custom development that doesn't fit should be done as part of `mu-plugins`. Any custom work done on the visual element of the site should be done within `themes`.

## Memcache caching (optional)

This one is optional but highly recommended, if you want to use memcache for object caching, you need to run the following (will have been done during install if you used the install script):

`cp wp-content/plugins/memcached/object-cache.php wp-content/object-cache.php`

## Running the code sniffs

We use the [WordPress VIP coding standards](https://github.com/Automattic/VIP-Coding-Standards) to ensure the code is adhering to the best possible performance and security practices. We also use the core [WordPress standards](https://github.com/WordPress/WordPress-Coding-Standards) and the [Neutron standards](https://github.com/Automattic/phpcs-neutron-standard) to ensure for consistency for a modern WordPress code base. You can check your code against all of this by running the following command:

`bin/docker/phpcs`

## Working with Feature Flags

We encourage working with feature flags. You can review the [documentation](../../packages/feature-flags/README.md);
