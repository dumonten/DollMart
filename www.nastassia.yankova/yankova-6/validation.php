<?php 

$special_characters = "\!\#\$\%\&\'\*\+\-\/\=\?\^\_\`\{\|\}\~\.";
$character = "a-z0-9"; 
$pattern_mail = "/^[{$character}{$special_characters}]+@([{$character}\-]+\.)+[{$character}]{2,6}$/i";
$pattern_text = "/^[a-zA-z\-\x20]+$/";

function isValidateEmail($email)
{
    global $pattern_mail; 
    return preg_match($pattern_mail, $email); 
}

function isValidateText($text)
{
    global $pattern_text; 
    return preg_match($pattern_text, $text);
}
