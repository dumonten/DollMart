<?php

include 'functions.php';

/*Returns the html-code for product(dolls) with the passed parameters for the match of products on the index page*/ 
function setProductItem($product_item_value, $product_item_name, $product_price, $product_photo_path) {
    $page = file_get_contents('./content/product_item_for_index_page.html');
    $page = str_replace('{product-photo-path}',   $product_photo_path, $page);
    $page = str_replace('{product-item-value}',   $product_item_value, $page);
    $page = str_replace('{product-item-name}',    $product_item_name,  $page);
    $page = str_replace('{product-price}',        $product_price,      $page);
    return $page;
}

/*Returns the html-code for collections item with the passed parameters for the match of products on the index page*/ 
function setCollectionItem($collection_name, $collection_photo_path) {
    $page = file_get_contents('./content/collection_item_for_index_page.html');
    $page = str_replace('{collection-name}',         $collection_name,       $page);
    $page = str_replace('{collection-photo-path}',   $collection_photo_path, $page);
    return $page;
}


/*
* Connecting to the database to get information about:
* - recently received products page
* - collections
*/
try {
    
    $dbh = connectToDb('mysql:host=localhost;dbname=dolls', 'root',  '0001'); 
    if (!$dbh) exit(); 
    
    $sth_products    = $dbh->prepare("SELECT * FROM `dollsInformation`");
    $sth_collections = $dbh->prepare("SELECT * FROM `collectionsInformation`");
    
    $sth_products->execute();
    $sth_collections->execute();
    
    $products_data = $sth_products->fetchAll(PDO::FETCH_ASSOC);
    $collections_data = $sth_collections->fetchAll(PDO::FETCH_ASSOC);
}
catch(Exception $e) {
    echo $e->getMessage(); 
    exit();  
}


$main = getPage('Monster high', file_get_contents('./content/index_content.html'), Header::Standard, Footer::Standard);
setCSS($main, array('/assets/css/style.css', '/assets/css/header.css', '/assets/css/footer.css'));

$products = array(); 
foreach($products_data as $product)
    $products[] = setProductItem($product['Doll name'], $product['Doll item name'], $product['Doll price'], $product['Doll main photo path']); 
$tempstr = implode(" ", $products);
$main = str_replace('{product-item}', $tempstr, $main);

$collections = array();
foreach($collections_data as $collection)
    $collections[] = setCollectionItem($collection['Collection name'], $collection['Collection photo path']); 
$tempstr = implode(" ", $collections);
$main = str_replace('{collection-item}', $tempstr, $main);

echo $main;
