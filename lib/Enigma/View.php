<?php
/**
 * General purpose view base class, where we put generic formatting.
 * @author Charles B. Owen
 */

namespace Enigma;

/**
 * General purpose view base class, where we put generic formatting.
 */
abstract class View {
    const INDEX = 0;        ///< Constant for index.php
    const ENIGMA = 1;       ///< Constant for enigma.php
    const SETTINGS = 2;     ///< Constant for settings.php
    const BATCH = 3;        ///< Constant for batch.php
    const NEWUSER = 4;
    const PASSWORDVALIDATE = 5;
    const SEND = 6;
    const RECEIVE = 7;
    const RECIPIENTS = 8;
    /**
     * View constructor.
     * @param System $system The System object
     * @param $page Page we are viewing (one of the constants above)
     */
	public function  __construct(System $system, $page) {
		$this->system = $system;
		$this->page = $page;

		// Automatically redirect to index if
        // the system is not ready to use.
		if($page !== self::INDEX) {
		    if(!$system->ready()) {
		        $this->setRedirect('./');
            }
        }
	}

    /**
     * Get the System object
     * @return System object
     */
	public function getSystem() {
		return $this->system;
	}

    /**
     * Set a page to optionally redirect to
     * @param $redirect string Redirect link or null if none.
     */
	public function setRedirect($redirect) {
		$this->redirect = $redirect;
	}

    /**
     * Get a page to optionally redirect to
     * @return string|null Redirect link or null if none.
     */
	public function getRedirect() {
		return $this->redirect;
	}

    /**
     * Common content that goes in the &lt;head&gt; section
     * @return string HTML
     */
	public function head() {
	    return <<<HTML
	<link href="enigma.css" type="text/css" rel="stylesheet" />
HTML;
    }

    /**
     * Present the entire page.
     * @return string HTML
     */
	public function present() {
		return $this->presentHeader() .
			$this->presentBody() .
			$this->presentFooter();
	}

    /**
     * Present the header for a page
     * @return string HTML
     */
	public function presentHeader() {
		$html = <<<HTML
<header>
<figure><img src="images/banner-800.png" width="800" height="357" alt="Header image"/></figure>
HTML;

        if($this->page !== self::INDEX and $this->page !== self::NEWUSER) {
            $html .= $this->nav();
        }

        $html .= <<<HTML

</header>
HTML;
		return $html;
	}

    /**
     * Create the page &lt;nav&gt; area
     * @return string HTML
     */
	private function nav() {
        $links = [
            ['to'=>'enigma.php', 'text'=>'Enigma', 'page'=>self::ENIGMA],
            ['to'=>'settings.php', 'text'=>'Settings', 'page'=>self::SETTINGS],
            ['to'=>'batch.php', 'text'=>'Batch', 'page'=>self::BATCH],
            ['to'=>'sender.php', 'text'=>'Send', 'page'=>self::SEND],
            ['to'=>'receiver.php', 'text'=>'Receive', 'page'=>self::RECEIVE],
            ['to'=>'./', 'text'=>'Ausloggen', 'page'=>0]
        ];

        $html = <<<HTML
<nav><ul>
HTML;

        foreach($links as $link) {
            $to = $link['to'];
            $text = $link['text'];
            $selected = $this->page === $link['page'] ? ' class="selected"' : '';
            $html .= <<<HTML
<li$selected><a href="$to">$text</a></li>
HTML;
        }

        $html .= <<<HTML
</ul></nav>
HTML;

        return $html;
    }

    /**
     * Present the body of the page (page specific)
     * @return mixed HTML
     */
    abstract public function presentBody();

    /**
     * Present the Enigma machine (rotors only).
     * Used by Settings and Batch.
     */
	protected function presentEnigmaRotors() {
        $system = $this->getSystem();
        $enigma = $system->getEnigma();

        $rotor1 = $enigma->getRotorSetting(1);
        $rotor2 = $enigma->getRotorSetting(2);
        $rotor3 = $enigma->getRotorSetting(3);

        $html = <<<HTML
<div class="enigma" id="enigma">
<figure class="enigma"><img src="images/rotors.png" alt="Enigma Rotors" width="1024" height="580"></figure>
<p class="wheel wheel-s wheel-1">$rotor1</p>
<p class="wheel wheel-s wheel-2">$rotor2</p>
<p class="wheel wheel-s wheel-3">$rotor3</p>
</div>
HTML;

        return $html;
    }

    /**
     * Present the page footer
     * @return string HTML
     */
	public function presentFooter() {
		$html = <<<HTML
<footer>
	<p class="center"><img src="images/banner1-800.png" width="800" height="100" alt="Footer image"/></p>
</footer>
HTML;

		return $html;
	}

    /**
     * Present any error message for this page if one exists.
     * @return string HTML
     */
	public function presentMessage() {
	    if($this->system->getMessage($this->page) !== null) {
            return '<p class="message">' . $this->system->getMessage($this->page) . '</p>';
        }

        return '';
    }





    public function protect($site, $user) {
        if($user->isStaff()) {
            return true;
        }
//        if($user!==null){
//            return true;
//        }

        $this->protectRedirect = $site->getRoot() . "/";
        return false;
    }

    /**
     * Get any redirect page
     */
    public function getProtectRedirect() {
        return $this->protectRedirect;
    }

    /// Page protection redirect
    private $protectRedirect = null;




	private $page;              ///< The current page ID
	private $system;            ///< System object
	private $redirect = null;   ///< Optional redirect if we can't be here
}