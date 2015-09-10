<?php

if (!file_exists('Config.php')) {
    exit('Please configure this app first by copying the default config to Config.php');
}
include 'Config.php';

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
        $x = shell_exec(implode(' 2>&1 && ', $cmds) . ' 2>&1 ');
        if (!empty($x)) {
            echo 'Error encountered: ' . $x;
        }
    } else {
        echo 'No Payload data';
    }
} else {
    //Page is called in browser
    if (!function_exists('exec')) {
        echo "exec is not enabled";
    }
    if (!function_exists('shell_exec')) {
        echo "shell_exec is not enabled";
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
