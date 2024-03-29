<?php

include 'functions.php';
include 'validation.php';

if (!empty($_POST)) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    if ($_POST['flag'] == "login") {
        if (isValidateEmail($email) && $password != "") { 
            sendMessage("You are logged.");
        } else {
            sendMessage("WRONG DATA!"); 
        }
    } else if ($_POST['flag'] == "register") {
        $text = $_POST['text'];
        if (isValidateText($text) && isValidateEmail($email) && $password != "") {
            try {
                $dbh = connectToDb('mysql:host=localhost;dbname=dolls', 'root',  '0001'); 
                if (!$dbh) exit();
                $password = hash("sha256", $password);
                $sth = $dbh->prepare("INSERT INTO `usersInformation` SET `User name` = :user_name, `User email` = :user_email, `User password` = :user_password");
                $sth->bindParam(':user_name',       $text,      PDO::PARAM_STR);
                $sth->bindParam(':user_email',      $email,     PDO::PARAM_STR);
                $sth->bindParam(':user_password',   $password,  PDO::PARAM_STR);
                $sth->execute();
            } catch(Exception $e) {
                echo $e->getMessage(); 
                exit();  
            }
            sendMessage("You are registred.");
        } else {
            sendMessage("WRONG DATA!");
        }
    }
}


$main = getPage('Authorization', file_get_contents('./content/authorization_content.html'), Header::Authorization, Footer::None);
setCSS($main, array('/assets/css/authorization.css', '/assets/css/header.css'));

echo $main;
