<?php

include 'functions.php';

$main = getPage('Monster high', file_get_contents('./content/index_content.html'), Header::Standard, Footer::Standard);
setCSS($main, array('/assets/css/style.css', '/assets/css/header.css', '/assets/css/footer.css'));

echo $main;


