<?php
/**
 * Created by PhpStorm.
 * User: huangziyi
 * Date: 2018/6/16
 * Time: 上午5:25
 */

namespace Enigma;


class PasswordValidateView extends View
{
    private $validator;
    private $error = "";
    const VALIDATOR_NOT_MATCH='Invalid or unavailable validator';
    const EMAIL_NOT_MATCH_USER='Email address is not for a valid user';
    const EMAIL_NOT_MATCH_VALIDATOR='Email address does not match validator';
    const PASSWORD_NOT_MATCH='Passwords did not match';
    const PASSWORD_SHORT='Password too short';

    public function __construct(System $system, $get) {
        parent::__construct($system, View::PASSWORDVALIDATE);
        $system->clear();
        //$this->setTitle("Felis Password Entry");
        if(isset($get['e'])){
            $this->error = $get['e'];
        }
        $this->validator = strip_tags($get['v']);
    }


    public function presentBody(){
        $system = $this->getSystem();
        $html=<<<HTML
<div class="password">

    <header>
        <figure><img src="images/banner-800.png" width="800" height="357" alt="Header image"/></figure>
    </header>
    <div class="body">
        <form class="dialog" action="post/password-validate.php" method="post">
                <input type="hidden" name="validator" value="$this->validator">
                <div class="controls">
                    <p>
                        <label for="email">Email</label><br>
                        <input type="email" id="email" name="email" placeholder="Email">
                    </p>
                    <p>
                        <label for="password">Password:</label><br>
                        <input type="password" id="password" name="password" placeholder="password">
                    </p>
                    <p>
                        <label for="password2">Password (again):</label><br>
                        <input type="password" id="password2" name="password2" placeholder="password">
                    </p>
                    <p><button name="ok">Create Account</button></p>
                    <p><button name="cancel">Cancel</button></p>
HTML;
        $html.="<p>$this->error</p>";
        $html.=<<<HTML

                </div>
           
        </form>
    </div>
    <footer>
        <p class="center"><img src="images/banner1-800.png" width="800" height="100" alt="Footer image"/></p>
    </footer>
</div>
HTML;
        return $html;

    }
}