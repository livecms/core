var themeName = path.basename(templateDir);

var Mix = require('laravel-mix');

Mix
.sass(templateDir+'/src/scss/main.scss', 'vendor/livecms/'+themeName+'/css')
.scripts([
    templateDir+'/vendors/jquery/dist/jquery.min.js',
    templateDir+'/vendors/bootstrap/dist/js/bootstrap.min.js',
    templateDir+'/vendors/fastclick/lib/fastclick.js',
    templateDir+'/vendors/nprogress/nprogress.js',
    templateDir+'/vendors/sweetalert2/dist/sweetalert2.min.js',
    templateDir+'/src/js/helpers/smartresize.js',
    templateDir+'/src/js/main.js'
], 'public/vendor/livecms/'+themeName+'/js/main.js')
.copy('public/vendor/livecms/'+themeName+'/css/main.css', templateDir+'/assets/css/main.css')
.copy('public/vendor/livecms/'+themeName+'/js/main.js', templateDir+'/assets/js/main.js');
