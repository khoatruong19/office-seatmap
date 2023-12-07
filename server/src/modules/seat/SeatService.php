<?php
declare( strict_types=1 );

namespace modules\seat;

use modules\seat\dto\CreateSeatDto;

class SeatService
{
    public function __construct(private readonly SeatRepository $seatRepository)
    {
    }

    /**
     * @param CreateSeatDto $create_seat_dto
     * @return bool|string
     */
    public function create(CreateSeatDto $create_seat_dto): bool|string
    {
        $existed_seat = $this->seatRepository->findOne("label",$create_seat_dto->getLabel());
        if($existed_seat) return true;
        $seat_entity = new SeatEntity($create_seat_dto->getLabel(), $create_seat_dto->getPosition(), true, $create_seat_dto->getOfficeId());
        return $this->seatRepository->create($seat_entity->toArray());
    }

    /**
     * @return array|bool
     */
    public function findAll(): bool|array
    {
        return $this->seatRepository->findAll();
    }

    public function findAllByOfficeId(string $office_id): bool|array
    {
        return $this->seatRepository->findAllByOfficeId($office_id);
    }
}