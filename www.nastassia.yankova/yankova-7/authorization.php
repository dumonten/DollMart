<?php

include 'functions.php';
include 'validation.php';

$main = getPage('Authorization', file_get_contents('./content/authorization_content.html'), Header::Authorization, Footer::None);
setCSS($main, array('/assets/css/authorization.css', '/assets/css/header.css'));

if (!empty($_POST))
{
    $email = $_POST['email'];
    $password = $_POST['password'];
    if ($_POST['flag'] == "login")
    {
        if (isValidateEmail($email) && $password != "")
        { 
            $dbh = connectToDb('mysql:host=localhost;dbname=dolls', 'root',  '0001'); 
            if (!$dbh) exit();
            $sth = $dbh->prepare("SELECT * FROM `usersInformation` WHERE `User email` = :email");
            $sth->bindParam(':email', $email, PDO::PARAM_STR);
            $sth->execute();
            $data = $sth->fetch(PDO::FETCH_ASSOC);
            if($data['User password'] === hash("sha256", $password))
            {
                $uhash = hash("sha256", rand(10e5, 10e10));
                $sth = $dbh->prepare("UPDATE `usersInformation` SET `User hash` = :uhash WHERE `User email` = :email");
                $sth->bindParam(':email', $email, PDO::PARAM_STR);
                $sth->bindParam(':uhash', $uhash, PDO::PARAM_STR);
                $sth->execute();
                
                setcookie("id", $data['User ID']);
                setcookie("hash", $uhash);

                header("Location: basket.php"); exit();
            }
            else
            {
                function_alert("Вы ввели неправильный логин/пароль"); 
            }
        }
        else
        {
            function_alert("WRONG DATA!"); 
        }
    }
    else if ($_POST['flag'] == "register") 
    {
        $text = $_POST['text'];
        if (isValidateText($text) && isValidateEmail($email) && $password != "")
        {
            $dbh = connectToDb('mysql:host=localhost;dbname=dolls', 'root',  '0001'); 
            if (!$dbh) exit();
            $sth = $dbh->prepare("SELECT * FROM `usersInformation` WHERE `User email` = :email");
            $sth->bindParam(':email', $email, PDO::PARAM_STR);
            $sth->execute();
            $data = $sth->fetch(PDO::FETCH_ASSOC);
            if ($data and $data['User email'] == $email) {

                function_alert("User with such login alredy exists!"); 
            }
            else
            {
                try
                {
                    $dbh = connectToDb('mysql:host=localhost;dbname=dolls', 'root',  '0001'); 
                    if (!$dbh) exit();
                    $password = hash("sha256", $password);
                    $sth = $dbh->prepare("INSERT INTO `usersInformation` SET `User name` = :user_name, `User email` = :user_email, `User password` = :user_password");
                    $sth->bindParam(':user_name',       $text,      PDO::PARAM_STR);
                    $sth->bindParam(':user_email',      $email,     PDO::PARAM_STR);
                    $sth->bindParam(':user_password',   $password,  PDO::PARAM_STR);
                    $sth->execute();
                }
                catch(Exception $e)
                {
                    echo $e->getMessage(); 
                    exit();  
                }

                function_alert("You are registred.");
            }
           
        }
        else
        {
            function_alert("WRONG DATA!");
        }
    }
}

echo $main;
