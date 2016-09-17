var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 mix.copy('vendor/bower_components/jquery/dist/jquery.js', 'resources/assets/js/jquery.js');
 mix.copy('vendor/bower_components/bootstrap-sass/assets/javascripts/bootstrap.js', 'resources/assets/js/bootstrap.js');
 mix.copy('vendor/bower_components/select2/dist/js/select2.js', 'resources/assets/js/select2.js');
 mix.copy('vendor/bower_components/datatables/media/js/dataTables.bootstrap.js', 'resources/assets/js/datatables.js');
 mix.copy('vendor/bower_components/moment/moment.js', 'resources/assets/js/moment.js');
 mix.copy('vendor/bower_components/bootstrap-material-design/dist/bootstrap-material-design.umd.js', 'resources/assets/js/materials.js');
 mix.copy('vendor/bower_components/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js', 'resources/assets/js/datetimepicker.js');
 */

elixir(function(mix) {
    mix.sass("app.scss");
    mix.styles([
        "libs/bootstrap.min.css",
        "libs/bootstrap-editable.css",
        "libs/bootstrap-material-design.css",
        "libs/ripples.css",
        "libs/select2.min.css",
        "libs/datatables.min.css",
        "libs/bootstrap-datetimepicker.min.css",
        "partials/sidebar.css",
        "partials/snow.css",
        "partials/mods.css"
    ])

    mix.scripts([
        'jquery.min.js',
        'bootstrap.min.js',
        'moment.min.js',
        'select2.min.js',
        'datatables.min.js',
        'material.min.js',
        'bootstrap-datetimepicker.min.js',
        'ripples.min.js',
        'custom.js'
    ]);
    /* mix.browserSync({ proxy: 'http://frost.com' }); */
});
