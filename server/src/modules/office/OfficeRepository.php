<?php
declare( strict_types=1 );

namespace modules\office;
use core\HttpStatus;
use core\Repository;
use shared\exceptions\ResponseException;
use shared\interfaces\IRepository;
use PDO;

class OfficeRepository extends Repository implements IRepository{
    /**
     * @param array $data
     * @return false|string
     */
    public function create(array $data): bool|string
    {
        $sql = "INSERT INTO offices (name, visible, blocks) VALUES (:name, :visible, :blocks)";
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
        $allowed_fields = ['name', 'id'];
        if(!in_array($field, $allowed_fields)) {
            throw new ResponseException(HttpStatus::$BAD_REQUEST,"Field is not allowed!");
        }

        $sql = "SELECT * from offices WHERE ".$field." = :value ";
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
        $sql = "SELECT * FROM offices ORDER BY created_at DESC";
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param string $office_id
     * @param array $data
     * @return bool
     */
    public function updateOne(string $office_id, array $data): bool
    {
        $sql = "UPDATE offices SET ";
        $setValues = "";
        foreach ($data as $column => $value) {
            $setValues .= "$column = :$column, ";
        }
        $setValues = rtrim($setValues, ', ') . " WHERE id = :id";
        $stmt = $this->database->getConnection()->prepare($sql.$setValues);
        $data['id'] = $office_id;
        return $stmt->execute($data);
    }

    /**
     * @param string $office_id
     * @return bool
     */
    public function delete(string $office_id): bool
    {
        $sql = "DELETE FROM offices WHERE id = :id";
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->bindParam(':id', $office_id);
        return $stmt->execute();
    }
}