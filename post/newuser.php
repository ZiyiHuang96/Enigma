<?php
require_once "../lib/enigma.inc.php";
$controller = new \Enigma\NewUserController($system, $_POST, $site);
//echo $controller->showRedirect();
header("location: " . $controller->getRedirect());