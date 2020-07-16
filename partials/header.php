<?php

$header = '<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>'.(!empty($activePageDetails['title']) ? $activePageDetails['title'] : 'Page not found').' &bull; Nginx Manager</title>

    <meta name="pageslug" content="'.$selectedPage.'" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.css" rel="stylesheet">

    <style>
        html {position: relative; min-height: 100%;}
        body {padding-top: 56px; margin-bottom: 60px;}
        footer {position: absolute; bottom: 0; width: 100%; height: 60px; line-height: 60px; background-color: #f5f5f5;}

        .domains-list, .templates-list, .main-configs-list {max-height: 85vh; overflow-y: scroll;}
        .domains-list .list-group-item span {vertical-align: super;}

        @keyframes pulse {
            0% {transform: scale(1);}
            100% {transform: scale(1.6); opacity: 0;}
        }
        .rounded-pulse {width: 20px; height: 20px; border-radius: 50%; display: inline-block;}
        .rounded-pulse:before {content: ""; display: block; width: 20px; height: 20px; border-radius: 50%; animation: pulse 2s infinite;}
        .rounded-pulse:before:hover {background-color: darkblue;}
        .rounded-pulse.active-server {background-color: #17a2b8;}
        .rounded-pulse.active-server:before {background-color: #17a2b8;}
        .rounded-pulse.passive-server {background-color: #ffc107;}
        .rounded-pulse.passive-server:before {background-color: #ffc107; animation: none;}

        .rounded-pulse.medium-pulse, .rounded-pulse.medium-pulse:before {width: 30px; height: 30px;}

        .card {min-height: 125px;}
        .card-footer h5 {display: inline-block;}

        .navbar-brand span {vertical-align: middle;}
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="assets/img/nginx-manager.png" alt="logo" style="height: 32px;"> <span>Nginx Manager</span>
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">';

                if (!empty($uname)) {
                    foreach ($pages as $pageSlug => $page) {
                        if ($page['showInTop']) {
                            $header .= '
                                <li class="nav-item mr-3 '.($selectedPage == $pageSlug ? "active" : "").'">
                                    <a class="nav-link" href="/?page='.$pageSlug.'">'.$page['icon'].' '.$page['title'].'</a>
                                </li>';
                        }
                    }

                    $header .= '
                        <li class="nav-item ml-3"><a class="nav-link" id="logout-btn" href="javascript:;"><i class="fa fa-sign-out-alt"></i> '.$uname.'</a></li>';
                }

                $header .= '
                </ul>
            </div>
        </div>
    </nav>
';

return $header;
