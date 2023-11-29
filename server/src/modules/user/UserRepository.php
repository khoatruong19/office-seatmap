<?php

namespace modules\user;
use core\HttpStatus;
use core\Model;
use shared\exceptions\ResponseException;
use shared\interfaces\IRepository;
use PDO;

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

        $inserted_id = $this->database->getConnection()->lastInsertId();
        return $inserted_id;
    }

    public function findOne(string $field, string $value) {
        $allowed_fields = ['username', 'email', 'id'];

        if(!in_array($field, $allowed_fields)) {
            throw new ResponseException(HttpStatus::$BAD_REQUEST,"Field is not allowed!");
        }

        $sql = "select * from users where ".$field." = :value limit 1";
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->execute([
            "value" => $value,
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?? null;
    }

    public function getRole(string $user_id) {
        $sql = "select * from users where id = :value limit 1";
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->execute([
            "value" => $user_id,
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['role'] : null;
    }
}