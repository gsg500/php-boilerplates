<?php
session_start();

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require_once( 'app/services/AutoLoader.php' );

use \app\services\Autoloader;
use \app\ServiceLoader;
use \app\services\Database;
use \app\services\Router;
use \app\services\Message;
use \app\services\EnvLoader;

new Autoloader();
new EnvLoader();

$load = new ServiceLoader();

$load->set( 'Database', new Database(), true );
$load->set( 'Message', new Message(), true );

$router = new Router( $load );

require('web/routes.php');

$router->dispatch();