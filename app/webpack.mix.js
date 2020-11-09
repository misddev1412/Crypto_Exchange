const mix = require('laravel-mix');
// Backend JS/CSS
mix.js('resources/js/app.js', 'public/js').extract(['vue', 'lodash']);
mix.js('resources/js/language.js', 'public/js');

mix.sass('resources/sass/app.scss','public/css');
mix.sass('resources/sass/components/core/menu-builder.scss', 'public/css');
