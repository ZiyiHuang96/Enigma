<?php
/**
 * Created by PhpStorm.
 * User: huangziyi
 * Date: 2018/6/16
 * Time: 上午4:25
 */

namespace Enigma;


class Email
{
    public function mail($to, $subject, $message, $headers) {
        mail($to, $subject, $message, $headers);
    }
}