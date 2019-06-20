<?php
require '../lib/site.inc.php';

$controller = new Enigma\UserController($site, $user, $_POST);
header("location: " . $controller->getRedirect());