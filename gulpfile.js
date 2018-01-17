const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');
require('laravel-elixir-minify-html');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(mix => {
    // .webpack('public.js', 'public/js/public.js')
    // .sass('public.scss', 'public/css/public.css')
    // 
    mix.sass('admin.scss', 'public/css/admin.css')
        .sass('myresumes.scss', 'public/css/myresumes.css')
        .sass('print.scss', 'public/css/print.css')
        .webpack('admin.js', 'public/js/admin.js')
        .webpack('role.js', 'public/js/role.js')
        .webpack('landing.js', 'public/js/landing.js')
        .webpack('company.js', 'public/js/company.js')
        .webpack('dashboard.js', 'public/js/dashboard.js')
        .webpack('questionnaire.js', 'public/js/questionnaire.js')
        .webpack('main-app.js', 'public/js/main-app.js')
        .browserSync({
            proxy: 'localhost/magisgit/resumeio_laravel/public',
        });

    if (elixir.config.production) {
        // mix.html('storage/framework/views/*', 'storage/framework/views/', {
        //         collapseWhitespace: true,
        //         conservativeCollapse: true,
        //         removeComments: true,
        //         minifyCSS: true,
        //         minifyJS: true,
        //     });

            // .version(['js/landing.js', 'js/company.js', 'js/questionnaire.js', 'js/main-app.js',
            //     'css/admin.css', 'css/myresumes.css', 'js/admin.js', 'js/role.js']);
    }
});
