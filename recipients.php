<?php
require_once "lib/enigma.inc.php";
$view = new Enigma\RecipientsView($system,$site,$_GET,$_POST);
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
    <link href="enigma.css" type="text/css" rel="stylesheet" />
</head>

<body>
<?php echo $view->present();?>
</body>
</html>