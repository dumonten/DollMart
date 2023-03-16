<?php

const link = '<link rel="stylesheet" href={path-css}>';
const mainTemplatePath = './prime_template.html';
const headerPath = './content/header_content.html';
const headerTopNavStandardPath = './content/header_topnav_standard.html';
const headerTopNavAuthPath = './content/header_topnav_authorization.html';
const footerPath = './content/footer_content.html';

enum Header
{
    case Standard;
    case Authorization;
    case None;
}

enum Footer
{
    case Standard;
    case None;
}

function getStylesheetDefinitions($paths): string
{
    $links = array();
    foreach($paths as $path)
        $links[] = str_replace('{path-css}', $path, link);
    return implode(" ", $links);
}
function getPage($title, $content, $headerType, $footerType): array|bool|string
{
    $main = file_get_contents(mainTemplatePath);

    $header = $topnav_content = "";
    switch ($headerType)
    {
        case Header::Standard:
            $header  = file_get_contents(headerPath);
            $topnav_content = file_get_contents(headerTopNavStandardPath);
            break;
        case Header::Authorization:
            $header  = file_get_contents(headerPath);
            $topnav_content = file_get_contents(headerTopNavAuthPath);
            break;
        case Header::None:
            break;
    }
    $header  = str_replace('{header-topnav-content}', $topnav_content, $header);

    $footer  = "";
    switch ($footerType)
    {
        case Footer::Standard:
            $footer  = file_get_contents(footerPath);
            break;
        case Footer::None:
            break;
    }

    $main = str_replace('{title}',   $title,   $main);
    $main = str_replace('{header}',  $header,  $main);
    $main = str_replace('{content}', $content, $main);
    $main = str_replace('{footer}',  $footer,  $main);

    return $main;
}
function setCSS(&$page, $css_paths): void
{
    $stylesheets_adding = getStylesheetDefinitions($css_paths);
    $page = str_replace('{stylesheets_adding}', $stylesheets_adding, $page);
}