<?php

define('PATH_REPOSITORY', '/var/www/tum.sexy/git-repo/'); //With trailing slash
define('PATH_PUBLIC', '/var/www/tum.sexy/public_html/'); //With trailing slash

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['payload'])) {
        $requestBody = json_decode(file_get_contents('php://input'));
    } else {
        $requestBody = $_POST['payload'];
    }

    if (isset($_POST['payload'])) {
        $x = shell_exec('cd ' . PATH_REPOSITORY . ' 2>&1 && git reset --hard HEAD 2>&1 && git pull 2>&1 && yes | cp -au ' . PATH_REPOSITORY . '* ' . PATH_PUBLIC . '  2>&1');
    }
}



if (function_exists('exec')) {
    echo "exec is enabled";
}
if (function_exists('shell_exec')) {
    echo "shell_exec is enabled";
}