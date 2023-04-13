<?php 

define('SPECIAL_CHARACTERS', "\!\#\$\%\&\'\*\+\-\/\=\?\^\_\`\{\|\}\~\.");
define('CHARACTER', "a-z0-9"); 
define('PATTERN_MAIL', "/^[{".CHARACTER."}{".SPECIAL_CHARACTERS."}]+@([{".CHARACTER."}\-]+\.)+[{".CHARACTER."}]{2,6}$/i");
define('PATTERN_USER_NAME', "/^[a-zA-z\-\x20]+$/");

function isValidateEmail($email) {
    return preg_match(PATTERN_MAIL, $email); 
}

function isValidateText($name) {
    return preg_match(PATTERN_USER_NAME, $name);
}
