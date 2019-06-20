<?php
/** @file
 * @brief The main unit testing page view
 * @author Charles B. Owen
 * @version 2.00 12-07-2014 Declared a version number
 * @version 2.01 01-30-2015 Custom version for CSE477
 * @version 3.00 01-19-2015 Pulled into a shared class
 * @cond
 */

namespace Testing;

class IndexView {

	public function __construct($dir, &$get) {
		$this->dir = $dir;
	}


	public function present() {
		$html = <<<HTML
<!doctype html>
<head>

<meta charset="utf-8" />
<TITLE>PHPUnit Testing</TITLE>
<link href="tests.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript" src="tests.js"></script>
</head>
<body>

<header>
	<nav><a href="#tests">tests</a> <a href="#results">results</a></nav>
	<h1>PHPUnit Testing</h1>
</header>

<h2 id="tests">Tests</h2>

<!-- Place to put the tests -->
<div id="testsdiv"></div>

<!-- Place to put the results -->
<form><h2 id="results">Results
<a class="clearresults">clear</a>
<span class="verbose"><input type="checkbox" name="verbose" id="verbose">verbose</span>
</h2></form>
<div id="resultsdiv"></div>

<script>
// The testing object
var tests = new Tests('testsdiv', 'resultsdiv');

// Add all of the tests

HTML;

		// This finds all of the tests in the tests directory and
		// adds them to the testing object.
		$files = scandir($this->dir . '/tests');

		foreach($files as $file) {
			if(strpos($file, 'Test.php') !== FALSE) {
				$test = substr($file, 0, strlen($file) - 8);
				$html .= "tests.add(['$test']);\n";
			}
		}

		$html .= <<<HTML
// And display the test links
tests.display();
</script>


</body>
</html>
HTML;


		return $html;
	}

	private $dir;
}