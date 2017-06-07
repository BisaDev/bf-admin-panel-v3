const {mix} = require('laravel-mix');
mix.browserSync('brightfoxv2.dev');

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

mix.js('resources/assets/js/app.js', 'public/js')
    .scripts([
        'public/vendor/detect.js',
        'public/vendor/waves.js',
        'public/vendor/wow.min.js',
        'public/vendor/fastclick.js',
        'public/vendor/jquery.slimscroll.js',
        'public/vendor/jquery.nicescroll.js',
        'public/vendor/jquery.scrollTo.min.js',
        'public/vendor/switchery.min.js',
        'public/vendor/jquery.core.js',
        'public/vendor/jquery.app.js',
        'public/vendor/bootstrap-inputmask.min.js'
    ], 'public/js/vendor.js')
    .less('resources/assets/less/app.less', 'public/css');

