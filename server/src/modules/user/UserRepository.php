<?php

namespace modules\user;
use core\HttpStatus;
use core\Model;
use shared\exceptions\ResponseException;
use shared\interfaces\IRepository;

class UserRepository extends Model implements IRepository{
    /**
     * @throws ResponseException
     */
    public function save($entity)
    {
        $existedUsername = $this->findOne("username", $entity["username"]);

        if($existedUsername) {
            throw new ResponseException(HttpStatus::$BAD_REQUEST, "Username existed!");
        }

        $sql = "insert into users (username, password, email) values (:username, :password, :email)";
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->execute([
            "email" => $entity["email"],
            "username" => $entity["username"],
            "password" => $entity["password"],
        ]);

        $insertedId = $this->database->getConnection()->lastInsertId();
        return $insertedId;
    }

    public function findOne(string $field, string $value) {
        $allowedFields = ['username', 'email', 'id'];

        if(!in_array($field, $allowedFields)) {
            throw new ResponseException(HttpStatus::$BAD_REQUEST,"Field is not allowed!");
        }

        $sql = "select * from users where ".$field." = :value";
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->execute([
            "value" => $value,
        ]);
        $result = $stmt->fetch();

        return $result ?? null;
    }
}