<?php

include 'functions.php';

/*Returns the html-code for products(dolls) with the passed parameters for the match of products on the basket page*/ 
function generateProductList($products) {
    $productContent = file_get_contents('./content/product_content.html');
    $productList = array();
    foreach($products as $product)
    {
        if ($product['Num of product'] <= 0)
            continue;  
        $pitem  = str_replace('{product-name}',  $product['Name of product'], $productContent);     
        $pitem  = str_replace('{product-num}',  $product['Num of product'], $pitem);         
        $productList[] = $pitem;
    }
    if (empty($productList))
        return('<div class="product-item">"Your basket is empty!"</div>'); 
    return implode(" ", $productList);
}

/*
* Connecting to the database to get information about: 
* - user purchases
*/
try {
    $dbh = connectToDb('mysql:host=localhost;dbname=dolls', 'root',  '0001'); 
    if (!$dbh) exit();
    
    $sth = $dbh->prepare("SELECT * FROM `basketsInformation` WHERE `User ID` = :cookieID");    
    $sth->bindValue(':cookieID',  $_COOKIE['id'], PDO::PARAM_STR); 
    
    $sth->execute();
    
    $data = $sth->fetchAll(PDO::FETCH_ASSOC);
}
catch(Exception $e) {
    echo $e->getMessage(); 
    exit();  
}

$main = getPage('Basket', file_get_contents('./content/basket_content.html'), Header::Basket, Footer::Standard);
setCSS($main, array('/assets/css/basket.css', '/assets/css/header.css', '/assets/css/footer.css'));

$products = generateProductList($data); 
$main = str_replace('{product-list}', $products, $main);

echo $main;


