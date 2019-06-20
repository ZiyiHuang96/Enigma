<?php
/**
 * Created by PhpStorm.
 * User: huangziyi
 * Date: 2018/6/12
 * Time: 上午4:55
 */

namespace Enigma;


class User
{
    private $id;		///< The internal ID for the user
    private $email;		///< Email address
    private $name; 		///< Name as last, first

    const ADMIN = "A";
    const STAFF = "S";
    const CLIENT = "C";
    const SESSION_NAME = 'user';
    /**
     * Constructor
     * @param $row Row from the user table in the database
     */
    public function __construct($row) {
        $this->id = $row['id'];
        $this->email = $row['email'];
        $this->name = $row['name'];
//        $this->phone = $row['phone'];
//        $this->address = $row['address'];
//        $this->notes = $row['notes'];
//        $this->joined = strtotime($row['joined']);
//        $this->role = $row['role'];
    }

    /**
     * Determine if user is a staff member
     * @return bool True if user is a staff member
     */
    public function isStaff() {
        return $this->role === self::ADMIN ||
            $this->role === self::STAFF;
    }

    public function isUser(){
        return $this->role === self::SESSION_NAME;
    }


    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $phone
     */




    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }



}