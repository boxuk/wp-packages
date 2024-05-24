# Comment Disablement

This will remove the ability for comments from your WP installation. 

## Usage
You should call the `init()` method as early as possible in your code. 
```php
add_action(
    'plugins_loaded', 
    function () { 
        ( new \Boxuk\BoxWpEditorTools\Comments() )->init();
    }
);
```
