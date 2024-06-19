# Comment Disablement

This will remove the ability for comments from your WP installation. 

## Usage

Comments are disbaled by default, but can be enabled by modifing the `boxuk_disable_comments` filter:

```php
add_filter( 'boxuk_disable_comments', '__return_false' );
```
