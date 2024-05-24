# Security

Some security hardening features are available for you to use. 

## Usage
There's a default set of configurations, so you can just need to call the `init()` method as early as possible. 
```php
add_action(
    'plugins_loaded',
    function() { 
        ( new Boxuk\BoxWpEditorTools\Security\Security() )->init(); 
    }
);
```

## Advanced Usage

The `init()` method of the `Security` class accepts a collection of boolean values to enable/disable features. They _all_ default to enabled, so you can pass `false` to disable any feature if it's causing you an issue. Alternatively, you can only initialise the required classes as needed. 
 - **Author Enumeration** - prevents the well-known author enumeration vector via the REST API. 
 - **Headers** - Adds frame options and no-sniff headers, along with removing som eheaders that VIP add to the response. 
 - **Password Validation** - adds a bunch of rules to enforce stronger passwords ( 10-72 characters, one upper, one lower, one number ). 
 - **User Sessions** - Prevents users from having multiple sessions logged in at one time. An option to set the maximum number of sessions is added to the Settings screen in the Admin Area. 
 - **HTTP Request Methods** - ensures that all HTTP requests are valid. 
 - **RSS** - prevents RSS feeds being generated from the content
 - **Session Timeouts** - Changes the default WP session to 10 hours (instead of 'session', ie PHP session cookie!) unless the user selects 'remember me'. 

For example: 
```php
add_action(
    'plugins_loaded',
    function() { 
        ( new Boxuk\BoxWpEditorTools\Security\Security() )->init(
            true, // author-enumeration
            true, // headers
            false, // password validation
            false, // concurrent user-sessions
            true, // HTTP request methods
            false, // RSS
            true // Session timeouts
        ); 
    }
);
```
