# BoxUK Asset Loader

The Asset Loader class allows for any asset generated via WP-Scripts to be loaded to WordPress. 

## Basic Usage
Simple usage: 
```php
$loader = new Boxuk\BoxWpEditorTools\AssetLoader();
$loader->load( 'index' ); 
```

This will load the following files if they exist:
 - /wp-content/themes/{theme_name}/build/index.js
 - /wp-content/themes/{theme_name}/build/index.css

You should typically use these methods in a function tied to `wp_enqueue_scripts` hook. 

## Advanced Usage

### Base Path
You can specify the base path for the asset loader to load from. 
```php
$base_path = WP_CONTENT_DIR . '/plugins/my-plugin/assets'; 
$loader = new Boxuk\BoxWpEditorTools\AssetLoader( $base_path );

$loader->load( 'index' ); // loads wp-content/plugins/my-plugin/assets/index.js
$loader->load( 'admin' ); // loads wp-content/plugins/my-plugin/assets/admin.js
```

### Asset Handle Prefix
The scripts and styles are enqueued to WordPress using a handle that is made of a prefix
and the name passed to `::load()`. You can modify the prefix: 
```php
$prefix = 'my_plugin_asset_';
$base_path = WP_CONTENT_DIR . '/plugins/my-plugin/assets'; 
$loader = new Boxuk\BoxWpEditorTools\AssetLoader( $base_path, $prefix ); 
$loader->load( 'index' ); // handle will be `my_plugin_asset_index`
```

### Localisation or Additional Data
The loader can also inject any data in the JS context for your script to use. 
See the [WordPress Docs for `wp_localize_script`](https://developer.wordpress.org/reference/functions/wp_localize_script/) for more context. 

#### The object name
The default object-name is `{handle}Data`, so `AssetLoader::load('index')` would create
an object called `indexData`. You can filter this using the `localize_{handle}_data_object_name` filter. 
```php
add_filter( 
    'localize_index_data_object_name', 
    function ( string $default ): string { 
        return 'myObjectName'
    }
);
$loader = new AssetLoader(); 
$loader->load('index');
```

#### The Object Data
The object provided in the JS context can have it's data populated via a similar filter, `localise_{handle}_data`. 

```php
add_filter(
    'localise_index_data', 
    function ( array $default ): array { 
        return [ 
            'key' => 'value', 
        ];
    }
);

$loader = new AssetLoader(); 
$loader->load('index');
```

## Example Full Usage
Here's an example of a plugin using the asset loader in full.

```php

class MyPluginAssetManager { 

    private AssetLoader $asset_loader;

    public function __construct() { 
        $base_path = plugin_dir_path( __DIR__ ) . '/build/';
        $this->asset_loader = new AssetLoader( $base_path, 'my_plugin' );
    }

    public function init() { 
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin' ] );
        add_filter( 'localise_admin_data_object_name', [ $this, 'get_data_object_name' ] );
        add_filter( 'localise_admin_data', [ $this, 'get_admin_data' ] );
    }

    public function enqueue_frontend() { 
        $this->asset_loader->load( 'frontend' ); 
    }

    public function enqueue_admin() { 
        $this->asset_loader->load( 'admin' );
    }

    public function get_data_object_name() { 
        return 'myPluginData';
    }

    public function get_admin_data() { 
        return [ 
            'key' => 'value'
        ];
    }
}
```
