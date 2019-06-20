<?php
/**
 * Batch encoding/decoding controller.
 * @author Charles B. Owen
 */

namespace Enigma;

/**
 * Batch encoding/decoding controller.
 */
class BatchController extends Controller {
	/**
	 * BatchController constructor.
	 * @param System $system System object
	 * @param array $post $_POST
	 */
	public function __construct(System $system, array $post) {
		parent::__construct($system);

		// Default will be to return to the enigma page
		$this->setRedirect("../batch.php#enigma");

        if(!empty($post['encode'])) {
            $from = strip_tags($post['from']);
            $this->encode($from);
        }

        if(!empty($post['decode'])) {
            $to = strip_tags($post['to']);
            $this->decode($to);
        }

        if(!empty($post['reset'])) {
		    $system->reset();
        }
	}

    /**
     * Encode a message
     * @param $text string Message to encode
     */
	private function encode($text) {
	    $system = $this->getSystem();
	   // $system->reset();

        $system->setDecoded($text);

        $encoded = '';

        for($i=0; $i<strlen($text); $i++) {
            $ch = strtoupper(substr($text, $i, 1));
            if(strcmp($ch, 'A') >= 0 && strcmp($ch , 'Z') <= 0) {
                $encoded .= $this->send($ch);
            } else {
                switch($ch) {
                    case '.':
                        $encoded .= $this->send('X');
                        break;

                    case '0':
                        $encoded .= $this->send('NULL');
                        break;

                    case '1':
                        $encoded .= $this->send('EINZ');
                        break;

                    case '2':
                        $encoded .= $this->send('ZWO');
                        break;

                    case '3':
                        $encoded .= $this->send('DREI');
                        break;

                    case '4':
                        $encoded .= $this->send('VIER');
                        break;

                    case '5':
                        $encoded .= $this->send('FUNF');
                        break;

                    case '6':
                        $encoded .= $this->send('SEQS');
                        break;

                    case '7':
                        $encoded .= $this->send('SIEBEN');
                        break;

                    case '8':
                        $encoded .= $this->send('ACHT');
                        break;

                    case '9':
                        $encoded .= $this->send('NEUN');
                        break;
                }
            }

        }

        //
        // Split into substrings of 5 characters
        //
        $encoded5 = '';
        for($i=0; $i<strlen($encoded); $i+=5) {
            if(strlen($encoded5) > 0) {
                $encoded5 .= ' ';
            }

            $encoded5 .= substr($encoded, $i, 5);
        }
        $system->setEncoded($encoded5);
    }

    /**
     * Decode a message
     * @param $text string Message to decode
     */
    private function decode($text) {
        $system = $this->getSystem();
     //   $system->reset();

        $system->setEncoded($text);

        $decoded = '';

        for($i=0; $i<strlen($text); $i++) {
            $ch = strtoupper(substr($text, $i, 1));
            if(strcmp($ch, 'A') >= 0 && strcmp($ch , 'Z') <= 0) {
                $decoded .= $this->send($ch);
            }
        }

        //
        // Split into substrings of 5 characters
        //
        $encoded5 = '';
        for($i=0; $i<strlen($decoded); $i+=5) {
            if(strlen($encoded5) > 0) {
                $encoded5 .= ' ';
            }

            $encoded5 .= substr($decoded, $i, 5);
        }
        $system->setDecoded($encoded5);
    }

    /**
     * Send a known valid string to the Enigma
     * @param $str string String to send
     * @return string Transcoded version of the string.
     */
    private function send($str) {
	    $result = '';
        for($i=0; $i<strlen($str); $i++) {
            $ch = substr($str, $i, 1);
            $result .= $this->getSystem()->getEnigma()->pressed($ch);
        }

        return $result;
    }
}