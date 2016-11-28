var elixir = require('laravel-elixir');

/* require('laravel-elixir-vueify');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 */

elixir(function(mix) {
    mix.sass([
        "app.scss"
    ])
        .styles([
        "libs/bootstrap.min.css",
        "libs/bootstrap-editable.css",
        "libs/bootstrap-material-design.css",
        "libs/ripples.css",
        "libs/select2.min.css",
        "libs/datatables.min.css",
        "libs/bootstrap-datetimepicker.min.css",
        "partials/snow.css"
        ])

        .copy('node_modules/chart.js/dist/Chart.min.js', 'public/js/chart.min.js')
        .copy('node_modules/font-awesome/fonts', 'public/fonts')

        .scripts([
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

    /* mix.browserify('main.js');
     mix.browserSync({ proxy: 'http://frost.com' }); */
});
