<?php
require "lib/Testing/IndexView.php";
$view = new Testing\IndexView(__DIR__, $_GET);
echo $view->present();
