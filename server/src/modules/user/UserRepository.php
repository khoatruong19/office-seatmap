<?php
declare( strict_types=1 );

namespace modules\user;
use core\Repository;
use shared\exceptions\ResponseException;
use shared\helpers\FieldNotAllow;
use shared\interfaces\IRepository;
use PDO;

class UserRepository extends Repository implements IRepository{
    /**
     * @param array $data
     * @return false|string
     */
    public function create(array $data): bool|string
    {
        $sql = "INSERT INTO users (full_name, password, email, role, avatar) VALUES (:full_name, :password, :email, :role, :avatar)";
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->execute($data);
        return $this->database->getConnection()->lastInsertId();
    }

    /**
     * @param string $field
     * @param string $value
     * @return mixed|null
     * @throws ResponseException
     */
    public function findOne(string $field, string $value): mixed
    {
        FieldNotAllow::execute(['username', 'email', 'id'], $field);
        $sql =  sprintf('SELECT * FROM users WHERE %s = :value limit 1', $field);
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->execute([
            "value" => $value,
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?? null;
    }

    /**
     * @return array|false
     */
    public function findAll(): bool|array
    {
        $sql = "SELECT id, role, full_name, email, avatar, created_at, updated_at FROM users ORDER BY full_name ";
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param string $user_id
     * @param array $data
     * @return bool
     */
    public function updateOne(string $user_id, array $data): bool
    {
        $sql = "UPDATE users SET ";
        $setValues = "";
        foreach ($data as $column => $value) {
            $setValues .= "$column = :$column, ";
        }
        $setValues = rtrim($setValues, ', ') . " WHERE id = :id";
        $stmt = $this->database->getConnection()->prepare($sql.$setValues);
        $data['id'] = $user_id;
        return $stmt->execute($data);
    }

    /**
     * @param string $user_id
     * @return bool
     */
    public function delete(string $user_id): bool
    {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->bindParam(':id', $user_id);
        return $stmt->execute();
    }

    /**
     * @param string $user_id
     * @return mixed|null
     */
    public function getRole(string $user_id): mixed
    {
        $sql = "SELECT * FROM users WHERE id = :value limit 1";
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->execute([
            "value" => $user_id,
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['role'] : null;
    }
}