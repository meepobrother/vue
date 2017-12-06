var gulp = require('gulp');
var bump = require('gulp-bump');

gulp.task('bump', function() {
    gulp.src('../*.json')
        .pipe(bump({
            version: '1.2.3',
            type: 'major'
        }))
        .pipe(gulp.dest('./'));
});