# Template Persistence
One of the biggest annoyances about developing block-based themes is that if you modify a template (ie `archive.html` or `single.html`) in the block editor in the browser, you'd need to migrate that change to the HTML file to be added to version control. By default, WordPress saves the changes you make to the database which then overrides any subsequent changes you make to the HTML file. 

This change saves your edits to templates to their respective `.html` file. This applies to all `wp_template`, so anything accessed via **Templates** in the site-editor. 

## Usage
Template persistence is enabled automatically, but can be disabled by passing a value to the `boxuk_disable_template_persistence` filter: 

```php
add_filter( 'boxuk_disable_template_persistence', '__return_true' );
```

Since local environments can vary so much, we have not included any checks in the template persistence class to validate which environment is used. See [docs for `wp_get_environment_type()`](https://developer.wordpress.org/reference/functions/wp_get_environment_type/) for the above example code. 

```php
add_filter( 
    'boxuk_disable_template_persistence'
    function ( bool $default ): bool { 
        return wp_get_environment_type() === 'local';
    }
);
```
