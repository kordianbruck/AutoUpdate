<?php

//Edit these values to fit your needs
define('PATH_REPOSITORY', '/var/www/git-repo/'); //With trailing slash
define('PATH_PUBLIC', '/var/www/public_html/'); //With trailing slash
define('GIT_BRANCH', 'master'); //Use this branch as your development branch and only update this branch
define('GITHUB_SECRET', ''); //Set your secret here in order to verify that requests come from Github
//Do not edit below

//Handle incoming github webhooks
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Verify Github signature
    $requestBody = verifyGithub();

    //@todo check branch in payload
    
    //
    if (!empty($requestBody)) {
        $cmds = [
            'cd ' . PATH_REPOSITORY,
            'git checkout ' . GIT_BRANCH,
            'git reset --hard HEAD',
            'git pull',
            'yes | cp -au ' . PATH_REPOSITORY . '* ' . PATH_PUBLIC
        ];
        $x = shell_exec(implode(' 2>&1 && ', $cmds) .' 2>&1 ');
    }
}


//Page is called in browser
if (function_exists('exec')) {
    echo "exec is enabled";
}
if (function_exists('shell_exec')) {
    echo "shell_exec is enabled";
}
if (!is_writable(PATH_REPOSITORY)) {
    echo 'Repository path is not writeable';
}
if (!is_writable(PATH_REPOSITORY . '.git')) {
    echo 'Repository path does not contain a git repository';
}
if (!is_writable(PATH_PUBLIC)) {
    echo 'Public path is not writeable';
}

function getPayload() {
    if (!isset($_POST['payload'])) {
        return file_get_contents('php://input');
    } else {
        return json_encode($_POST['payload']);
    }
}

function verifyGithub() {
    $headers = getallheaders();
    $hubSignature = $headers['X-Hub-Signature'];

    // Split signature into algorithm and hash
    list($algo, $hash) = explode('=', $hubSignature, 2);

    // Get payload
    $payload = getPayload();

    // Calculate hash based on payload and the secret
    $payloadHash = hash_hmac($algo, $payload, GITHUB_SECRET);

    // Check if hashes are equivalent
    if ($hash !== $payloadHash) {
        // Kill the script or do something else here.
        die('Bad secret');
    }

    // Your code here.
    return json_decode($payload);
}
