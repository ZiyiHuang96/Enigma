<?php
/**
 * Main Enigma controller. Handles post from the Enigma simulation.
 * @author Charles B. Owen
 */

namespace Enigma;

/**
 * Main Enigma controller. Handles post from the Enigma simulation.
 */
class EnigmaController extends Controller {
	/**
	 * EnigmaController constructor.
	 * @param System $system System object
	 * @param array $post $_POST
	 */
	public function __construct(System $system, array $post) {
		parent::__construct($system);

		// Default will be to return to the enigma page
		$this->setRedirect("../enigma.php#enigma");

		if(!empty($post['key'])) {
		    $system->press(strip_tags($post['key']));
        }

        if(!empty($post['reset'])) {
		    $system->reset();
        }
	}
}