<?php
require_once "../lib/enigma.inc.php";
$controller = new \Enigma\RecipientsController($system, $site,$_GET, $_POST);
//echo $controller->showRedirect();
header("location: " . $controller->getRedirect());