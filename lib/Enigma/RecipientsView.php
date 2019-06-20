<?php
/**
 * Created by PhpStorm.
 * User: huangziyi
 * Date: 2018/6/18
 * Time: 上午3:58
 */

namespace Enigma;


class RecipientsView extends View
{
    private $user_info;
    private $name = null;
    private $site;
    public function __construct(System $system, Site $site, $get, $post)
    {
        parent::__construct($system, View::RECIPIENTS);
        $this->name = $_GET['r'];
        $user = new Users($site);
        $this->user_info = $user->search($this->name);
        $this->site = $site;

    }

    public function presentBody()
    {
        $users = new Users($this->site);
        $re = $users->search($_GET['r']);
        $r=$_GET['r'];

        $html=<<<HTML
<div class="body">
    <form method="post" action="post/recipients.php">
        <input type="hidden" name="search" value="$r">
        
        <div class="dialog receipients">
            <!--<p>Select a user to add to the list of recipients.</p >-->
            <!--<table>-->
HTML;
        if(empty($re)){
            $html.=<<<HTML
<p>Query returned no results!</p >
<p><input type="submit" name="cancel" value="Ok"></p >
HTML;

        }
        else{
            $html.=<<<HTML
<p>Select a user to add to the list of recipients.</p >
            <table>
HTML;

            foreach ($re as $u){
                $id = $u->getId();
                $name = $u->getName();

                $html.=<<<HTML
<tr>
            <td><input type="radio" name="recipient" value="$id"</td><td>$name</td>
            </tr>
HTML;
            }
            $html.=<<<HTML
            </table>
HTML;

            $html .= $this->presentMessage();
            $html.=<<<HTML
            <p><input type="submit" name="add" value="Add"> <input type="submit" name="cancel" value="Cancel">
            </p >
HTML;
            //$html .= $this->presentMessage();

            $html.=<<<HTML
        </div>
HTML;

        }
//        foreach ($re as $u){
//            $id = $u->getId();
//            $name = $u->getName();
//
//            $html.=<<<HTML
//<tr>
//            <td><input type="radio" name="recipient" value="$id"</td><td>$name</td>
//            </tr>
//HTML;
//        }
        $html.=<<<HTML

</div>
</form>
</div>
HTML;
        return $html;

    }

}