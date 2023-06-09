const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

require('dotenv');

mix.js('resources/js/store.js', 'public/js');
mix.js('resources/js/main.js', 'public/js').vue().version();
mix.js('resources/js/charity.js', 'public/js').vue().version();
mix.js('resources/js/service-worker.js', 'public/');

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);