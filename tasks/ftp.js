var gulp = require('gulp');
var sftp = require('gulp-sftp');
var ssh = require('gulp-ssh');

gulp.task('ftp', ['ftp.delete'], function() {
    console.log('正在上传');
    return gulp.src('addons/imeepos_runnerpro/**/*')
        .pipe(sftp({
            host: '121.42.158.224',
            port: '22',
            user: 'root',
            pass: 'Imeepos1989.',
            remotePath: '/data/wwwroot/meepo.com.cn/addons/imeepos_runnerpro/'
        }));
});

var gulpSSH = new ssh({
    ignoreErrors: false,
    sshConfig: {
        host: '121.42.158.224',
        port: 22,
        username: 'root',
        password: 'Imeepos1989.',
    }
});
gulp.task('ftp.delete', () => {
    return gulpSSH.shell([`rm -rf /data/wwwroot/meepo.com.cn/addons/imeepos_runnerpro/ && mkdir /data/wwwroot/meepo.com.cn/addons/imeepos_runnerpro/`])
        .pipe(gulp.dest('config/logs'));
});