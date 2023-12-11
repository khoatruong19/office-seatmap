<?php
declare( strict_types=1 );

namespace modules\seat;

use modules\seat\dto\CreateSeatDto;
use modules\seat\dto\SetUserToSeatDto;
use modules\seat\dto\SwapUsersFromTwoSeatsDto;

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
        $existed_seat = $this->seatRepository->findByOfficeId("label", $create_seat_dto->getLabel(), $create_seat_dto->getOfficeId());
        if($existed_seat) return true;
        $seat_entity = new SeatEntity($create_seat_dto->getLabel(), $create_seat_dto->getPosition(), true, $create_seat_dto->getOfficeId());
        return $this->seatRepository->create($seat_entity->toArray());
    }

    /**
     * @param string $office_id
     * @return bool|array
     */
    public function findAllByOfficeId(string $office_id): bool|array
    {
        return $this->seatRepository->findAllByOfficeId($office_id);
    }

    public function findByUserId(int $user_id){

    }

    /**
     * @param SetUserToSeatDto $set_user_to_seat_dto
     * @return bool
     */
    public function setUserToSeat(SetUserToSeatDto $set_user_to_seat_dto){
        $seatUsedByUserId = $this->seatRepository->findByUserId($set_user_to_seat_dto->getUserId(), $set_user_to_seat_dto->getOfficeId());
        if($seatUsedByUserId){
            $this->removeUserFromSeat($seatUsedByUserId['id']);
        }
        return $this->seatRepository->updateOne(strval($set_user_to_seat_dto->getId()), ["user_id" => $set_user_to_seat_dto->getUserId(), "available" => 0]);
    }

    /**
     * @param int $seat_id
     * @return bool
     */
    public function removeUserFromSeat(int $seat_id){
        return $this->seatRepository->updateOne(strval($seat_id), ["user_id" => null, "available" => 1]);
    }

    public function swapUsersFromTwoSeats(SwapUsersFromTwoSeatsDto $swap_users_from_two_seat_dto){
       $this->seatRepository->updateOne(strval($swap_users_from_two_seat_dto->getFirstSeatId()), ["user_id" => $swap_users_from_two_seat_dto->getSecondUserId()]);
       $this->seatRepository->updateOne(strval($swap_users_from_two_seat_dto->getSecondSeatId()), ["user_id" => $swap_users_from_two_seat_dto->getFirstUserId()]);
    }
}