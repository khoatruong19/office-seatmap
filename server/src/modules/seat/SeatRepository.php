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
     * @param string $field
     * @param string $value
     * @param int $office_id
     * @return mixed
     * @throws ResponseException
     */
    public function findByOfficeId(string $field, string $value, int $office_id): mixed
    {
        $allowed_fields = ['label', 'id'];
        if(!in_array($field, $allowed_fields)) {
            throw new ResponseException(HttpStatus::$BAD_REQUEST,"Field is not allowed!");
        }

        $sql = "SELECT * FROM seats WHERE ".$field." = :value AND office_id = :office_id limit 1";
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->execute([
            "value" => $value,
            "office_id" => $office_id
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?? null;
    }

    /**
     * @param int $user_id
     * @param int $office_id
     * @return mixed|null
     */
    public function findByUserId(int $user_id, int $office_id){
        $sql = "SELECT * FROM seats WHERE user_id = :user_id AND office_id = :office_id limit 1";
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->execute([
            "user_id" => $user_id,
            "office_id" => $office_id
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?? null;
    }

    /**
     * @param string $office_id
     * @return bool|array
     */
    public function findAllByOfficeId(string $office_id): bool|array
    {
        $sql = "SELECT seats.label, seats.position, seats.id, users.id as userId, users.avatar, users.role, users.full_name FROM 
                seats LEFT JOIN users ON seats.user_id=users.id  WHERE office_id = :value";
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
        $sql = "UPDATE seats SET ";
        $setValues = "";
        foreach ($data as $column => $value) {
            $setValues .= "$column = :$column, ";
        }
        $setValues = rtrim($setValues, ', ') . " WHERE id = :id";
        $stmt = $this->database->getConnection()->prepare($sql.$setValues);
        $data['id'] = $seat_id;
        return $stmt->execute($data);
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

    /**
     * @param string $label
     * @param string $office_id
     * @return bool
     */
    public function deleteByLabel(string $label, string $office_id): bool
    {
        $sql = "DELETE FROM seats WHERE label = :label AND office_id = :office_id";
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->bindParam(':label', $label);
        $stmt->bindParam(':office_id', $office_id);
        return $stmt->execute();
    }
}