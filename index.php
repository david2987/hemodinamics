<?php
ini_set('display_errors', 1);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'model/database.php';

// Determine controller
if(!isset($_GET['p']))
{
    $controller = 'presupuesto';
}else{
    $controller = $_GET['p'];
}

if(isset($_REQUEST['c'])) {
    $controller = strtolower($_REQUEST['c']);
}

// Session authentication check
if (!isset($_SESSION['user']) && $controller !== 'auth') {
    header('Location: index.php?c=auth&a=Login');
    exit;
}

$accion = isset($_REQUEST['a']) ? $_REQUEST['a'] : 'Index';

// Front Controller instantiation
require_once "controller/$controller.controller.php";
$controllerClass = ucwords($controller) . 'Controller';
$controllerInstance = new $controllerClass;

// Execute action
call_user_func( array( $controllerInstance, $accion ) );
?>