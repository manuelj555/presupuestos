var gulp = require('gulp');
var babel = require('gulp-babel');
var gulpif = require('gulp-if');
var gutil = require('gulp-util');
var concat = require('gulp-concat');
var sass = require('gulp-sass');
var uglify = require('gulp-uglify');
var uglifycss = require('gulp-uglifycss');
var browserify = require('browserify');
var babelify = require('babelify');
var vueify = require('vueify');
var source = require('vinyl-source-stream');
var buffer = require('vinyl-buffer');
var watchify = require('watchify');
var sourcemaps = require('gulp-sourcemaps');
var browserSync = require('browser-sync').create();
// var runSequence = require('run-sequence');

var config = {
    isProd: gutil.env.env == 'prod',
    resourcesPath: './app/Resources/assets/',
    targetPath: './web/build/',
    jsTarget: './web/build/js/',
    cssTarget: './web/build/css/',
    fontsTarget: './web/build/fonts/',
};

gulp.task('js', function () {
    var bundler = watchify(browserify({
        entries: config.resourcesPath + "js/main.js",
        paths: [
            './node_modules',
            config.resourcesPath + 'js',
            config.resourcesPath + 'vue',
        ],
        debug: true,
        cache: {},
        packageCache: {},
    }).transform(babelify, {
        presets: ['es2015'], plugins: ["transform-runtime"]
    }).transform(vueify));

    function rebundle() {
        return bundler.bundle().on('error', function (err) {
            err.stream = null; // Quitamos contenido no leible
            console.log('     #########  ERROR   ###########');
            console.log(err);
            this.emit('end');
        })
            .pipe(source("presupuesto.js"))
            .pipe(buffer())
            .pipe(gulpif(!config.isProd, sourcemaps.init({loadMaps: true})))
            .pipe(gulpif(config.isProd, uglify()))
            .pipe(gulpif(!config.isProd, sourcemaps.write('.')))
            .pipe(gulp.dest(config.jsTarget))
    }

    bundler.on('update', function () {
        rebundle();
        gutil.log('Compilando Archivos de Vue...');
    }).on('time', function (time) {
        gutil.log('Listo en: ', gutil.colors.cyan(time + ' ms'));
    })

    return rebundle();
});

gulp.task('js:jquery', function () {
    return gulp.src("node_modules/jquery/dist/jquery.min.js")
    // .pipe(gulpif(config.isProd, uglify()))
        .pipe(gulp.dest(config.jsTarget))
});

gulp.task('css', function () {
    var cssPrefixPath = config.resourcesPath + "css/"
    return gulp.src([
        'node_modules/bootstrap/dist/css/bootstrap.min.css',
        'node_modules/font-awesome/css/font-awesome.min.css',
        cssPrefixPath + '**/*.{css,scss}'
    ])
        .pipe(gulpif(!config.isProd, sourcemaps.init({loadMaps: true})))
        .pipe(gulpif('**/*.scss', sass.sync().on('error', sass.logError)))
        .pipe(concat('styles.css'))
        .pipe(gulpif(config.isProd, uglifycss()))
        .pipe(gulpif(!config.isProd, sourcemaps.write('.')))
        .pipe(gulp.dest(config.cssTarget))
        .pipe(browserSync.stream({match: '**/*.{css,scss}'}));
});

gulp.task('fonts', function () {
    return gulp.src([
        'node_modules/font-awesome/fonts/*',
        // 'app/Resources/assets/fonts/**/*'
    ])
        .pipe(gulp.dest(config.fontsTarget));
});

// gulp.task('watch', function () {
//     //gulp.watch(['**/*.{css,scss}'], { cwd: './app/Resources/assets/'}, ['css', 'admin:css']);
//     //gulp.watch(['**/*.{css,scss}'], { cwd: './web/css/'}, ['css', 'admin:css']);
//     gulp.watch([
//         'js/app.js',
//         'js/**/*.js',
//         'vue/**/*.vue',
//         'vue/**/*.js'
//     ], {cwd: './assets'}, ['js']);
// });

gulp.task('browser-sync', ['js'], function () {
    var server = gutil.env.server || 'presupuestos.local.com/app_dev.php'
    browserSync.init({
        proxy: server,
        ui: false,
    });

    gulp.watch(['**/*.{css,scss}'], {
        cwd: config.resourcesPath + 'css/'
    }, ['css']);

    gulp.watch([
        "app/Resources/views/**/*.html.twig",
    ]).on('change', browserSync.reload);
});

gulp.task('default', ['js', 'css']);
gulp.task('build', ['default', 'fonts']);

