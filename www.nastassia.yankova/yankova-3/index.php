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


$products_data =
    [
        ['/assets/images/product-1.jpg', 'Draculaura Doll',    "Monster High Draculaura Doll<br>With Pet And Accessories",    '$24.99'], 
        ['/assets/images/product-2.jpg', 'Lagoona Blue Doll',  "Monster High Lagoona Blue Doll<br>With Pet And Accessories",  '$24.99'], 
        ['/assets/images/product-3.jpg', 'Frankie Stein Doll', "Monster High Frankie Stein Doll<br>With Pet And Accessories", '$24.99'], 
        ['/assets/images/product-4.jpg', 'Cleo De Nile Doll',  "Monster High Cleo De Nile Doll<br>With Pet And Accessories",  '$24.99'], 
        ['/assets/images/product-5.jpg', 'Clawdeen Wolf Doll', "Monster High Clawdeen Wolf Doll<br>With Pet And Accessories", '$24.99'], 
        ['/assets/images/product-6.jpg', 'Deuce Gorgon Doll',  "Monster High Deuce Gorgon Doll<br>With Pet And Accessories",  '$24.99']
    ];

$collections_data =
    [
        ['G1 collection', '/assets/images/g1.jpg'], 
        ['G2 collection', '/assets/images/g2.jpg'],
        ['G3 collection', '/assets/images/g3.jpg']
    ];


$main = getPage('Monster high', file_get_contents('./content/index_content.html'), Header::Standard, Footer::Standard);
setCSS($main, array('/assets/css/style.css', '/assets/css/header.css', '/assets/css/footer.css'));


$products = array();
foreach($products_data as $product)
    $products[] = setProductItem($product[1], $product[2], $product[3], $product[0]); 
$tempstr = implode(" ", $products);
$main = str_replace('{product-item}', $tempstr, $main);

$collections = array();
foreach($collections_data as $collection)
    $collections[] = setCollectionItem($collection[0], $collection[1]); 
$tempstr = implode(" ", $collections);
$main = str_replace('{collection-item}', $tempstr, $main);

echo $main;


