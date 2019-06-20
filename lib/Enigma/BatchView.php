<?php
/**
 * View class for the batch processing page
 * @author Charles B. Owen
 */

namespace Enigma;

/**
 * View class for the batch processing page
 */
class BatchView extends View {
    /**
     * BatchView constructor.
     * @param System $system The System object
     */
	public function __construct(System $system) {
		parent::__construct($system, View::BATCH);


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

		$dec = $system->getDecoded();
		$enc = $system->getEncoded();

        $machine = $this->presentEnigmaRotors();

        $html = <<<HTML
<div class="body">
$machine
<form class="dialog" method="post" action="post/batch.php">
<div class="encoder">
<p><textarea name="from">$dec</textarea> <textarea name="to">$enc</textarea></p>
<p><input type="submit" name="encode" value="Encode ->"> 
<input type="submit" name="decode" value="Decode <-"> <input type="submit" name="reset" value="Reset"></p>
</div>
</form>
</div>
HTML;

		return $html;
	}
}