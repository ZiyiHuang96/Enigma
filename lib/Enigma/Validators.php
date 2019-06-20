<?php
/**
 * Created by PhpStorm.
 * User: huangziyi
 * Date: 2018/6/16
 * Time: 上午3:15
 */

namespace Enigma;


class Validators extends Table
{
    public function __construct(Site $site){
        parent::__construct($site, "validator");
    }

    /**
     * Create a new validator and add it to the table.
     * @param $userid User this validator is for.
     * @return The new validator.
     */
    public function newValidator($userid) {
        $validator = $this->createValidator();

        // Write to the table
        $sql=<<<SQL
insert into $this->tableName(validator, userid, date)
values(?,?,?)
SQL;
        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);
        $statement->execute(array($validator,$userid,date("Y-m-d H:i:s")));
        return $validator;
    }

    /**
     * Generate a random validator string of characters
     * @param $len Length to generate, default is 32
     * @returns Validator string
     */
    public function createValidator($len = 32) {
        $bytes = openssl_random_pseudo_bytes($len / 2);
        return bin2hex($bytes);
    }

    public function get($validator){
        $sql=<<<SQL
select userid from $this->tableName
where validator=?
SQL;
        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);
        $a = array($validator);
        try{
            $statement->execute($a);
        }
        catch(\PDOException $e){
            return null;
        }
        if($statement->rowCount()==0){
            return null;
        }
        return $statement->fetchAll(\PDO::FETCH_ASSOC)[0]['userid'];
    }



    /**
     * Remove any validators for this user ID.
     * @param $userid The USER ID we are clearing validators for.
     */
    public function remove($userid) {
        $sql=<<<SQL
DELETE FROM $this->tableName
WHERE  userid=?
SQL;
        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);
        $a = array($userid);
        try {
            $statement->execute($a);
        } catch(\PDOException $e) {
            // do something when the exception occurs...
            return false;
        }
        if($statement->rowCount()==0){
            return false;
        }
        return true;


    }
}