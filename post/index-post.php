<?php
require_once "../lib/enigma.inc.php";
$controller = new \Enigma\IndexController($system, $_SESSION, $_POST, $site);
//echo $controller->showRedirect();
header("location: " . $controller->getRedirect());