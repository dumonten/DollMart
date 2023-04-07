<?php

include 'functions.php';

$main = getPage('Basket', file_get_contents('./content/basket_content.html'), Header::Basket, Footer::Standard);
setCSS($main, array('/assets/css/basket.css', '/assets/css/header.css', '/assets/css/footer.css'));

try
{
    $dbh = connectToDb('mysql:host=localhost;dbname=dolls', 'root',  '0001'); 
    if (!$dbh) exit();
    $sth = $dbh->prepare("SELECT * FROM `basketsInformation` WHERE `User ID` = :cookieID");    
    $sth->bindValue(':cookieID',  $_COOKIE['id'], PDO::PARAM_STR); 
    $sth->execute();
    $data = $sth->fetchAll(PDO::FETCH_ASSOC);
}
catch(Exception $e)
{
    echo $e->getMessage(); 
    exit();  
}

$products = generateProductList($data); 
$main = str_replace('{product-list}', $products, $main);

echo $main;


