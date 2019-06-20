<?php
require __DIR__ . "/../../vendor/autoload.php";

use Enigma\Enigma;

/** @file
 * Unit tests for the class Enigma
 * @cond 
 */
class EnigmaTest extends \PHPUnit_Framework_TestCase {
    const ALPHA = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L',
'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

    private $verbose = false;

	public function test_rotors() {
        $enigma = new Enigma();

        $this->assertEquals('A', $enigma->getRotorSetting(1));
        $this->assertEquals('A', $enigma->getRotorSetting(2));
        $this->assertEquals('A', $enigma->getRotorSetting(3));

        $enigma->setRotorSetting(1, 'X');
        $this->assertEquals('X', $enigma->getRotorSetting(1));

        $enigma->setRotorSetting(2, 'P');
        $this->assertEquals('P', $enigma->getRotorSetting(2));

        $enigma->setRotorSetting(3, 'D');
        $this->assertEquals('D', $enigma->getRotorSetting(3));

        // Default rotors are 1-3
        $this->assertEquals(1, $enigma->getRotor(1));
        $this->assertEquals(2, $enigma->getRotor(2));
        $this->assertEquals(3, $enigma->getRotor(3));

        $enigma->setRotor(1, 3);
        $enigma->setRotor(2, 5);
        $enigma->setRotor(3, 2);

        $this->assertEquals(3, $enigma->getRotor(1));
        $this->assertEquals(5, $enigma->getRotor(2));
        $this->assertEquals(2, $enigma->getRotor(3));

    }


    /**
     * @expectedException \Enigma\EnigmaException
     */
    public function test_duplicate_rotors() {
        $enigma = new Enigma();
        $enigma->setRotor(1, 3);
        $enigma->setRotor(2, 3);
        $enigma->setRotor(3, 3);

        $enigma->pressed('A');
    }

    public function test_many() {
        $enigma = new Enigma();

        for($i=0; $i<20; $i++) {
            $wheels = [1, 2, 3, 4, 5];
            shuffle($wheels);
            $code = $this->randomString(3);
            $w1 = $wheels[0];
            $w2 = $wheels[1];
            $w3 = $wheels[2];
            $this->echoIt("\n$w1 $w2 $w3 $code ");

            $str = $this->randomString(600);
            $this->set($enigma, $w1, $w2, $w3,
                substr($code, 0, 1),
                substr($code, 1, 1),
                substr($code, 2, 1));

            $encoded = $this->send($enigma, $str);

            $this->set($enigma, $w1, $w2, $w3,
                substr($code, 0, 1),
                substr($code, 1, 1),
                substr($code, 2, 1));

            $decoded = $this->send($enigma, $encoded);

            $this->assertEquals($decoded, $str);

        }

    }

    private function send(Enigma $enigma, $str) {
	    $result = '';
	    for($i=0; $i<strlen($str); $i++) {
	        $result .= $enigma->pressed(substr($str, $i, 1));
        }

        $encoded = $enigma->pressed('A');

        $this->echoIt("\n$str -> $result\n");
        return $result;
    }

    private function set(Enigma $enigma, $w1, $w2, $w3, $s1, $s2, $s3) {
        $enigma->setRotor(1, $w1);
        $enigma->setRotor(2, $w2);
        $enigma->setRotor(3, $w3);

        $enigma->setRotorSetting(1, $s1);
        $enigma->setRotorSetting(2, $s2);
        $enigma->setRotorSetting(3, $s3);
    }

    private function randomString($len) {
        $result = '';
        for($i=0; $i<$len; $i++) {
            $result .= self::ALPHA[mt_rand(0, 25)];
        }

        return $result;
    }

    private function echoIt($str) {
	    if($this->verbose) {
	        echo $str;
        }
    }
}

/// @endcond
