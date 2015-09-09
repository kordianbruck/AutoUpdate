<?php

//Edit these values to fit your needs
define('PATH_REPOSITORY', '/var/www/git-repo/'); //With trailing slash
define('PATH_PUBLIC', '/var/www/public_html/'); //With trailing slash
define('GIT_BRANCH', 'master'); //Use this branch as your development branch and only update this branch

//Handle incoming github webhooks
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['payload'])) {
        $requestBody = json_decode(file_get_contents('php://input'));
    } else {
        $requestBody = $_POST['payload'];
    }

    if (isset($_POST['payload'])) {
        $x = shell_exec('cd ' . PATH_REPOSITORY . ' 2>&1 && git checkout ' . GIT_BRANCH . ' 2>&1 && git reset --hard HEAD 2>&1 && git pull 2>&1 && yes | cp -au ' . PATH_REPOSITORY . '* ' . PATH_PUBLIC . '  2>&1');
    }
}


//Page is called in browser
if (function_exists('exec')) {
    echo "exec is enabled";
}
if (function_exists('shell_exec')) {
    echo "shell_exec is enabled";
}
if(!is_writable(PATH_REPOSITORY)){
    echo 'Repository path is not writeable';
}
if(!is_writable(PATH_REPOSITORY.'.git')){
    echo 'Repository path does not contain a git repository';
}
if(!is_writable(PATH_PUBLIC)){
    echo 'Public path is not writeable';
}