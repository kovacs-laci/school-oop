<?php
session_start();
ini_set('error_log', 'error_log.log');

//// Detect if the request is an AJAX call
//$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
//
//if ($isAjax) {
//    // Handle AJAX requests only (skip rendering HTML)
//    requestHandle();
//    return;
//}
//// Render the full HTML for non-AJAX requests
//head();
//displayBodyStart();
//displayNav();
//requestHandle();
//displayBodyEnd();
include __DIR__ . '/../vendor/autoload.php';

use App\Routing\Router;

$router = new Router();
$router->handle();

