<?php
/**
 * Created by PhpStorm.
 * User: huangziyi
 * Date: 2018/6/18
 * Time: 上午5:06
 */

namespace Enigma;


class RecipientsController extends Controller
{
    private $site;
    public function __construct(System $system,Site $site,$get,$post)
    {
        parent::__construct($system);
        $this->setRedirect("../sender.php");
        $this->site = $site;
        if(isset($post['add'])){
            if(empty($post['recipient'])){
                $this->setRedirect('../recipients.php?r='.$post['search']);
                $system->setMessage(View::RECIPIENTS,'You must select a recipient or cancel.');
            }
            else{
                $id = $post['recipient'];
                $users = new Users($site);
                $user = $users->get($id);
//            $user_id = $user->getId();
                $system->addRecipient($user);
            }

            //$system->resetRecipient();
            //var_dump($system->getRecipients());
        }
    }

}