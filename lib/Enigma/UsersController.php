<?php

namespace Enigma;

/**
 * Controller for the users page users.php
 * Utilized by post/users.php
 */
class UsersController {
    /**
     * UsersController constructor.
     * @param Site $site Site object
     * @param User $user Current user
     * @param array $post $_POST
     */
    public function __construct(Site $site, User $user, array $post) {
//        $root = $site->getRoot();
        $this->redirect = $site->getRoot() . "/users.php";
        $this->user = $user;
        $this->site = $site;
        $this->post = $post;
        if(isset($post['edit'])){
            $id = $post['user'];
            if($id === null){
                $this->redirect = $site->getRoot() . "/users.php";
            }
            else{
                $this->redirect = $site->getRoot() . "/user.php?id=$id";
            }
        }
        if(isset($post['add'])){
            $this->redirect = $site->getRoot() . "/user.php";
        }
    }

    /**
     * @return mixed
     */
    public function getRedirect() {
        return $this->redirect;
    }


    private $site;
    private $post;
    private $user;
    private $redirect; ///< Page we will redirect the user to.
}