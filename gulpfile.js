var elixir = require('laravel-elixir');

var paths = {
    'bower': './vendor/bower_components/',
    'vendor': './resources/assets/vendor/'
};

elixir(function(mix) {
    mix.sass('app.scss')

        .copy(paths.bower + 'bootstrap-sass/assets/fonts/bootstrap/**', 'public/fonts')
        .copy(paths.bower + 'font-awesome/fonts/**', 'public/fonts')

        .scripts([
            paths.bower + 'jquery/dist/jquery.js',
            paths.vendor + 'js/cookies.js',
            'custom/app-globals.js'
        ])

        .styles([
            paths.bower + 'font-awesome/css/font-awesome.css',
            './public/css/app.css'
        ])

        .version(['css/all.css', 'js/all.js']);
});
