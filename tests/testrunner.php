<?php
// Get the desired test
$test = strip_tags($_REQUEST['test']);
echo 'Test: ' . $test . "\n";

/*
 * This prevents PHPUnit from flushing the buffer we
 * currently have open.
 */
define('PHPUNIT_TESTSUITE', true);

// Run the test
// This fakes the command line parameters to phpunit.phar
$argv1 = ["phpunit.phar", "--printer", "PHPUnitTestPrinter"];
if($test === 'all.php') {
	$argv1[] = $testingDirectory . '/tests';
} else {
	$argv1[] = $test;
}
$_SERVER['argv'] = $argv1;
$GLOBALS['argv'] = $argv1;

require __DIR__ . '/phpunit.phar';

class PHPUnitTestPrinter extends PHPUnit_TextUI_ResultPrinter {
	public function __construct($out = null, $verbose = false, $colors = self::COLOR_DEFAULT, $debug = false, $numberOfColumns = 80, $reverse = false) {
		parent::__construct($out, $verbose, $colors, $debug, $numberOfColumns, $reverse);
	}

	public function flush() {}
}

PHPUnit_TextUI_Command::main(false);
