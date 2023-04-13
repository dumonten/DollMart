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

function cookiesAreSet() {
    if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) {
        $dbh = connectToDb('mysql:host=localhost;dbname=dolls', 'root',  '0001'); 
        if (!$dbh) exit();

        $sth = $dbh->prepare("SELECT * FROM `usersInformation` WHERE `User ID` = :cookieID");
        $sth->bindParam(':cookieID', $_COOKIE['id'], PDO::PARAM_STR);
        $sth->execute();
        
        $data = $sth->fetch(PDO::FETCH_ASSOC);
        if(($data['User hash'] != $_COOKIE['hash']) or ($data['User ID'] != $_COOKIE['id'])) {
            unsetCookies(); 
            return false; 
        }
        return true; 
    } else {
        return false; 
    }
}

function unsetCookies() {
    setcookie("id",   "", time() - 1);
    setcookie("hash", "", time() - 1);
}

function getStylesheetDefinitions($paths) {
    $links = array();
    foreach($paths as $path)
        $links[] = str_replace('{path-css}', $path, LINK);
    return implode(" ", $links);
}

function setCSS(&$page, $css_paths) {
    $stylesheets_adding = getStylesheetDefinitions($css_paths);
    $page = str_replace('{stylesheets_adding}', $stylesheets_adding, $page);
}

function getPage($title, $content, $headerType, $footerType) {
    $main = file_get_contents(MAIN_TEMPLATE_PATH);

    $header = $topnav_content = "";
    switch ($headerType)
    {
        case Header::Standard:
            $header  = file_get_contents(HEADER_PATH);
            $topnav_content = file_get_contents(HEADER_TOPNAV_STANDARD_PATH);
            if (cookiesAreSet())
                $topnav_content = str_replace('{user-page}', './basket.php', $topnav_content); 
            else
                $topnav_content = str_replace('{user-page}', './authorization.php', $topnav_content); 
            break;
        case Header::Authorization:
            $header  = file_get_contents(HEADER_PATH);
            $topnav_content = file_get_contents(HEADER_TOPNAVAUTH_PATH);
            break; 
        case Header::Basket:
            $header  = file_get_contents(HEADER_PATH);
            $topnav_content = file_get_contents(HEADER_TOPNAV_BASKET_PATH);
            break;
        case Header::None:
            break;
    }
    $header  = str_replace('{header-topnav-content}', $topnav_content, $header);
    if (cookiesAreSet())
        $header  = str_replace('{num-in-basket}', getNumInBasket(), $header);       
    else 
        $header  = str_replace('{num-in-basket}', '0', $header);

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

function connectToDb($dsn, $login, $password) {
    try {
        $dbh = new PDO($dsn, $login,  $password, 
                        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    }
    catch(PDOException $e) {
        echo "Error: data base isn't found."; 
        return null; 
    }
    return $dbh;
}

/*Getting the number of items in the cart to display on the home page*/
function getNumInBasket() : string {
    if(cookiesAreSet()) {
        $dbh = connectToDb('mysql:host=localhost;dbname=dolls', 'root',  '0001'); 
        if (!$dbh) exit();
        
        $sth = $dbh->prepare("SELECT * FROM `basketsInformation` WHERE `User ID` = :cookieID");    
        $sth->bindValue(':cookieID',  $_COOKIE['id'], PDO::PARAM_STR); 
        $sth->execute();
        
        $products = $sth->fetchAll(PDO::FETCH_ASSOC);     
        $total = 0; 
        foreach ($products as $product)
            $total += intval($product['Num of product']); 
        return strval($total);
    } 
    return "0"; 
}

/*Returns the email of the currently logged in user*/
function getUserEmail() : string {
    $dbh = connectToDb('mysql:host=localhost;dbname=dolls', 'root',  '0001'); 
    if (!$dbh) exit();

    $sth = $dbh->prepare("SELECT * FROM `usersInformation` WHERE `User ID` = :cookieID");
    $sth->bindParam(':cookieID', $_COOKIE['id'], PDO::PARAM_STR);
    $sth->execute();
    
    $data = $sth->fetch(PDO::FETCH_ASSOC);
    return $data['User email']; 
}

/*Sends a pop-up message to the client on a new page on top of the old one*/ 
function sendMessage($message) { 
    echo "<script>alert('$message');</script>";
}