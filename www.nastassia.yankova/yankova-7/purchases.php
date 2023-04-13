<?php

include "functions.php"; 

if(cookiesAreSet()) {
    if (intval($_POST['product-num']) >= 0) {
        try {
            $dbh = connectToDb('mysql:host=localhost;dbname=dolls', 'root',  '0001'); 
            if (!$dbh) exit();
           
            $sth = $dbh->prepare("UPDATE `basketsInformation` SET `Num of product` = :pnum WHERE (`User ID` = :cookieID) AND (`Name of product` = :pname)");
            $sth->bindValue(':cookieID',     $_COOKIE['id'],  PDO::PARAM_INT);
            $sth->bindValue(':pname', $_POST['product-name'], PDO::PARAM_STR);
            $sth->bindValue(':pnum',  $_POST['product-num'],  PDO::PARAM_STR);
            $sth->execute(); 
            /*
            * Here it is necessary to catch an exception if there is no record. 
            * I.e. it needs to be created
            */
            if(!$sth->rowCount()) {
                $sth = $dbh->prepare("INSERT INTO `basketsInformation` SET `User ID` = :cookieID, `Name of product`=:pname, `Num of product` = :pnum");
                $sth->bindValue(':cookieID',     $_COOKIE['id'],  PDO::PARAM_INT);
                $sth->bindValue(':pname', $_POST['product-name'], PDO::PARAM_STR);
                $sth->bindValue(':pnum',  $_POST['product-num'],  PDO::PARAM_STR);
                $sth->execute();
            }
        }
        catch(Exception $e) {
            echo $e->getMessage(); 
            exit();  
        }
    } 
}
header("Location: index.php");