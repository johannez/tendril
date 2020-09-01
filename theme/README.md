# Timber based Starter Theme


## Timber
[Timber](https://github.com/timber/timber) is a great helper library for Wordpress that allows you to use [Twig](https://twig.symfony.com) as a templating engine instead of the usual PHP files. Tendril makes use of the Timber libraries. The `functions.php` or a plugin's php file is a good place to define the Config Class, which contains basic site configuration that can be easily overriden. You also want to use the `Config::registerPostType()` and `Config::registerBlock()` functions for your custom definitions. The Site class should be instantiated and customized to your needs in the `functions.php`.

## Laravel Mix
This theme uses [Laravel Mix](https://github.com/JeffreyWay/laravel-mix) as the frontend build system. It's a wrapper around Webpack and has great support in the Laravel community. You can customize the build process in `webpack.mix.js`.

## Tailwind CSS
[Tailwind CSS](https://tailwindcss.com) is a utility class framework, which makes theming a breeze with minimal SASS file coding. You can customize the fonts, colors, sizes, etc. in `tailwind.config.js`. In the `resources/sass` folder you can customize the global CSS styles and define your own utility class. The `resources/components` folder is meant for custom components that are used throughout the site. Alternatively, you can define your components in separate Twig files.

## VueJS
[VueJS](https://vuejs.org) is a popular Javascript framework that allows you to create component files to make your site more interactive. The theme's Laravel Mix is already configured for VueJS and you can put your components into `resources/js/components`, which then should be loaded automatically through the `app.js`
