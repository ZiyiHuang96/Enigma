<?php
/**
 * Simulation of a 3-rotor Enigma machine.
 */

namespace Enigma;


/**
 * Exception class for Enigma errors.
 */
class EnigmaException extends \Exception {

}

/**
 * Simulation of a 3-rotor Enigma machine.
 *
 * The convention used in this system is 1-3 is left to right, which
 * does differ from the normal notation of 1 on the right.
 */
class Enigma {
    /**
     * Enigma constructor.
     */
    public function __construct() {
        $this->clear();

        $this->wheels = [
            1=>['notch'=>'R', 'sub'=>'EKMFLGDQVZNTOWYHXUSPAIBRCJ'],
            2=>['notch'=>'F', 'sub'=>'AJDKSIRUXBLHWTMCQGZNPYFVOE'],
            3=>['notch'=>'W', 'sub'=>'BDFHJLCPRTXVZNYEIWGAKMUSQO'],
            4=>['notch'=>'K', 'sub'=>'ESOVPZJAYQUIRHXLNFTGKDCMWB'],
            5=>['notch'=>'A', 'sub'=>'VZBRGITYUPSDNHLXAWMJQOFECK']
        ];
    }

    /**
     * Clear the Enigma machine to the default initial state
     */
    public function clear() {
        $this->rotors = [
            1=>['rotor'=>1, 'setting'=>'A'],
            2=>['rotor'=>2, 'setting'=>'A'],
            3=>['rotor'=>3, 'setting'=>'A']
        ];
    }

    /**
     * Get a rotor current setting.
     * @param $rotor int Rotor position 1-3
     * @return string Character A-Z
     * @throws EnigmaException if invalid input.
     */
    public function getRotorSetting($rotor) {
        $rotor = (int)$rotor;    // Ensure it is an integer
        if(!isset($this->rotors[$rotor])) {
            throw new EnigmaException("Invalid rotor number $rotor passed to getRotorSetting.");
        }

        return $this->rotors[$rotor]['setting'];
    }

    /**
     * Set a rotor current setting
     * @param $rotor int Rotor position 1-3
     * @param $setting string Character A-Z
     * @throws EnigmaException if invalid input
     */
    public function setRotorSetting($rotor, $setting) {
        $rotor = (int)$rotor;    // Ensure it is an integer
        if(!isset($this->rotors[$rotor])) {
            throw new EnigmaException("Invalid rotor number $rotor passed to setRotorSetting.");
        }

        // Ensure the setting is A to Z only
        if(strlen($setting) !== 1 ||
            strcmp($setting, 'A') < 0 ||
            strcmp($setting, 'Z') > 0) {
            throw new EnigmaException("Invalid rotor setting '$setting' passed to setRotorSetting.");
        }

        $this->rotors[$rotor]['setting'] = $setting;
    }

    /**
     * Get the install rotor for a given rotor position
     * @param $rotor int Rotor position 1-3
     * @return int The currently set rotor 1-5
     * @throws EnigmaException if invalid input
     */
    public function getRotor($rotor) {
        $rotor = (int)$rotor;    // Ensure it is an integer
        if(!isset($this->rotors[$rotor])) {
            throw new EnigmaException("Invalid rotor number $rotor passed to getRotor.");
        }

        return $this->rotors[$rotor]['rotor'];
    }

    /**
     * Set the install rotor for a given rotor position.
     *
     * Notice: Rotors must be unique. You cannot use rotor 2 in two positions.
     * @param $rotor int Rotor position 1-3
     * @param $setting int Rotor number 1-5
     * @throws EnigmaException if invalid input
     */
    public function setRotor($rotor, $setting) {
        $rotor = (int)$rotor;    // Ensure it is an integer
        if(!isset($this->rotors[$rotor])) {
            throw new EnigmaException("Invalid rotor number $rotor passed to setRotor.");
        }

        $setting1 = $setting + 0;   // Ensure it is an integer
        if($setting1 < 1 || $setting1 > 5) {
            throw new EnigmaException("Invalid rotor number '$setting' passed to setRotor.");
        }

        $this->rotors[$rotor]['rotor'] = $setting;
    }

