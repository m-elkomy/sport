<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

include 'connect.php';

//initialize routes for directory
$lang = 'includes/languages/';
$func = 'includes/functions/';
$tpl = 'includes/templates/';
$css = 'layout/css/';
$js  = 'layout/js/';

include $lang . 'english.php';
include $func . 'function.php';
include $tpl . 'header.php';

//adding navbar to all page except the one has nonavbar var
if(!isset($nonavbar)){
    include $tpl . 'navbar.php';
}