<?php
/**
 * View class for the settings page
 * @author Charles B. Owen
 */

namespace Enigma;

/**
 * View class for the settings page
 */
class SettingsView extends View {
    /**
     * SettingsView constructor.
     * @param System $system The System object
     */
	public function __construct(System $system) {
		parent::__construct($system, View::SETTINGS);


	}

	/**
	 * Preset the page header
	 * @return string HTML
	 */
	public function presentHeader() {
		$html = parent::presentHeader();

		return $html;
	}

	/**
	 * Present the page body
	 * @return string HTML
	 */
	public function presentBody() {
		$system = $this->getSystem();
		$enigma = $system->getEnigma();

		$rotor1 = $enigma->getRotorSetting(1);
        $rotor2 = $enigma->getRotorSetting(2);
        $rotor3 = $enigma->getRotorSetting(3);

        $machine = $this->presentEnigmaRotors();

        $html = <<<HTML
<div class="body">
$machine
<form class="dialog" method="post" action="post/settings.php">
HTML;

        // The form controls...
        $html .= $this->rotor(1);
        $html .= $this->rotor(2);
        $html .= $this->rotor(3);

		$html .= <<<HTML
<p><input type="submit" name="set" value="Set"> <input type="submit" name="cancel" value="Cancel"></p>
HTML;

		$html .= $this->presentMessage();

		$html .= <<<HTML
</form>
</div>
HTML;

		return $html;
	}

    /**
     * Create the form controls for a single rotor
     * @param $rotor Rotor number 1-3
     * @return string HTML
     */
	private function rotor($rotor) {
        $system = $this->getSystem();
        $enigma = $system->getEnigma();

        $setting = $enigma->getRotorSetting($rotor);
        $wheel = $enigma->getRotor($rotor);

        $html = <<<HTML
<p><label for="rotor-$rotor">Rotor $rotor:</label>
<select id="rotor-$rotor" name="rotor-$rotor">
HTML;

        $rotors = ['', 'I', 'II', 'III', 'IV', 'V'];
        for($i=1; $i<=5; $i++) {
            $id = $rotors[$i];
            $selected = $wheel == $i ? " selected" : "";
            $html .= <<<HTML
<option value="$i"$selected>$id</option>
HTML;

        }
        $html .= <<<HTML
</select>&nbsp;&nbsp;
<label for="initial-$rotor">Setting:</label>
<input class="initial" id="initial-$rotor" name="initial-$rotor" type="text" value="$setting">
</p>
HTML;

	    return $html;
    }
}