<?php

include 'functions.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './phpmailer/Exception.php'; 
require './phpmailer/PHPMailer.php'; 
require './phpmailer/SMTP.php';


if (cookiesAreSet())
{
    if (isset($_POST['message'])) 
    {    
        $mail = new PHPMailer(true); 
        $mail->isSMTP(); 
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;  
        $mail->Username = 'ani.lanister@gmail.com';
        $mail->Password = 'zhpbtxtnqpjoedqm';
        $mail->SMTPSecure = 'ssl'; 
        $mail->Port = 465; 
    
        $mail->addAddress('ani.lanister@gmail.com');
        $mail->setFrom(getUserEmail());
        $mail->isHTML(true); 
        $mail->Subject = "Request from web-site"; 
        $mail->Body = $_POST['message']; 
        $mail->send();  
        function_alert("Типа отправилось"); 
    }
}
else 
{
    function_alert("Вы не залогинились"); 
}


$main = getPage('Contact to us', file_get_contents('./content/contact_to_us_content.html'), Header::Standard, Footer::Standard);
setCSS($main, array('/assets/css/contact_to_us.css', '/assets/css/header.css', './assets/css/footer.css'));
echo $main;
