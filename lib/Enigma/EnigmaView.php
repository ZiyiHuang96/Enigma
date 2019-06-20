<?php
/**
 * Main Enigma simulator view class
 * @author Charles B. Owen
 */

namespace Enigma;

/**
 * Main Enigma simulator view class
 */
class EnigmaView extends View {

    /**
     * EnigmaView constructor.
     * @param System $system The System object
     */
	public function __construct(System $system) {
		parent::__construct($system, View::ENIGMA);
	}

	/**
	 * Preset the page header
	 * @return string HTML
	 */
	public function presentHeader() {
		$html = parent::presentHeader();

		$name = $this->getSystem()->getUser()->getName();

		$html .= <<<HTML
<h1 class="center">Greetings, $name, and welcome to The Endless Enigma!</h1>
HTML;

		return $html;
	}

	/**
	 * Present the page footer
	 * @return string HTML
	 */
	public function presentBody() {
		$system = $this->getSystem();
        $enigma = $system->getEnigma();

        $rotor1 = $enigma->getRotorSetting(1);
        $rotor2 = $enigma->getRotorSetting(2);
        $rotor3 = $enigma->getRotorSetting(3);

		$keys = ['q', 'w', 'e', 'r', 't', 'z', 'u', 'i', 'o',
            'a', 's', 'd', 'f', 'g', 'h', 'j', 'k',
            'p', 'y', 'x', 'c', 'v', 'b', 'n', 'm', 'l'];

		$html = <<<HTML
<div class="body">
<form method="post" action="post/enigma.php">

<div class="enigma" id="enigma">
<figure class="enigma"><img src="images/enigma.png" alt="Enigma Simulation"></figure>
<p class="wheel wheel-1">$rotor1</p>
<p class="wheel wheel-2">$rotor2</p>
<p class="wheel wheel-3">$rotor3</p>
HTML;

		foreach($keys as $l) {
		    $l1 = strtoupper($l);
            $on = $l1 === $system->getLighted() ? "light-on" : "";
            $pressed = $l1 === $system->getPressed() ? "pressed" : "";

            $html .= <<<HTML
<div class="key key-$l $pressed"><img src="images/key.png" alt="$l1 Key"><button name="key" value="$l1"><span>$l1</span></button></div>
<div class="light light-$l $on">$l1</div>
HTML;
        }


		$html .= <<<HTML
</div>
HTML;


		if($system->getMessage(View::ENIGMA) !== null) {
			$html .= '<p class="message">' . $system->getMessage(View::ENIGMA) . '</p>';
		}

		$html .= <<<HTML
</form>
<form class="dialog" method="post" action="post/enigma.php">
<p><input type="submit" name="reset" value="Reset"></p>
</form>

</div>
HTML;

		return $html;
	}
}