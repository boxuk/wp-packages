# Editor Cleanup
This will hide some additional features added to the block editor: 
 - **Core Block Patterns**: WordPress provides a collection of patterns, but most themes will provide their own patterns and as such the WordPress provided patterns make finding your patterns much harder!
 - **Block Directory**: When inserting blocks, if the user has searched for a block that doesn't exist, WordPress helpfully searches the plugin directory for any plugins that might include that block. Mostly this is not desired behaviour on projects delivered for clients. 
 - **WordPress VIP Featured Plugins**: WordPress VIP adds a banner to highlight featured plugins for their platform. Plugins are managed by the developer so this should be hidden. 

## Usage
The `init` method should be hooked early in the WordPress Lifecycle
```php
add_action(
    'plugins_loaded', 
    function () { 
        ( new Boxuk\BoxWpEditorTools\EditorCleanup() )->init(); 
    }
);
```
