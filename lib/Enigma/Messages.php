<?php
/**
 * Created by PhpStorm.
 * User: huangziyi
 * Date: 2018/6/18
 * Time: 下午5:34
 */

namespace Enigma;


class Messages extends Table
{
    public function __construct(Site $site)
    {
        parent::__construct($site, "message");
    }

    public function get($id){
        $sql=<<<SQL
select *
from $this->tableName
where id=?
SQL;
        $pdo=$this->pdo();
        $statement = $pdo->prepare($sql);
        $statement->execute(array($id));
        if($statement->rowCount()==0){
            //return [];
            return null;
        }
        return new Message($statement->fetch(\PDO::FETCH_ASSOC));
    }

    public function insert(Message $message){
        $sql=<<<SQL
insert into $this->tableName(id,sender,receiver,date,code,content)
values (?,?,?,?,?,?)
SQL;
        $pdo=$this->pdo();
        $statement = $pdo->prepare($sql);
        try{
            $statement->execute(array($message->getId(),$message->getSender(),$message->getReceiver(),
                $message->getDate(),$message->getCode(),$message->getContent()));
        }
        catch(\PDOException $e){
            return false;
        }
        return true;
    }

    public function receiveMessage($id){
        $sql=<<<SQL
select *
from $this->tableName
where receiver=?
SQL;
        $pdo=$this->pdo();
        $statement = $pdo->prepare($sql);
        try{
            $statement->execute(array($id));
        }
        catch (\PDOException $e){
            return [];
        }
        if($statement->rowCount()==0){
            return [];
        }
        $ms = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $result = [];
        foreach ($ms as $m){
            $result[] = new Message($m);
        }
        return $result;
    }

}