#!/usr/bin/php
<?php

/**
 * usage
 *
 * php /opt/nginx-manager/init.php --generate="config"
 * php /opt/nginx-manager/init.php --generate="password" --username="username"
 * php /opt/nginx-manager/init.php --generate="all" --username="username"
 */

include_once 'lib/helpers.php';

include_once 'lib/ColoredBashPrinter.php';

$printer = new ColoredBashPrinter();

$params = getopt('', ['generate::', 'username::']);

if (PHP_SAPI !== 'cli') {
    echo $printer->getErrorString('ERR: cli only');
    exit;
}

if (empty($params['generate'])) {
    echo $printer->getErrorString('ERR: "generate" parameter missing');
    exit;
} else {
    if ($params['generate'] == 'config') {
        initConfigFile($printer);
    } elseif ($params['generate'] == 'password') {
        initCreateUserPassword($printer, $params);
    } else {
        initConfigFile($printer);
        initCreateUserPassword($printer, $params);
    }

    exit;
}

function initConfigFile($printer) {
    $filePath = dirname(__FILE__).'/lib/config.php';

    if (is_dir('/etc/nginx/sites-available')) {
        $style = 'apache';
    } else {
        $style = 'source';
    }

    if (!is_file($filePath)) {
        $content = <<<CONF
<?php

// '' is for default
define('NGM_PHP_VERS', ['', '5.6', '7.0', '7.1', '7.2', '7.3', '7.4']);

/*
 * styles
 *
 * "source": /etc/nginx/conf.d/
 * "apache": /etc/nginx/sites-available/ | /etc/nginx/sites-enabled/
 */
define('NGM_CONF_STYLE', '{$style}');

// it must be end with a trailing slash!
define('NGM_CONFDIR_MAIN', '/etc/nginx/');

// it must be end with a trailing slash!
define('NGM_CONFDIR_SOURCE', '/etc/nginx/conf.d/');

// it must be end with a trailing slash!
define('NGM_CONFDIR_AVAILABLE', '/etc/nginx/sites-available/');

// it must be end with a trailing slash!
define('NGM_CONFDIR_ENABLED', '/etc/nginx/sites-enabled/');

CONF;

        file_put_contents($filePath, $content);

        echo $printer->getInfoString('config file generated');
    } else {
        echo $printer->getErrorString('ERR: config file already exists');
    }
}

function initCreateUserPassword($printer, $params) {
    if (empty($params['username'])) {
        echo $printer->getErrorString('ERR: "username" parameter missing');
        exit;
    } else {
        $username = strtolower($params['username']);
    }

    $filePath = dirname(__FILE__).'/users/'.$username;

    if (!is_file($filePath)) {
        $randomPass = generateRandomString(32);

        file_put_contents($filePath, $randomPass, LOCK_EX);

        echo $printer->getInfoString('password for '.$username.': '.$randomPass);
    } else {
        echo $printer->getErrorString('ERR: user already exists');
    }
}
