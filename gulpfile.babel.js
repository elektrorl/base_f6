'use strict';
import plugins from 'gulp-load-plugins';
import gulp from 'gulp';
import yaml from 'js-yaml';
import fs from 'fs';
import sftp from 'gulp-sftp';
// Load all Gulp plugins into one variable
const $ = plugins();
// Load settings from settings.yml
const {
  COMPATIBILITY,
  PATHS
} = loadConfig();

function loadConfig() {
  let ymlFile = fs.readFileSync('config.yml', 'utf8');
  return yaml.load(ymlFile);
}
// Build the "dist" folder by running all of the below tasks
gulp.task('build', gulp.series(gulp.parallel(sass, javascript)));
// Build the site, run the server, and watch for file changes
gulp.task('default', gulp.series('build', watch));
// Compile Sass into CSS
function sass() {
  return gulp.src('src/scss/app.scss').pipe($.sass({
    includePaths: PATHS.sass,
    outputStyle: 'compact'
  }).on('error', $.sass.logError)).pipe($.autoprefixer({
    browsers: COMPATIBILITY
  })).pipe(gulp.dest(PATHS.dist + 'css')).pipe(sftp(sftpOpts("css")));
}
// Combine JavaScript into one file
function javascript() {
  return gulp.src(PATHS.javascript).pipe($.babel()).pipe($.concat('app.js')).pipe($.uglify().on('error', e => {
    console.log(e);
  })).pipe(gulp.dest(PATHS.dist + 'js')).pipe(sftp(sftpOpts("js")));
}
//SFTP
function sftpOpts(path) {
  return {
    host: 'website.com',
    user: 'johndoe',
    pass: '1234'
    remotePath: "www/monthemeenligne/" + path
  };
}
// Watch for changes to static assets, pages, Sass, and JavaScript
function watch() {
  gulp.watch('src/scss/**/*.scss').on('all', gulp.series(sass));
  gulp.watch('src/js/**/*.js').on('all', gulp.series(javascript));
}