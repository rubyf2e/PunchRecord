 let mix = require('laravel-mix');
 mix.browserSync({
 	port:5006,
 	proxy: 'PunchRecord',
 	plugins: ["bs-html-injector"],
 	online: true,
 	injectChanges: true,
 }).options({
 	extractVueStyles: true
 }).sourceMaps()
 .js('resources/assets/js/app.js', 'public/js').version().extract(['vue'])
 .sass('resources/assets/sass/app.scss', 'public/css', {
 	strictMath: true
 });
