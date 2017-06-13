<?php

namespace Deployer;

require 'recipe/common.php';

set('repository', 'https://github.com/fising/coffer.git');
set('git_tty', true);
set('shared_files', ['software/mac2222.md']);
set('shared_dirs', ['upload']);
set('writable_dirs', ['software']);
set('allow_anonymous_stats', false);
set('http_user', 'www');
set('deploy_path', '/data/apps/coffer');

desc('同步文件到生产服务器');
task('rsync', function () {
    run("rsync -av --exclude=shared --delete {{deploy_path}} root@127.0.0.1:/data/apps2/");
});

desc('本地预部署项目');
task('deploy', [
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'deploy:clear_paths',
    'rsync',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'rsync',
    'success'
]);

after('deploy:failed', 'deploy:unlock');
