<?php
require_once "lib/enigma.inc.php";
$view = new Enigma\SettingsView($system);
if($view->getRedirect() !== null) {
  header("location: " . $view->getRedirect());
	exit;
}
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
