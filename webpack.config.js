var Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning(Encore.isProduction())

    // uncomment to define the assets of the project

    //.addStyleEntry('css/app', './assets/css/app.scss')
    .addStyleEntry('vendor/bootstrap/css/bootstrap.min', './assets/vendor/bootstrap/css/bootstrap.min.css')
    .addStyleEntry('vendor/font-awesome/css/font-awesome.min', './assets/vendor/font-awesome/css/font-awesome.min.css')
    .addStyleEntry('vendor/datatables/dataTables.bootstrap4', './assets/vendor/datatables/dataTables.bootstrap4.css')
    .addStyleEntry('css/sb-admin', './assets/css/sb-admin.css')

    // .addEntry('js/app', './assets/js/app.js')
    .addEntry('vendor/jquery/jquery.min', './assets/vendor/jquery/jquery.min.js')
    .addEntry('vendor/bootstrap/js/bootstrap.bundle.min', './assets/vendor/bootstrap/js/bootstrap.bundle.min.js')
    .addEntry('vendor/jquery-easing/jquery.easing.min', './assets/vendor/jquery-easing/jquery.easing.min.js')
    .addEntry('vendor/chart.js/Chart.min', './assets/vendor/chart.js/Chart.min.js')
    .addEntry('vendor/datatables/jquery.dataTables', './assets/vendor/datatables/jquery.dataTables.js')
    .addEntry('vendor/datatables/dataTables.bootstrap4', './assets/vendor/datatables/dataTables.bootstrap4.js')
    .addEntry('js/sb-admin.min', './assets/js/sb-admin.min.js')
    .addEntry('js/sb-admin-datatables.min', './assets/js/sb-admin-datatables.min.js')
    .addEntry('js/sb-admin-charts.min', './assets/js/sb-admin-charts.min.js')

    // uncomment if you use Sass/SCSS files
    //.enableSassLoader()

    // uncomment for legacy applications that require $/jQuery as a global variable
    .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
