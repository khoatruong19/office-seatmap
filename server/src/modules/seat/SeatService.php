<?php
declare( strict_types=1 );

namespace modules\seat;

use modules\seat\dto\CreateSeatDto;
use modules\seat\dto\SetUserToSeatDto;

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
        $existed_seat = $this->seatRepository->findOneWithOfficeId("label", $create_seat_dto->getLabel(), $create_seat_dto->getOfficeId());
        if($existed_seat) return true;
        $seat_entity = new SeatEntity($create_seat_dto->getLabel(), $create_seat_dto->getPosition(), true, $create_seat_dto->getOfficeId());
        return $this->seatRepository->create($seat_entity->toArray());
    }

    public function findAllByOfficeId(string $office_id): bool|array
    {
        return $this->seatRepository->findAllByOfficeId($office_id);

    }

    public function setUserToSeat(SetUserToSeatDto $set_user_to_seat_dto){
        return $this->seatRepository->updateOne(strval($set_user_to_seat_dto->getId()), ["user_id" => $set_user_to_seat_dto->getUserId()]);
    }

    public function removeUserFromSeat(int $seat_id){
        return $this->seatRepository->updateOne(strval($seat_id), ["user_id" => null]);
    }
}