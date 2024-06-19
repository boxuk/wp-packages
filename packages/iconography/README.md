# Box WordPress Iconography Support

Add the ability to add icons inline to your content in the WordPress block editor.

## Quick Start!
Add this plugin to your WordPress site and include a configuration file and it's ready to use!

If you're using composer, then it's as simple as `composer require boxuk/wp-iconography`.

## Configuration

Configuration files are based on a schema that allows you to quickly define the configuration.
They're loaded automatically from `wp-content/themes/{theme_name}/icons/*.config.json`, or you can define any custom paths using the filter available:
```php
add_filter(
	'boxuk_iconography_files',
	function ( $config_files ) {
		$config_files['example'] = __DIR__ . '/config/example.config.json';
		return $config_files;
	}
);
```

There's also example configuration files included in the plugin, so if you want to load **Material Symbols** you can just load the config included using this snippet:
```php
add_filter(
	'boxuk_iconography_files',
	function ( $config_files ) {
		$plugin_dir = WP_CONTENT_DIR . '/mu-plugins/wp-iconography'; // make sure this is valid for your project!


		// Remove any unnecessary
		$config_files['material-symbols-outlined']        = $plugin_dir . '/config/material-symbols-outlined.config.json';
		$config_files['material-symbols-outlined-filled'] = $plugin_dir . '/config/material-symbols-outlined-filled.config.json';
		$config_files['material-symbols-sharp']           = $plugin_dir . '/config/material-symbols-sharp.config.json';
		$config_files['material-symbols-sharp-filled']    = $plugin_dir . '/config/material-symbols-sharp-filled.config.json';
		$config_files['material-symbols-rounded']         = $plugin_dir . '/config/material-symbols-rounded.config.json';
		$config_files['material-symbols-rounded-filled']  = $plugin_dir . '/config/material-symbols-rounded-filled.config.json';
		return $config_files;
	}
);
```

There's loads of filters available to change the way icons load, so feel free to look through the source code at the available filters. And if there's function that doesn't work for you, PRs are always welcome!

## Configuration Schema

The Schema for configuration includes:
 - **Title** - the name output in the editor.
 - **Name** - the unique key/name for the icon set. I'd recommend `namespace/name` format.
 - **Tag Name** - this is the HTML tag that will be used to generate the output for the icon.
 - **Class Name** - this is used to generate the output for the icon
 - **URL** - the CSS file to load
 - **Additional CSS** - any inline CSS to include that might be relevant.
 - **Icons** - an array of:
	- **Label** - the value to use in the admin interface. Used for searches and is displayed on hover.
	- **Content** - the content inside the HTML generated.

Sample JSON file:
```json
{
	"title": "Example",
	"name": "boxuk/example",
	"tagName": "span",
	"className": "boxuk-icon-example",
	"url": "...",
	"additionalCSS": ".boxuk-icon-example { font-weight: 700; }",
	"icons": [
		{ "label": "Example", "content": "example-content" }
	]
}
```

Generated output:
```html
<span class="boxuk-icon-example">example-content</span>
```

## Contributing
Please do not submit any Pull Requests here. They will be closed.
---

Please submit your PR here instead: https://github.com/boxuk/wp-packages

This repository is what we call a "subtree split": a read-only subset of that main repository.
We're looking forward to your PR there!
