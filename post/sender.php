<?php
require '../lib/enigma.inc.php';
$controller = new Enigma\SenderController($system, $site, $_POST);
header("location: " . $controller->getRedirect());