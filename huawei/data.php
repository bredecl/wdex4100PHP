<?php
function p($a)
{
    echo '<pre>';
    print_r($a);
    echo '</pre>';
}

error_reporting(E_ALL);
include_once("CustomHttpClient.php");
include_once("Router.php");


//The router class is the main entry point for interaction.
$router = new Router();


//If specified without http or https, assumes http://
$router->setAddress('192.168.3.1');

//Username and password.
//Username is always admin as far as I can tell.
$router->login('admin', 'Badorem.161');
echo "d";
die();
var_dump($router->getLedStatus());
//https://github.com/zetcco/Restart-Router
//https://github.com/HSPDev/Huawei-E5180-API
//http://192.168.3.17/huawei/data.php