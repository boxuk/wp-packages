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
