<?php

session_start();

include_once 'lib/helpers.php';

checkMainConfig();

include_once 'lib/config.php';

header('Content-type:application/json;charset=utf-8');

if (empty($_POST)) {
    echo json_encode(['status' => 0, 'msg' => 'No POST value']);
    exit;
}

// ajax sections
$sectionMapper = [
    'check-login' => 'checkLogin',
    'logout' => 'makeLogout',

    'dismiss-nginx-reload' => 'dismissNginxReload',

    'get-home-stats' => 'getHomeStats',
    'recheck-home-stats' => 'recheckHomeStats',

    'disable-domain' => 'disableDomain',
    'enable-domain' => 'enableDomain',
    'create-domain' => 'createDomain',
    'change-conf-content' => 'changeConfContent',

    'delete-template' => 'deleteTemplate',
    'create-template' => 'createTemplate',
    'change-template-content' => 'changeTemplateContent',

    'change-main-config-content' => 'changeMainConfigContent',
];

if (!isset($sectionMapper[$_POST['section']])) {
    echo json_encode(['status' => 0, 'msg' => 'Invalid request']);
    exit;
} else {
    $sectionMapper[$_POST['section']]();
}


/*
 * FUNCTIONS
 */

function needsUserLogin()
{
    // check if user not logged in
    if (empty($_SESSION['uname'])) {
        echo json_encode(['status' => 0, 'msg' => 'Unauthorized']);
        exit;
    }
}


function checkLogin()
{
    $username = strtolower($_POST['username']);
    $password = $_POST['password'];

    if ($username && $password) {
        $usersDirPath = realpath(dirname(__FILE__).'/users');
        $userFiles = array_filter(glob($usersDirPath.'/*'), 'is_file');

        $usersInDir = [];
        foreach ($userFiles as $userFile) {
            $usernameFilename = end(explode('/', $userFile));
            $content = `head -n1 {$userFile}`;

            $usersInDir[$usernameFilename] = $content;
        }

        if (!isset($usersInDir[$username]) || $usersInDir[$username] != $password) {
            echo json_encode(['status' => 0, 'msg' => 'Invalid credentials']);
            exit;
        }

        $_SESSION['uname'] = $username;
        echo json_encode(['status' => 1, 'msg' => 'Login successful']);
        exit;
    } else {
        echo json_encode(['status' => 0, 'msg' => 'Invalid credentials']);
        exit;
    }
}


function makeLogout()
{
    session_destroy();
    echo json_encode(['status' => 1, 'msg' => 'Successfully logged out']);
    exit;
}


function dismissNginxReload()
{
    needsUserLogin();

    unset($_SESSION['need_nginx_reload']);
}


function getHomeStats()
{
    if (
        !isset($_SESSION['homeStatsLastBuilt']) ||
        !isset($_SESSION['homeStats']) ||
        ((time() - $_SESSION['homeStatsLastBuilt']) >= 300)
    ) {
        $_SESSION['homeStatsLastBuilt'] = time();
        $_SESSION['homeStats'] = statsBuilder();
    }

    $serverTime = date('H:i:s');
    $lastUpdate = date('H:i:s', $_SESSION['homeStatsLastBuilt']);

    echo json_encode(['status' => 1, 'results' => $_SESSION['homeStats'], 'serverTime' => $serverTime, 'lastUpdate' => $lastUpdate]);
    exit;
}


function recheckHomeStats()
{
    $_SESSION['homeStatsLastBuilt'] = time();
    $_SESSION['homeStats'] = statsBuilder();

    $serverTime = date('H:i:s');
    $lastUpdate = date('H:i:s', $_SESSION['homeStatsLastBuilt']);

    echo json_encode(['status' => 1, 'results' => $_SESSION['homeStats'], 'serverTime' => $serverTime, 'lastUpdate' => $lastUpdate]);
    exit;
}


