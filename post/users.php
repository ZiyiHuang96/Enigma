<?php
require '../lib/site.inc.php';

$controller = new Enigma\UsersController($site, $user, $_POST);
header("location: " . $controller->getRedirect());