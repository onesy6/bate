<?php
$config = include 'config.inc.php';
include 'initialization.class.php';
$initialization = new Initialization();
$domain = $initialization->getDomainFromHost($_SERVER['HTTP_HOST']);
define('DOMAIN', $domain);
// 走分发。
$dispatch_route = $initialization->getDispatchRoute($config);

