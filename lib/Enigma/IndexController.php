<?php
/**
 * Controller for the form on the main (index) page.
 * @author Charles B. Owen
 */

namespace Enigma;

/**
 * Controller for the form on the main (index) page.
 */

class IndexController extends Controller {
    /**
     * IndexController constructor.
     * @param System $system The System object
     * @param array $post $_POST
     */
    public function __construct(System $system, array &$session, array $post, Site $site) {
        parent::__construct($system);

        // Default will be to return to the home page
        $this->setRedirect("../");

        // Clear any error messages
        $system->clearMessages();


        $users = new Users($site);

        $email = strip_tags($post['name']);
        $password = strip_tags($post['password']);
        $user = $users->login($email, $password);
        $session[User::SESSION_NAME] = $user;

        if(!isset($post['name'])) {
            return;
        }

        $name = trim(strip_tags($post['name']));
        if($name === '') {
            $system->setMessage(View::INDEX,"Invalid Credentials");
            return;
        }
        else {
            if($user !== null) {
                $this->setRedirect("../enigma.php");
            }
            else{
                $system->setMessage(View::INDEX,"Invalid Credentials");
                return;
            }
        }

        $user_name = $user->getName();
        $system->setUser($user);
        $system->resetRecipient();
        $system->setDisplay(null);
        $system->set_decode('');
        $system->set_code('');

    }
}