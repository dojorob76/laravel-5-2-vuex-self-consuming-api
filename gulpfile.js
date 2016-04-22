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
            paths.bootstrap + 'javascripts/bootstrap/alert.js', // <- For Bootstrap Alerts ONLY
            paths.bootstrap + 'javascripts/bootstrap/modal.js', // <- For Bootstrap Modals ONLY
            paths.bootstrap + 'javascripts/bootstrap/dropdown.js', // <- For Bootstrap Dropdowns ONLY
            paths.bootstrap + 'javascripts/bootstrap/collapse.js', // <- For Bootstrap Collapse ONLY
            paths.bootstrap + 'javascripts/bootstrap/tooltip.js', // <- For Bootstrap Collapse ONLY
            //paths.bootstrap + 'javascripts/bootstrap.js', // <- Remove above and uncomment this for ALL Bootstrap JS
            paths.vendor + 'js/cookies.js',
            'custom/**'
        ])

        .styles([
            paths.vendor + 'css/animate.css',
            './public/css/app.css'
        ])

        .version(['css/all.css', 'js/all.js']);
});
