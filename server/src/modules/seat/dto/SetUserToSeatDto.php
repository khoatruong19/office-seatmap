<?php
declare(strict_types=1);

namespace modules\seat\dto;
use shared\interfaces\IDto;

class SetUserToSeatDto implements IDto
{
    private int $id;
    private int $user_id;
    public function __construct(int $id, int $user_id)
    {
        $this->id = $id;
        $this->user_id = $user_id;
    }
    public static function fromArray(array $raw_data): SetUserToSeatDto
    {
        return new SetUserToSeatDto(
            $raw_data['id'],
            $raw_data['user_id'],
        );
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

}