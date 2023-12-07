<?php
declare( strict_types=1 );

namespace modules\seat;
use core\HttpStatus;
use core\Repository;
use shared\exceptions\ResponseException;
use shared\interfaces\IRepository;
use PDO;

class SeatRepository extends Repository implements IRepository{
    /**
     * @param array $data
     * @return false|string
     */
    public function create(array $data): bool|string
    {
        $sql = "INSERT INTO seats (label, position, available, office_id) VALUES (:label, :position, :available, :office_id)";
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
        $allowed_fields = ['label', 'id'];
        if(!in_array($field, $allowed_fields)) {
            throw new ResponseException(HttpStatus::$BAD_REQUEST,"Field is not allowed!");
        }

        $sql = "SELECT * FROM seats WHERE ".$field." = :value limit 1";
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
        $sql = "SELECT * FROM seats ORDER BY created_at DESC";
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAllByOfficeId(string $office_id): bool|array
    {
        $sql = "SELECT * FROM seats WHERE office_id = :value ORDER BY created_at DESC";
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->execute([
            "value" => $office_id,
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param string $seat_id
     * @param array $data
     * @return bool
     */
    public function updateOne(string $seat_id, array $data): bool
    {
        return true;
    }

    /**
     * @param string $seat_id
     * @return bool
     */
    public function delete(string $seat_id): bool
    {
        $sql = "DELETE FROM seats WHERE id = :id";
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->bindParam(':id', $seat_id);
        return $stmt->execute();
    }
}