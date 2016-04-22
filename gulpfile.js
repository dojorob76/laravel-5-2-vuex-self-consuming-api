var elixir = require('laravel-elixir');

var paths = {
    'bower': './vendor/bower_components/',
    'vendor': './resources/assets/vendor/',
    'bootstrap': './vendor/bower_components/bootstrap-sass/assets/'
};

elixir(function (mix) {
    mix.sass('app.scss')

        .copy(paths.bootstrap + 'fonts/bootstrap/**', 'public/fonts')

        .scripts([
            paths.bower + 'jquery/dist/jquery.js',
            paths.bootstrap + 'javascripts/bootstrap.js',
            paths.vendor + 'js/cookies.js',
            'custom/**'
        ])

        .styles([
            paths.vendor + 'css/animate.css',
            './public/css/app.css'
        ])

        .version(['css/all.css', 'js/all.js']);
});
