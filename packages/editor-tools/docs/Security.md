# Security

Some security hardening features are available for you to use. 

## Usage
The security hardening features will load automatically with sensible defaults, but there's a number of filters you can use to configure. 

## Filters

| Filter Name | Description | Default Value |
| ----------- | ----------- | ------------- |
| `boxuk_prevent_author_enum` | Prevents access to the author-archive pages | `true` |
| `boxuk_prevent_author_rest_endpoint` | Prevents access to the author API endpoint | `true` |
| `boxuk_send_no_sniff_headers` | Sends `nosniff` and `frame_options` headers | `true` |
| `boxuk_remove_vip_headers` | Removes `X-Hacker` and `X-Powered-By`  headers | `true` |
| `boxuk_validate_password` | Enforces strong password validations | `true` |
| `boxuk_restrict_http_request_methods` | Restricts HTTP methods to the known list | `true` |
| `boxuk_disable_rss` | Disables the RSS functionality | `true` |
| `boxuk_modify_session_timeout` | Modifies the default session period to 10 hours | `true` |
| `boxuk_restrict_user_creation` | Restricts creating users in the admin interface | `false` |
| `boxuk_restrict_login_by_username` | Restricts logging in by username (allows by email only) | `true` |


Filters should be added at the earliest possible point, so avoid adding via a hook (ie don't add to `init` you might be too late). WordPress includes helpful return true/false methods to make it super easy to configure. 

```php
add_filter( 'example_filter', '__return_true' );
add_filter( 'example_filter', '__return_false' );
```
