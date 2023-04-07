<?php

include 'functions.php';

try
{
    $dbh = connectToDb('mysql:host=localhost;dbname=dolls', 'root',  '0001'); 
    if (!$dbh) exit();
    $product_name = $_GET['product-name'];
    $sth = $dbh->prepare("SELECT * FROM `dollsInformation` WHERE `Doll name` = :product_name");
    $sth->bindParam(':product_name', $product_name, PDO::PARAM_STR);
    $sth->execute();
    $data = $sth->fetch(PDO::FETCH_ASSOC);
}
catch(Exception $e)
{
    echo $e->getMessage(); 
    exit();  
}

$content = file_get_contents('./content/doll_content.html');
$content = str_replace('{product-title}',  $product_name."<br>with Pet And Accessories", $content);
$content = str_replace('{main-photo}',     $data['Doll main photo path'], $content);
$content = str_replace('{small-photo}',    $data['Doll small photo path'], $content);
$content = str_replace('{price}',          $data['Doll price'], $content);
$content = str_replace('{about}',          $data['Doll about-text'], $content);
$content = str_replace('{about-list}',     $data['Doll about-list-text'], $content);

$main = getPage($product_name, $content, Header::Standard, Footer::Standard);
setCSS($main, array('./assets/css/doll.css', './assets/css/header.css', './assets/css/footer.css'));

echo $main; 


