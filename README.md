# Tendril
Wordpress site building with Timber and TailwindCSS

## Installation

1. Go to your existing Wordpress site and create a new composer.json file in your root folder. [Example](https://gist.github.com/johannez/be711a101ac2e2d98c58c925579c05ae)
2. Create `src\SITE` folder in your root folder. This is where all your site specific classes (post type definitions, blocks, commands, migrations, etc.) belong.
3. Run `composer install` to get PHP dependencies
4. Put this snippet into `wp-config.php` or `functions.php` or a custom plugin to load the composer libraries
```
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
  require_once(__DIR__ . '/vendor/autoload.php');
}
```
5. Run `composer run theme` to install the starter theme
6. Activate the theme in Wordpress. For more information on the theme have a look at the README.md in the theme directory.

