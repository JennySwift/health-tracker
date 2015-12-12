var elixir = require('laravel-elixir');

elixir(function(mix) {
    mix.sass('style.scss');

    mix.version("css/style.css");

    mix.scripts([
        'plugins/*.js',
        'app.js',
        'controllers/**/*.js',
        'factories/**/*.js',
        'directives/**/*.js',
        'filters/**/*.js'
    ], 'public/js/all.js');

    //mix.version("js/all.js");

    //mix.scripts([
    //    'jasmine/spec/*.js'
    //], 'public/js/jasmine/specs.js');
    //
    //mix.scripts([
    //    'jasmine/src/*.js'
    //], 'public/js/jasmine/src.js');

    mix.stylesIn('resources/assets/css', 'public/css/plugins.css');
});
