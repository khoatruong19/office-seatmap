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
    public function save($data)
    {
        $sql = "insert into users (full_name, password, email) values (:full_name, :password, :email)";
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->execute([
            "email" => $data["email"],
            "full_name" => $data["full_name"],
            "password" => $data["password"],
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

    public function getByEmail(string $email) {
        $sql = "select * from users where email = :value limit 1";
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->execute([
            "value" => $email,
        ]);
        $result = $stmt->fetch();

        return $result ?? null;
    }
}