function disableDomain()
{
    needsUserLogin();

    if ($fname = $_POST['fname']) {

        $filename = base64_decode($fname);
        if (NGM_CONF_STYLE == 'source') {
            $filePath = NGM_CONFDIR_SOURCE.sanitize($filename);
            $newFilePath = rtrim($filePath, '.conf').'.disabled';
        } elseif (NGM_CONF_STYLE == 'apache') {
            $filePath = NGM_CONFDIR_ENABLED.sanitize($filename);
        }

        if (!is_file($filePath)) {
            echo json_encode(['status' => 0, 'msg' => 'Domain file not found']);
            exit;
        }

        if (NGM_CONF_STYLE == 'source') {
            $disable = `mv {$filePath} {$newFilePath}`;
        } elseif (NGM_CONF_STYLE == 'apache') {
            $disable = `rm {$filePath}`;
        }

        $_SESSION['need_nginx_reload'] = true;

        echo json_encode(['status' => 1, 'msg' => 'Domain has been disabled']);
        exit;
    } else {
        echo json_encode(['status' => 0, 'msg' => 'Domain parameter missing']);
        exit;
    }
}


function enableDomain()
{
    needsUserLogin();

    if ($fname = $_POST['fname']) {

        $filename = base64_decode($fname);
        if (NGM_CONF_STYLE == 'source') {
            $filePath = NGM_CONFDIR_SOURCE.sanitize($filename);
            $newFilePath = rtrim($filePath, '.disabled').'.conf';
        } elseif (NGM_CONF_STYLE == 'apache') {
            $filePath = NGM_CONFDIR_AVAILABLE.sanitize($filename);
            $symFilePath = NGM_CONFDIR_ENABLED.sanitize($filename);
        }

        if (!is_file($filePath)) {
            echo json_encode(['status' => 0, 'msg' => 'Domain file not found']);
            exit;
        }

        if (NGM_CONF_STYLE == 'source') {
            $enable = `mv {$filePath} {$newFilePath}`;
        } elseif (NGM_CONF_STYLE == 'apache') {
            $enable = `ln -s {$filePath} {$symFilePath}`;
        }

        $_SESSION['need_nginx_reload'] = true;

        echo json_encode(['status' => 1, 'msg' => 'Domain has been enabled']);
        exit;
    } else {
        echo json_encode(['status' => 0, 'msg' => 'Domain parameter missing']);
        exit;
    }
}


function createDomain()
{
    needsUserLogin();

    $filename = $_POST['new_domain_name'];
    $templateName = $_POST['new_domain_template'];

    if ($filename) {

        $fileNameFull = sanitize($filename).'.conf';
        if (NGM_CONF_STYLE == 'source') {
            $filePath = NGM_CONFDIR_SOURCE.$fileNameFull;
        } elseif (NGM_CONF_STYLE == 'apache') {
            $filePath = NGM_CONFDIR_AVAILABLE.$fileNameFull;
            $symFilePath = NGM_CONFDIR_ENABLED.$fileNameFull;
        }

        if (is_file($filePath)) {
            echo json_encode(['status' => 0, 'msg' => 'Domain file already exists']);
            exit;
        }

        if ($templateName) {
            $templatePath = realpath('templates').'/'.sanitize($templateName);
            $templateContent = file_get_contents($templatePath);
        } else {
            $templateContent = '';
        }

        // remove BOM bytes if content contains
        $templateContent = str_replace("\xEF\xBB\xBF", '', $templateContent);

        if (NGM_CONF_STYLE == 'source') {
            file_put_contents($filePath, $templateContent);
        } elseif (NGM_CONF_STYLE == 'apache') {
            file_put_contents($filePath, $templateContent);
            $enable = `ln -s {$filePath} {$symFilePath}`;
        }

        $redirectTo = '/?page=domains&fname='.base64_encode($fileNameFull);

        $_SESSION['need_nginx_reload'] = true;

        echo json_encode(['status' => 1, 'msg' => 'Domain created', 'redirectTo' => $redirectTo]);
        exit;
    } else {
        echo json_encode(['status' => 0, 'msg' => 'Domain name or template missing']);
        exit;
    }
}


