<?php
/**
 * Created by PhpStorm.
 * User: huangziyi
 * Date: 2018/6/18
 * Time: 下午5:34
 */

namespace Enigma;


class Message
{
    private $id;
    private $sender;
    private $receiver;
    private $date;
    private $code;
    private $content;

    public function __construct($row)
    {
        $this->id = $row['id'];
        $this->sender = $row['sender'];
        $this->receiver = $row['receiver'];
        $this->date = $row['date'];
        $this->code = $row['code'];
        $this->content = $row['content'];
    }

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
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @return mixed
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }


}