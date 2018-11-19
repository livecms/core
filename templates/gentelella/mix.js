var themeName = path.basename(templateDir);

var Mix = require('laravel-mix');

Mix
.sass(templateDir+'/src/scss/main.scss', 'vendor/livecms/'+themeName+'/css')
.scripts([
    templateDir+'/vendors/jquery/dist/jquery.min.js',
    templateDir+'/vendors/bootstrap/dist/js/bootstrap.min.js',
    templateDir+'/vendors/bootstrap-notify/bootstrap-notify.min.js',
    templateDir+'/vendors/jquery-validation/jquery.validate.min.js',
    templateDir+'/vendors/fastclick/lib/fastclick.js',
    templateDir+'/vendors/nprogress/nprogress.js',
    templateDir+'/vendors/sweetalert2/dist/sweetalert2.min.js',
    templateDir+'/src/js/helpers/smartresize.js',
    templateDir+'/src/js/main.js'
], 'public/vendor/livecms/'+themeName+'/js/main.js')
.copy('public/vendor/livecms/'+themeName+'/css/main.css', templateDir+'/assets/css/main.css')
.copy('public/vendor/livecms/'+themeName+'/js/main.js', templateDir+'/assets/js/main.js');

var Datatables = require('laravel-mix');

Datatables
.sass(templateDir+'/src/scss/datatables.scss', 'vendor/livecms/'+themeName+'/css')
.scripts([
    templateDir+'/vendors/datatables-1.10.16/js/jquery.dataTables.min.js',
    templateDir+'/vendors/datatables-1.10.16/js/dataTables.bootstrap.min.js',
], 'public/vendor/livecms/'+themeName+'/js/datatables.js')
.copy('public/vendor/livecms/'+themeName+'/css/datatables.css', templateDir+'/assets/css/datatables.css')
.copy('public/vendor/livecms/'+themeName+'/js/datatables.js', templateDir+'/assets/js/datatables.js');
