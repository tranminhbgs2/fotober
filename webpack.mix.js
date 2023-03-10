let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
// mix.sass('resources/assets/sass/app.scss', 'public/css');

// mix.js('resources/assets/js/app.js', 'public/js');
mix.js('resources/assets/js/notification.js', 'public/js');
mix.js('resources/assets/js/chat.js', 'public/js');

/*
 |--------------------------------------------------------------------------
 | Version
 |--------------------------------------------------------------------------
 */

if (mix.inProduction() || process.env.npm_lifecycle_event !== 'hot') {
    mix.version();
}
