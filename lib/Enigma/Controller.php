<?php
/**
 * Base class for controllers
 * @author Charles B. Owen
 */

namespace Enigma;

/**
 * Base class for controllers
 *
 * Every controller needs to know what system it is
 * a part of and any redirect page.
 */
class Controller {
	/**
	 * Controller constructor.
	 * @param System $system System this is a part of
	 */
	public function  __construct(System $system) {
		$this->system = $system;
	}

	/**
	 * Get the system object
	 * @return System
	 */
	public function getSystem() {
		return $this->system;
	}

	/**
	 * Set any redirect page
	 * @param $redirect Redirect page
	 */
	public function setRedirect($redirect) {
		$this->redirect = $redirect;
	}

	/**
	 * Get any redirect page
	 * @return string Redirect page
	 */
	public function getRedirect() {
		return $this->redirect;
	}

	/**
	 * Debug option to display the redirect page instead of redirecting to it.
	 * @return string HTML
	 */
	public function showRedirect() {
		return "<p><a href=\"$this->redirect\">$this->redirect</a>";
	}

	private $system;            ///< Current System object
	private $redirect = "./";   ///< Page to redirect to
}