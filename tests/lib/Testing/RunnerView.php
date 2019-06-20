<?php
/**
 * Created by PhpStorm.
 * User: cbowen
 * Date: 1/19/2016
 * Time: 6:50 PM
 */

namespace Testing;

/** @file
 * @brief This class runs the PHPUnit tests on a single test file
 * @cond
 */
class RunnerView {
	public function __construct($dir, &$get) {
		$this->dir = $dir;
		$this->get = $get;
	}


	public function present() {


	}

	private $dir;
	private $get;
}