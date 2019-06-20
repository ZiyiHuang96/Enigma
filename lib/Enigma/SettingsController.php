<?php
/**
 * Controller for the settings page.
 * @author Charles B. Owen
 */

namespace Enigma;

/**
 * Controller for the settings page.
 */
class SettingsController extends Controller {
	/**
	 * SettingsController constructor.
	 * @param System $system System object
	 * @param array $post $_POST
	 */
	public function __construct(System $system, array $post) {
		parent::__construct($system);

		$system->setMessage(View::SETTINGS,'');

		// Default will be to return to the settings page
		$this->setRedirect("../settings.php");

		// If we cancel, we ignore the input completely
		if(isset($post['cancel'])) {
		    return;
        }

       // print_r($post);

        $rotors = [];

        for($r=1; $r<=3; $r++) {
            $rotor = strip_tags($post["rotor-$r"]);
            $setting = strip_tags($post["initial-$r"]);
            $setting = strtoupper($setting);

            if(strlen($setting) !== 1 ||
                strcmp($setting, 'A') < 0 || strcmp($setting, 'Z') > 0) {
                $system->setMessage(View::SETTINGS,"Invalid setting for rotor $r");
                return;
            }

            $rotors[] = ['rotor'=>$rotor, 'setting'=>$setting];
        }

        //
        // Ensure no duplicate rotor
        //
        if($rotors[0]['rotor'] == $rotors[1]['rotor'] ||
            $rotors[0]['rotor'] == $rotors[2]['rotor'] ||
            $rotors[1]['rotor'] == $rotors[2]['rotor']) {
            $system->setMessage(View::SETTINGS,'You are not allowed to use the same rotor more than once.');
            return;
        }

        $system->setRotors($rotors);
	}
}