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
            sendMessage("You are registred.");
            savePersonalData($text, $email, $password);
        }
        else {
            sendMessage("WRONG DATA!");
        }
    }
}

$main = getPage('Authorization', file_get_contents('./content/authorization_content.html'), Header::Authorization, Footer::None);
setCSS($main, array('/assets/css/authorization.css', '/assets/css/header.css'));

echo $main;
