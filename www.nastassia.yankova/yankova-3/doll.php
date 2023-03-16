<?php

include 'functions.php';

$data =
    [
        'Draculaura Doll'    => ['./assets/images/product-1.jpg', './assets/images/product-1.jpg', '$24.99', './content/doll-data/product-about-1.txt', './content/doll-data/product-about-list-1.txt'],
        'Lagoona Blue Doll'  => ['./assets/images/product-2.jpg', './assets/images/product-2.jpg', '$24.99', './content/doll-data/product-about-2.txt', './content/doll-data/product-about-list-2.txt'],
        'Frankie Stein Doll' => ['./assets/images/product-3.jpg', './assets/images/product-3.jpg', '$24.99', './content/doll-data/product-about-3.txt', './content/doll-data/product-about-list-3.txt'],
        'Cleo De Nile Doll'  => ['./assets/images/product-4.jpg', './assets/images/product-4.jpg', '$24.99', './content/doll-data/product-about-4.txt', './content/doll-data/product-about-list-4.txt'],
        'Clawdeen Wolf Doll' => ['./assets/images/product-5.jpg', './assets/images/product-5.jpg', '$24.99', './content/doll-data/product-about-5.txt', './content/doll-data/product-about-list-5.txt'],
        'Deuce Gorgon Doll'  => ['./assets/images/product-6.jpg', './assets/images/product-6.jpg', '$24.99', './content/doll-data/product-about-6.txt', './content/doll-data/product-about-list-6.txt']
    ];

$product_name = $_GET['product-name'];

$content = file_get_contents('./content/doll_content.html');
$content = str_replace('{product-title}',  $product_name."<br>with Pet And Accessories", $content);
$content = str_replace('{main-photo}',     $data[$product_name][0], $content);
$content = str_replace('{small-photo}',    $data[$product_name][1], $content);
$content = str_replace('{price}',          $data[$product_name][2], $content);
$content = str_replace('{about}',          file_get_contents($data[$product_name][3]), $content);
$content = str_replace('{about-list}',     file_get_contents($data[$product_name][4]), $content);

$main = getPage($product_name, $content, Header::Standard, Footer::Standard);
setCSS($main, array('./assets/css/doll.css', './assets/css/header.css', './assets/css/footer.css'));

echo $main;
