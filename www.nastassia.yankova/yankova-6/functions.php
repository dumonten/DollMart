<?php

const link = '<link rel="stylesheet" href={path-css}>';
const mainTemplatePath = './prime_template.html';
const headerPath = './content/header_content.html';
const headerTopNavStandardPath = './content/header_topnav_standard.html';
const headerTopNavAuthPath = './content/header_topnav_authorization.html';
const headerTopNavBasketPath = './content/header_topnav_basket.html';
const footerPath = './content/footer_content.html';
const productContentPath = './content/product_content.html';

enum Header
{
    case Standard;
    case Authorization;
    case Basket;
    case None;
}

enum Footer
{
    case Standard;
    case None;
}

function cookiesAreSet()
{
    if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
    {
        $dbh = connectToDb('mysql:host=localhost;dbname=dolls', 'root',  '0001'); 
        if (!$dbh) exit();
        $sth = $dbh->prepare("SELECT * FROM `usersInformation` WHERE `User ID` = :cookieID");
        $sth->bindParam(':cookieID', $_COOKIE['id'], PDO::PARAM_STR);
        $sth->execute();
        $data = $sth->fetch(PDO::FETCH_ASSOC);
        if(($data['User hash'] != $_COOKIE['hash']) or ($data['User ID'] != $_COOKIE['id']))
        {
            setcookie("id",   "", time() - 1);
            setcookie("hash", "", time() - 1);
            return false; 
        }
        return true; 
    }
    else
    {
        return false; 
    }
}

function unsetCookies()
{
    setcookie("id",   "", time() - 1);
    setcookie("hash", "", time() - 1);
}

function getStylesheetDefinitions($paths)
{
    $links = array();
    foreach($paths as $path)
        $links[] = str_replace('{path-css}', $path, link);
    return implode(" ", $links);
}
function getPage($title, $content, $headerType, $footerType)
{
    $main = file_get_contents(mainTemplatePath);

    $header = $topnav_content = "";
    switch ($headerType)
    {
        case Header::Standard:
            $header  = file_get_contents(headerPath);
            $topnav_content = file_get_contents(headerTopNavStandardPath);
            if (cookiesAreSet())
            {
                $topnav_content = str_replace('{user-page}', './basket.php', $topnav_content); 
            }
            else
            {
                $topnav_content = str_replace('{user-page}', './authorization.php', $topnav_content); 
            }
            break;
        case Header::Authorization:
            $header  = file_get_contents(headerPath);
            $topnav_content = file_get_contents(headerTopNavAuthPath);
            break; 
        case Header::Basket:
            $header  = file_get_contents(headerPath);
            $topnav_content = file_get_contents(headerTopNavBasketPath);
            break;
        case Header::None:
            break;
    }
    $header  = str_replace('{header-topnav-content}', $topnav_content, $header);
    if (cookiesAreSet())
    {
        $header  = str_replace('{num-in-basket}', getNumInBasket(), $header);       
    }
    else 
    {
        $header  = str_replace('{num-in-basket}', '0', $header);
    }
    $footer  = "";
    switch ($footerType)
    {
        case Footer::Standard:
            $footer  = file_get_contents(footerPath);
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

function setCSS(&$page, $css_paths)
{
    $stylesheets_adding = getStylesheetDefinitions($css_paths);
    $page = str_replace('{stylesheets_adding}', $stylesheets_adding, $page);
}

function setProductItem($product_item_value, $product_item_name, $product_price, $product_photo_path)
{
    $main = file_get_contents("./content/product_item.html");
    $main = str_replace('{product-photo-path}',   $product_photo_path, $main);
    $main = str_replace('{product-item-value}',   $product_item_value, $main);
    $main = str_replace('{product-item-name}',    $product_item_name,  $main);
    $main = str_replace('{product-price}',        $product_price,      $main);
    return $main;
}

function setCollectionItem($collection_name, $collection_photo_path)
{
    $main = file_get_contents("./content/collection_item.html");
    $main = str_replace('{collection-name}',         $collection_name,       $main);
    $main = str_replace('{collection-photo-path}',   $collection_photo_path, $main);
    return $main;
}


function function_alert($message) 
{ 
    echo "<script>alert('$message');</script>";
}

function connectToDb($dsn, $login, $password)
{
    try
    {
        $dbh = new PDO($dsn, $login,  $password, 
                        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    }
    catch(PDOException $e) 
    {
        echo "Error: data base isn't found."; 
        return null; 
    }
    return $dbh;
}

function generateProductList($products)
{
    if (empty($products))
        return('<div class="product-item">"Your basket is empty!"</div>'); 
    $productContent = file_get_contents(productContentPath);
    $productList = array();
    foreach($products as $product)
    {
        if ($product['Num of product'] <= 0)
            continue;  
        $pitem  = str_replace('{product-name}',  $product['Name of product'], $productContent);     
        $pitem  = str_replace('{product-num}',  $product['Num of product'], $pitem);         
        $productList[] = $pitem;
    }
    return implode(" ", $productList);
}

function getNumInBasket() : string
{
    if(cookiesAreSet())
    {
        $dbh = connectToDb('mysql:host=localhost;dbname=dolls', 'root',  '0001'); 
        if (!$dbh) exit();
        $sth = $dbh->prepare("SELECT * FROM `basketsInformation` WHERE `User ID` = :cookieID");    
        $sth->bindValue(':cookieID',  $_COOKIE['id'], PDO::PARAM_STR); 
        $sth->execute();
        $products = $sth->fetchAll(PDO::FETCH_ASSOC);     
    }
    $total = 0; 
    foreach ($products as $product)
    {
        if ($product['Num of product'] <= 0)
            continue;  
        $total += intval($product['Num of product']); 
    }
    return strval($total); 
}