    /**
     * Handle a keypress on the Enigma machine
     * @param $key string Key value A-Z
     * @return string Encoded or decoded key value A-Z
     * @throws EnigmaException if invalid input
     */
    public function pressed($key) {
        // Error check
        $w1 = $this->getRotor(1);
        $w2 = $this->getRotor(2);
        $w3 = $this->getRotor(3);
        if($w1 == $w2 || $w1 == $w3 || $w2 == $w3) {
            throw new EnigmaException("Each rotor wheel must be distinct.");
        }

        // Ensure the key is A to Z only
        if(strlen($key) !== 1 ||
            strcmp($key, 'A') < 0 ||
            strcmp($key, 'Z') > 0) {
            throw new EnigmaException("Invalid key value '$key' passed to pressed.");
        }

        $this->advance();
        //print_r($this->rotors);

        $sequence = "\nPressed: $key";
        $code = $this->substitute(3, $key);
        $sequence .= " > $code";

        $code = $this->substitute(2, $code);
        $sequence .= " > $code";

        $code = $this->substitute(1, $code);
        $sequence .= " > $code";

        // Reflection
        $code = $this->reflection($code);
        $sequence .= " > $code";

        $code = $this->reverseSubstitute(1, $code);
        $sequence .= " > $code";

        $code = $this->reverseSubstitute(2, $code);
        $sequence .= " > $code";

        $code = $this->reverseSubstitute(3, $code);
        $sequence .= " > $code";

        return $code;
    }

    /**
     * Advance the wheels.
     *
     * This advances the Enigma wheels and is called by pressed for each
     * character to translate.
     */
    private function advance() {
        $next = $this->next($this->rotors[3]['setting']);
        $this->rotors[3]['setting'] = $next;

        $wheel = $this->wheel(3);
        if($next != $wheel['notch']) {
            // We are done
            return;
        }

        $next = $this->next($this->rotors[2]['setting']);
        $this->rotors[2]['setting'] = $next;

        $wheel = $this->wheel(2);
        if($next != $wheel['notch']) {
            // We are done
            return;
        }

        $next = $this->next($this->rotors[1]['setting']);
        $this->rotors[1]['setting'] = $next;
    }

    /**
     * Handle the substitution for a wheel in the forward
     * direction.
     * @param $rotor int Rotor number 1-3
     * @param $char string Character to substitute
     * @return string Substituted value
     */
    private function substitute($rotor, $char) {
        // Get the cipher for this wheel
        $cipher = $this->wheel($rotor)['sub'];

        // And the setting of the wheel
        $setting = $this->keyToIndex($this->rotors[$rotor]['setting']);
        if($rotor < 3) {
            // Other than the right-most wheel, where the input comes
            // from is offset by the wheel to the right
            $setting -= $this->keyToIndex($this->rotors[$rotor+1]['setting']);
        }

        $index = ($this->keyToIndex($char) + 26 + $setting) % 26;
        return substr($cipher, $index, 1);
    }

    /**
     * Handle the substitution for a wheel in the reverse
     * direction.
     * @param $rotor int Rotor number 1-3
     * @param $char string Character to substitute
     * @return string Substituted value
     */
    private function reverseSubstitute($rotor, $char) {
        $cipher = $this->wheel($rotor)['sub'];
        $setting = $this->keyToIndex($this->rotors[$rotor]['setting']);
        $char = $this->indexToKey(($this->keyToIndex($char) + $setting) % 26);

        for($index=0; $index<strlen($cipher); $index++) {
            if($char === substr($cipher, $index, 1)) {
                break;
            }
        }

        $index = ($index + 26 - $setting) % 26;
        return $this->indexToKey($index);
    }

    /**
     * Handle reflection
     * @param $char string Character that is reflected
     * @return string Reflected character
     */
    private function reflection($char) {
        $reflect = 'YRUHQSLDPXNGOKMIEBFZCWVJAT';
        $setting = -$this->keyToIndex($this->rotors[1]['setting']);

        $index = ($this->keyToIndex($char) + 26 + $setting) % 26;
        return substr($reflect, $index, 1);
    }

    /**
     * Given a key value, indicate what the next key value will be.
     * @param $key string Key value
     * @return string Next key value
     */
    private function next($key) {
        $a = ord('A');

        $index = ord($key) - $a;
        $index = ($index + 1) % 26;
        return chr($index + $a);
    }

    /**
     * Convert a key from a letter to an index 0-25
     * @param $key string Character A-Z
     * @return int Integer 0-25
     */
    private function keyToIndex($key) {
        return ord($key) - ord('A');
    }

    /**
     * Convert an index 0-25 back into a key
     * @param $index int Index 0-25
     * @return string Character
     */
    private function indexToKey($index) {
        return chr($index + ord('A'));
    }

    /**
     * @param $rotor Rotor number 1-3
     * @return array with keys 'notch' and 'sub' that defines a wheel.
     *
     * Example: ['notch'=>'R', 'sub'=>'EKMFLGDQVZNTOWYHXUSPAIBRCJ']
     */
    private function wheel($rotor) {
        return $this->wheels[$this->rotors[$rotor]['rotor']];
    }

    private $rotors;        ///< The current three installed rotors
    private $wheels;        ///< The five wheel descriptions
}