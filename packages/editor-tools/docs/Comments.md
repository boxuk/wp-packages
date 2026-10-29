# Comment Disablement

This will remove the ability for comments from your WP installation. 

Comments and pings default to closed and remain closed when posts are saved. Comment counts report zero, post type support is removed on both front-end and admin requests. Direct access to discussion settings returns HTTP 403.

Editor JavaScript hides core comment blocks from the inserter. Explicit block allow-lists are also filtered to exclude comment blocks. Comment blocks already present in content, patterns or templates are not removed.

Comment REST routes return HTTP 404 except for authenticated editor Notes requests. The routes remain registered so Notes continue to work.

## Usage

Comments are disbaled by default, but can be enabled by modifing the `boxuk_disable_comments` filter:

```php
add_filter( 'boxuk_disable_comments', '__return_false' );
```
