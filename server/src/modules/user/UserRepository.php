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
    public function create($data)
    {
        $sql = "INSERT INTO users (full_name, password, email) VALUES (:full_name, :password, :email)";
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

        $sql = "SELECT * FROM users WHERE ".$field." = :value limit 1";
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->execute([
            "value" => $value,
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ?? null;
    }

    public function findAll() {
        $sql = "SELECT id, role, full_name, avatar, created_at, updated_at FROM users";
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
    }

    public function updateOne(string $user_id, array $data) {
        $sql = "UPDATE users SET ";
        foreach ($data as $column => $value) {
            $sql .= "$column = :$column, ";
        }
        $sql = rtrim($sql, ', ') . " WHERE id = :id";

        $stmt = $this->database->getConnection()->prepare($sql);

        foreach ($data as $column => $value) {
            $stmt->bindParam(":$column", $value);
        }

        $stmt->bindParam(':id', $user_id);

        $result = $stmt->execute();

        return $result;
    }

    public function delete(string $user_id) {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->bindParam(':id', $user_id);
        $result = $stmt->execute();

        return $result;
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