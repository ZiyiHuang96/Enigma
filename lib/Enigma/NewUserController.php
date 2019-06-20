<?php
/**
 * Created by PhpStorm.
 * User: huangziyi
 * Date: 2018/6/17
 * Time: 下午4:24
 */

namespace Enigma;


class NewUserController extends Controller
{
    public function __construct(System $system, array $post, Site $site)
    {
        parent::__construct($system);

        $this->setRedirect("../");
        $system->clearMessages();

        if (isset($post['ok'])) {
            if (empty($post['name'])) {
                $system->setMessage(View::NEWUSER, "You must supply a name.");
                $this->setRedirect("../newuser.php");
            }
            elseif (empty($post['email'])) {
                $system->setMessage(View::NEWUSER, "You must supply an email address.");
                $this->setRedirect("../newuser.php");
            }
            else{
                $id = null;
                $email = strip_tags($post['email']);
                $name = strip_tags($post['name']);
                $row = array('id' => $id,
                    'email' => $email,
                    'name' => $name,
                    'password' => null
                );
                $editUser = new User($row);
                $users = new Users($site);
                if ($users->exists($email)){
                $system->setMessage(View::NEWUSER,"Email address already exist!");
                $this->setRedirect("../newuser.php");
                }
                elseif($id == null) {
                    $mailer = new Email();
                    $users->add($editUser, $mailer);
                    $this->setRedirect("../newuserpending.php");
                }

            }
            return;
        }

    }

}