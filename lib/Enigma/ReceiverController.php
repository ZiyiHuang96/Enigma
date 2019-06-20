<?php
/**
 * Created by PhpStorm.
 * User: huangziyi
 * Date: 2018/6/19
 * Time: 上午12:46
 */

namespace Enigma;


class ReceiverController extends Controller
{
    public function __construct(System $system, Site $site, $get, $post)
    {
        parent::__construct($system);
        $this->setRedirect("../receiver.php");


        if(isset($post['set'])){
            $rotors = [];

            for($r=1; $r<=3; $r++) {
                $rotor = strip_tags($post["rotor-$r"]);
                $setting = strip_tags($post["initial-$r"]);
                $setting = strtoupper($setting);

                if(strlen($setting) !== 1 ||
                    strcmp($setting, 'A') < 0 || strcmp($setting, 'Z') > 0) {
                    $system->setMessage(View::RECEIVE,"Invalid setting for rotor $r");
                    $this->setRedirect("../receiver.php?e=");
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
                $system->setMessage(View::RECEIVE,'You are not allowed to use the same rotor more than once.');
                $this->setRedirect("../receiver.php?e=");
                return;
            }

            $system->setRotors($rotors);
            $this->setRedirect("../receiver.php");
        }


        if(isset($post['message'])){
            $id = $post['message'];
            $messages = new Messages($site);
            $message = $messages->get($id);
            $code = $message->getCode();
            $c1 = $system->getEnigma()->pressed(strtoupper(substr($code, 0, 1)));
            $c2 = $system->getEnigma()->pressed(strtoupper(substr($code, 1, 1)));
            $c3 = $system->getEnigma()->pressed(strtoupper(substr($code, 2, 1)));
            $system->getEnigma()->setRotorSetting(1, $c1);
            $system->getEnigma()->setRotorSetting(2, $c2);
            $system->getEnigma()->setRotorSetting(3, $c3);

            $system->setDisplay($message);
            $content = $message->getContent();
            $this->decode($content);
            $decoded = $this->getSystem()->get_decode();
            $sde = $system->get_decode();
            $this->getSystem()->set_receive_left($decoded);
            //var_dump($decoded);
//            $system->set_receive_left($decoded);

            //$this->getSystem()->set_receive_left($decoded);
            $system->reset();

        }
        $this->setRedirect("../receiver.php");
        $this->getSystem()->set_encode('');
        $this->getSystem()->set_decode('');


    }

    private function decode($text) {
        $system = $this->getSystem();
        //   $system->reset();

        $system->setEncoded($text);
        //$system->set_encode($text);

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
        //$system->setDecoded($encoded5);
        $system->set_decode($encoded5);
        //return $encoded5;
    }
    private function send($str){
        $result = '';
        for($i=0; $i<strlen($str); $i++){
            $c = substr($str, $i, 1);
            $result.=$this->getSystem()->getEnigma()->pressed($c);
        }
        return $result;
    }

}