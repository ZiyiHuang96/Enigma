<?php
require_once "../lib/enigma.inc.php";
$controller = new \Enigma\SettingsController($system, $_POST);
//echo $controller->showRedirect();
header("location: " . $controller->getRedirect());