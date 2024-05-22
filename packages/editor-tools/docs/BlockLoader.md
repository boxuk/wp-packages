# Block Loader

The Block Loader will automatically load all blocks by looking for `block.json` files in a given path. These are loaded with `register_block_type`, see [WordPress Docs](https://developer.wordpress.org/reference/functions/register_block_type/) for more context. 

## Usage
You should initialise the block-loader early, before `init`. 
```php
add_action(
    'plugins_loaded',
    function () { 
        $block_loader = new \Boxuk\BoxWpEditorTools\BlockLoader();
        $block_loader->init();
    }
);
```

## Arguments
Block loader accepts a base-path in it's constructor: 
```php
$base_path = plugin_dir_path( __FILE__ ) . '/build';
$block_loader = new \Boxuk\BoxWpEditorTools\BlockLoader( $base_path );
$block_loader->init();
```
The base-path defaults to `get_template_directory() . '/build'`. 