<?php
$open = true;
require 'lib/enigma.inc.php';
$view = new Enigma\PasswordValidateView($system, $_GET);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>The Endless Enigma Password Entry</title>
    <?php echo $view->head(); ?>
</head>

<body>
<?php echo $view->presentBody()?>

</body>
</html>
