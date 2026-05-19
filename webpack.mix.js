const mix = require('laravel-mix');

mix.webpackConfig(
    {
        stats: {
            children: true,
        }
    }
);

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css').sass('resources/sass/main.scss', 'public/css').sourceMaps();
