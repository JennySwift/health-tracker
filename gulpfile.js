var elixir = require('laravel-elixir');

elixir(function(mix) {
    mix.sass('style.scss');

    // mix.scripts([
    //     'plugins/*.js',
    //     'config.js',
    //
    //     //'app.js',
    //     'controllers/**/*.js',
    //     'factories/**/*.js',
    //     'directives/**/*.js',
    //     'filters/**/*.js',
    //
    //     //'helpers.js',
    //     'repositories/**/*.js',
    //     'components/**/*.js',
    //     'app.js',
    //     //'routes.js'
    // ], 'public/js/all.js');

    // mix.version(["css/style.css", "js/all.js"]);
    mix.version(["css/style.css"]);

    //Copy css for medium-editor from node_modules to my css directory
    mix.copy('node_modules/medium-editor/dist/css/medium-editor.min.css', 'resources/assets/css/medium-editor.min.css');
    mix.copy('node_modules/sweetalert2/dist/sweetalert2.css', 'resources/assets/css/sweetalert2.css');

    mix.stylesIn('resources/assets/css', 'public/css/plugins.css');
});

