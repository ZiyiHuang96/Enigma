<?php
/**
 * Created by PhpStorm.
 * User: huangziyi
 * Date: 2018/6/18
 * Time: 上午3:25
 */

namespace Enigma;


class SenderController extends Controller
{
    public function __construct(System $system, Site $site, $post)
    {
        parent::__construct($system);
        $this->setRedirect("../sender.php");

        //$system->setMessage(View::SEND,'');
        // Clear any error messages
        $system->clearMessages(View::SEND,'');


        $users = new Users($site);
        $input_message = strip_tags($post['search']);
        $input_message = str_replace(' ','',$input_message);
        if(isset($post['searcher'])){
            if(strlen($input_message)<3){
                //////////$system->setMessage(View::SEND,"Search strings must be at least 3 letters long");
                $this->setRedirect("../sender.php?e=".SenderView::EMPTYSEARCH);
            }
            else{
                //$result = $users->search($input_message);
                $this->setRedirect("../recipients.php?r=" . $input_message);
//                $re = $this->getRedirect();
            }
        }
        if(isset($post['remove'])){
            $id = $post['remove'];
            $this->getSystem()->removeRecipient($id);
            $this->setRedirect("../sender.php");
        }

        if(isset($post['set'])){
            $rotors = [];

            for($r=1; $r<=3; $r++) {
                $rotor = strip_tags($post["rotor-$r"]);
                $setting = strip_tags($post["initial-$r"]);
                $setting = strtoupper($setting);

                if(strlen($setting) !== 1 ||
                    strcmp($setting, 'A') < 0 || strcmp($setting, 'Z') > 0) {
                    $system->setMessage(View::SEND,"Invalid setting for rotor $r");
                    $this->setRedirect("../sender.php");
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
                $system->setMessage(View::SEND,'You are not allowed to use the same rotor more than once.');
                $this->setRedirect("../sender.php");
                return;
            }

            $system->setRotors($rotors);
            $this->setRedirect("../sender.php");
        }


        if(isset($post['encode'])){
            if(ctype_alpha($post['code']) and strlen($post['code'])==3){
                $this->setRedirect("../sender.php");
                $code=$post['code'];
                $system->set_code(strtoupper($code));
                //$this->getSystem()->set_code(strtoupper($code));
                // Then, assuming the three letters are in $code, we do:
                $c1 = $system->getEnigma()->pressed(strtoupper(substr($code, 0, 1)));
                $c2 = $system->getEnigma()->pressed(strtoupper(substr($code, 1, 1)));
                $c3 = $system->getEnigma()->pressed(strtoupper(substr($code, 2, 1)));

                $system->getEnigma()->setRotorSetting(1, $c1);
                $system->getEnigma()->setRotorSetting(2, $c2);
                $system->getEnigma()->setRotorSetting(3, $c3);


                //*****************************************
                $message = strip_tags($post['from']);
                $this->getSystem()->set_decode($message);
                $this->encode($message);
                $encoded = $this->getSystem()->get_encode();
                //var_dump($encoded);
                //*****************
                $system->reset();

            }
            else{
                $system->set_code($post['code']);
                $this->setRedirect("../sender.php?e=".SenderView::EMPTYINPUT);
                return;
            }

        }
        if(isset($post['cancel'])){
            $this->setRedirect("../sender.php");
        }


        if(isset($post['send'])){
            $ms = new Messages($site);
            $recipients = $this->getSystem()->getRecipients();
            if(empty($recipients)){
                $system->set_code('');
                $system->set_decode('');
                $system->set_send_right('');
            }
            else{
                foreach ($recipients as $id=>$recipient){
                    $row = array('id'=>'','sender'=>$system->getUser()->getId(),
                        'receiver'=>$recipient->getId(),
                        'date'=>date("Y-m-d H:i:s"),
                        'code'=>$system->get_code(),
                        'content'=>$system->get_encode());
                    $m = new Message($row);
                    $ms->insert($m);
                }
            }
            $system->resetRecipient();
            $this->setRedirect("../sender.php");
            $this->getSystem()->set_decode('');
            $this->getSystem()->set_encode('');
            $this->getSystem()->set_code('');
        }






    }

    private function encode($text) {
        $system = $this->getSystem();
        // $system->reset();

        $system->set_decode($text);

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
        //$system->setEncoded($encoded5);
        $this->getSystem()->set_encode($encoded5);
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