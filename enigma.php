<?php
require_once "lib/enigma.inc.php";
$view = new Enigma\EnigmaView($system);
if($view->getRedirect() !== null) {
  header("location: " . $view->getRedirect());
	exit;
}
//if(!$view->protect($site,$user)){
//    header("location: " . $view->getProtectRedirect());
//}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>The Endless Enigma</title>
  <?php echo $view->head(); ?>
</head>

<body>
<?php
echo $view->present();
?>
</body>
</html>
