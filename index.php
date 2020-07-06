<?php

session_start();

include_once 'lib/helpers.php';

checkMainConfig();

include_once 'lib/config.php';

// get selected page from url
$selectedPage = isset($_GET['page']) ? $_GET['page'] : 'home';

// if user not logged in, show login page
if (empty($_SESSION['uname'])) {
    $selectedPage = 'login';
} elseif ($selectedPage == 'login') {
    header('Location: /');
    exit;
} else {
    $uname = $_SESSION['uname'];
}

$pages = include_once 'lib/pages.php';

if (empty($pages[$selectedPage]) || !is_file($pages[$selectedPage]['file'])) {
    // if selected page not exist
    $currentPage['pageTitle'] = 'Page not found';
    $currentPage['pageBody'] = 'Page not found ('.$selectedPage.')';
} else {
    $activePageDetails = $pages[$selectedPage];
    $currentPage = include_once $activePageDetails['file'];
}

$header = include_once('partials/header.php');
$footer = include_once('partials/footer.php');


echo $header;
echo printAlerts();
echo $currentPage['pageBody'];
echo $footer;
