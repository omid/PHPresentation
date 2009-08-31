<?php
error_reporting(E_ALL);

function __autoload($name)
{
    require_once("classes/{$name}.php");
}

require_once('functions.php');

// initializing variables
$g['weburl'] = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
$g['webdir'] = dirname($_SERVER['PHP_SELF']);

if(!isset($_GET['dir'])){
    $_GET['dir'] = '';
}

$g['base_path'] = "presentation/{$_GET['dir']}/";

find_path();

parse_presentation();

print_output();
