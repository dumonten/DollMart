<?php

include 'functions.php';

$main = getPage('Authorization', file_get_contents('./content/authorization_content.html'), Header::Authorization, Footer::None);
setCSS($main, array('/assets/css/authorization.css', '/assets/css/header.css'));

echo $main;


