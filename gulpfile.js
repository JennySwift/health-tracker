var elixir = require('laravel-elixir');

elixir(function(mix) {
    mix.sass('style.scss');

    mix.scripts([
        'plugins/*.js',
        'controllers/**/*.js',
        'factories/**/*.js'
    ], 'public/js/all.js');

    mix.stylesIn('resources/assets/css', 'public/css/plugins.css');
});
