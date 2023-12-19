<?php

declare(strict_types=1);

namespace modules\seat\dto;

use shared\interfaces\IDto;

class SetUserToSeatDto implements IDto
{
    private int $id;
    private int $user_id;
    private int $office_id;

    public function __construct(int $id, int $user_id, int $office_id)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->office_id = $office_id;
    }

    public static function fromArray(array $raw_data): SetUserToSeatDto
    {
        return new SetUserToSeatDto(
            $raw_data['id'],
            $raw_data['user_id'],
            $raw_data['office_id'],
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

    /**
     * @return int
     */
    public function getOfficeId(): int
    {
        return $this->office_id;
    }

}