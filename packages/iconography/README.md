# Box WordPress Iconography Support

Add the ability to add icons inline to your content in the WordPress block editor. 

## Quick Start!
~Add this plugin to your WordPress site and off you go! If you're using composer, then
it's as simple as `composer require boxuk/wp-iconography`.~

We need to setup packaging this as a plugin to be distrubutable, so watch this space - for now you'll need to include this repo and build the assets yourself using `npm run build`. 

## Contributing
Working on the repo requires packaging this into a functioning WordPress installation. A ready-to-go solution is yet to be developed so a PR is welcome, preferrably where Docker is the only dependancy. 

This package doesn't necessarily require composer dependancies to be installed, so `npm install` and `npm run start` should get you started. 

The composer dependancies include [WordPress Stubs](https://github.com/php-stubs/wordpress-stubs), so your IDE should automatically include type information for all WP core functions if you need to edit the PHP file. If they're not, it's likely a mis-configuration of your IDE. There's helper guides in the WordPress Stubs repo. 
