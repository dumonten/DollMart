<?php

include 'functions.php';

//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include './phpmailer/Exception.php'; 
include './phpmailer/PHPMailer.php'; 
include './phpmailer/SMTP.php';

if (isset($_POST['message']))
{
    if (cookiesAreSet()) {    
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true); 
        $mail->isSMTP();  //Send using SMTP
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;  //Enable SMTP authentication
        $mail->Username = 'ani.lanister@gmail.com';
        $mail->Password = 'zhpbtxtnqpjoedqm';
        $mail->SMTPSecure = 'ssl'; 
        $mail->Port = 465; 
    
        $mail->addAddress('ani.lanister@gmail.com');  //Add a сontact
        $mail->setFrom(getUserEmail());
        
        //Content
        $mail->isHTML(true); 
        $mail->Subject = "Request from web-site"; 
        $mail->Body = $_POST['message']; 
        $mail->send();  
        sendMessage("You have successfully sent the message"); 
    }
    else {
        sendMessage("You are not logged into your account"); 
    }    
} 

$main = getPage('Contact to us', file_get_contents('./content/contact_to_us_content.html'), Header::Standard, Footer::Standard);
setCSS($main, array('/assets/css/contact_to_us.css', '/assets/css/header.css', './assets/css/footer.css'));

echo $main;
