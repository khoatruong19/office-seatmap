<?php
declare(strict_types=1);

namespace modules\seat\dto;
use shared\interfaces\IDto;

class SwapUsersFromTwoSeatsDto implements IDto
{
    private int $office_id;
    private int $first_seat_id;
    private int $first_user_id;
    private int $second_seat_id;
    private int $second_user_id;
    public function __construct(int $office_id, int $first_seat_id, int $first_user_id, int $second_seat_id, int $second_user_id)
    {
        $this->office_id = $office_id;
        $this->first_seat_id = $first_seat_id;
        $this->first_user_id = $first_user_id;
        $this->second_seat_id = $second_seat_id;
        $this->second_user_id = $second_user_id;
    }
    public static function fromArray(array $raw_data): SwapUsersFromTwoSeatsDto
    {
        return new SwapUsersFromTwoSeatsDto(
            $raw_data['officeId'],
            $raw_data['firstSeatId'],
            $raw_data['firstUserId'],
            $raw_data['secondSeatId'],
            $raw_data['secondUserId']
        );
    }

    /**
     * @return int
     */
    public function getFirstSeatId(): int
    {
        return $this->first_seat_id;
    }

    /**
     * @return int
     */
    public function getOfficeId(): int
    {
        return $this->office_id;
    }

    /**
     * @return int
     */
    public function getFirstUserId(): int
    {
        return $this->first_user_id;
    }

    /**
     * @return int
     */
    public function getSecondSeatId(): int
    {
        return $this->second_seat_id;
    }

    /**
     * @return int
     */
    public function getSecondUserId(): int
    {
        return $this->second_user_id;
    }

}