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

//compile to storage
let storagePath = 'storage/app/mix/';
mix.setPublicPath(storagePath);

mix.js('resources/assets/js/app.js', storagePath);
mix.sass('resources/assets/sass/app.scss', storagePath);

//combine with vendors and move to public
mix.scripts([
    storagePath + 'app.js'
 ], 'public/js/app.js');

mix.copy(storagePath + 'app.css', 'public/css/');
