# Block Loader

The Block Loader will automatically load all blocks by looking for `block.json` files in a given path. These are loaded with `register_block_type`, see [WordPress Docs](https://developer.wordpress.org/reference/functions/register_block_type/) for more context. 

## Usage
Block loader is included automatically, and defaults to loading from your `wp-content/theme/THEME_NAME/build` folder. 
You can change this value using the `boxuk_block_loader_base_path` filter.

```php
add_filter(
    'boxuk_block_loader_base_path',
    function ( string $default_path ): string { 
        return __DIR__ . '/my-special-path';
    }
);
```
