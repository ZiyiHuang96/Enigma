<?php
require '../lib/enigma.inc.php';
$controller = new Enigma\PasswordValidateController($site, $_POST);
header("location: " . $controller->getRedirect());