var gulp = require('gulp');
var bump = require('gulp-bump');
var fs = require('fs');
var semver = require('semver');
let filter = require('gulp-filter');

var jsonFiles = [
    './bower.json',
    './package.json',
    './src/manifest.json'
];

var getPackageJson = function() {
    return JSON.parse(fs.readFileSync('./package.json', 'utf8'));
};

var saveVersion = function(version) {
    jsonFiles.map(jsonFile => {
        let package = JSON.parse(fs.readFileSync(jsonFile, 'utf-8'));
        package.version = version;
        fs.writeFileSync(jsonFile, JSON.stringify(package), 'utf-8')
    });
}

gulp.task('bump', ['bump.prerelease'], function() {

})

gulp.task('bump.minor', function() {
    bumpBuilder('minor');
});

gulp.task('bump.major', function() {
    bumpBuilder('major');
});

gulp.task('bump.patch', function() {
    bumpBuilder('patch');
});

gulp.task('bump.prerelease', function() {
    bumpBuilder('prerelease');
});

function bumpBuilder(type) {
    var pkg = getPackageJson();
    var newVer = semver.inc(pkg.version, type);
    saveVersion(newVer);
    var manifestFilter = filter(['manifest.json']);
    var regularJsons = filter(['!manifest.json']);
    return gulp.src(['./bower.json', './package.json', './src/manifest.json'])
        .pipe(bump({
            version: newVer
        }))
        .pipe(manifestFilter)
        .pipe(gulp.dest('./src'))
        // .pipe(manifestFilter.restore())
        .pipe(regularJsons)
        .pipe(gulp.dest('./'));
}