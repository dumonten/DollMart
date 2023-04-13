<?php

define('LINK',                           '<link rel="stylesheet" href={path-css}>');
define('MAIN_TEMPLATE_PATH',             './content/prime_template.html');
define('HEADER_PATH',                    './content/header_content.html');
define('HEADER_TOPNAV_STANDARD_PATH',    './content/header_topnav_standard.html');
define('HEADER_TOPNAVAUTH_PATH',         './content/header_topnav_authorization.html');
define('HEADER_TOPNAV_BASKET_PATH',      './content/header_topnav_basket.html');
define('FOOTER_PATH',                    './content/footer_content.html');

/*Possible types of header*/
enum Header {
    case Standard;
    case Authorization;
    case Basket;
    case None;
}

/*Possible types of footer*/
enum Footer {
    case Standard;
    case None;
}

function getStylesheetDefinitions($paths) {
    $links = array();
    foreach($paths as $path)
        $links[] = str_replace('{path-css}', $path, LINK);
    return implode(" ", $links);
}

function getPage($title, $content, $headerType, $footerType) {
    $main = file_get_contents(MAIN_TEMPLATE_PATH);

    $header = $topnav_content = "";
    switch ($headerType)
    {
        case Header::Standard:
            $header  = file_get_contents(HEADER_PATH);
            $topnav_content = file_get_contents(HEADER_TOPNAV_STANDARD_PATH);
            break;
        case Header::Authorization:
            $header  = file_get_contents(HEADER_PATH);
            $topnav_content = file_get_contents(HEADER_TOPNAVAUTH_PATH);
            break;
        case Header::None:
            break;
    }
    $header  = str_replace('{header-topnav-content}', $topnav_content, $header);

    $footer  = "";
    switch ($footerType)
    {
        case Footer::Standard:
            $footer  = file_get_contents(FOOTER_PATH);
            break;
        case Footer::None:
            break;
    }

    $main = str_replace('{title}',   $title,   $main);
    $main = str_replace('{header}',  $header,  $main);
    $main = str_replace('{content}', $content, $main);
    $main = str_replace('{footer}',  $footer,  $main);

    return $main;
}

function setCSS(&$page, $css_paths) {
    $stylesheets_adding = getStylesheetDefinitions($css_paths);
    $page = str_replace('{stylesheets_adding}', $stylesheets_adding, $page);
}

/*Sends a pop-up message to the client on a new page on top of the old one*/ 
function sendMessage($message) { 
    echo "<script>alert('$message');</script>";
}

function savePersonalData($text, $email, $password) {
    $file = fopen("bd.txt", "a") or die("Unable to open file!");
    fwrite($file, $text." ".$email." ".$password."\n");
    fclose($file);
}