function changeConfContent()
{
    needsUserLogin();

    $fname = $_POST['fname'];
    $newContent = $_POST['conf_content'];

    if ($fname && $newContent) {

        $filename = base64_decode($fname);
        if (NGM_CONF_STYLE == 'source') {
            $filePath = NGM_CONFDIR_SOURCE.sanitize($filename);
        } elseif (NGM_CONF_STYLE == 'apache') {
            $filePath = NGM_CONFDIR_AVAILABLE.sanitize($filename);
        }

        if (!is_file($filePath)) {
            echo json_encode(['status' => 0, 'msg' => 'Domain file not found']);
            exit;
        }

        // remove BOM bytes if content contains
        $newContent = str_replace("\xEF\xBB\xBF", '', $newContent);

        file_put_contents($filePath, $newContent);

        $_SESSION['need_nginx_reload'] = true;

        echo json_encode(['status' => 1, 'msg' => 'Domain conf file has been saved']);
        exit;
    } else {
        echo json_encode(['status' => 0, 'msg' => 'Domain parameter or content missing']);
        exit;
    }
}


function deleteTemplate()
{
    needsUserLogin();

    if ($fname = $_POST['fname']) {

        $filename = base64_decode($fname);
        $filePath = realpath('templates').'/'.sanitize($filename);

        if (!is_file($filePath)) {
            echo json_encode(['status' => 0, 'msg' => 'Template file not found']);
            exit;
        }

        unlink($filePath);

        echo json_encode(['status' => 1, 'msg' => 'Template file deleted']);
        exit;
    } else {
        echo json_encode(['status' => 0, 'msg' => 'Template parameter missing']);
        exit;
    }
}


function createTemplate()
{
    needsUserLogin();

    $newName = $_POST['new_template_name'];
    $newContent = $_POST['new_template_content'];

    if ($newName && $newContent) {

        $filename = rtrim($newName, '.stub');
        $filePath = realpath('templates').'/'.sanitize($filename);

        if (is_file($filePath)) {
            echo json_encode(['status' => 0, 'msg' => 'Template file already exists']);
            exit;
        }

        // remove BOM bytes if content contains
        $newContent = str_replace("\xEF\xBB\xBF", '', $newContent);

        file_put_contents($filePath.'.stub', $newContent);

        echo json_encode(['status' => 1, 'msg' => 'Template created']);
        exit;
    } else {
        echo json_encode(['status' => 0, 'msg' => 'Template parameter or content missing']);
        exit;
    }
}



function changeTemplateContent()
{
    needsUserLogin();

    $fname = $_POST['fname'];
    $newContent = $_POST['template_content'];

    if ($fname && $newContent) {

        $filename = base64_decode($fname);
        $filePath = realpath('templates').'/'.sanitize($filename);

        if (!is_file($filePath)) {
            echo json_encode(['status' => 0, 'msg' => 'Template file not found']);
            exit;
        }

        // remove BOM bytes if content contains
        $newContent = str_replace("\xEF\xBB\xBF", '', $newContent);

        file_put_contents($filePath, $newContent);

        echo json_encode(['status' => 1, 'msg' => 'Template file has been saved']);
        exit;
    } else {
        echo json_encode(['status' => 0, 'msg' => 'Template parameter or content missing']);
        exit;
    }
}


function changeMainConfigContent()
{
    needsUserLogin();

    $fname = $_POST['fname'];
    $newContent = $_POST['main_config_content'];

    if ($fname && $newContent) {

        $filename = base64_decode($fname);
        $filePath = NGM_CONFDIR_MAIN.sanitize($filename);

        if (!is_file($filePath)) {
            echo json_encode(['status' => 0, 'msg' => 'Config file not found']);
            exit;
        }

        // remove BOM bytes if content contains
        $newContent = str_replace("\xEF\xBB\xBF", '', $newContent);

        file_put_contents($filePath, $newContent);

        $_SESSION['need_nginx_reload'] = true;

        echo json_encode(['status' => 1, 'msg' => 'Config file has been saved']);
        exit;
    } else {
        echo json_encode(['status' => 0, 'msg' => 'Config parameter or content missing']);
        exit;
    }
}
