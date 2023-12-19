<?php

declare(strict_types=1);

namespace modules\seat;

use core\HttpStatus;
use modules\office\OfficeRepository;
use modules\seat\dto\CreateSeatDto;
use modules\seat\dto\SetUserToSeatDto;
use modules\seat\dto\SwapUsersFromTwoSeatsDto;
use modules\user\UserRepository;
use shared\enums\OfficeResponse;
use shared\enums\UserResponse;
use shared\exceptions\ResponseException;

class SeatService
{
    public function __construct(
        private SeatRepository $seatRepository,
        private OfficeRepository $officeRepository,
        private UserRepository $userRepository
    ) {
    }

    /**
     * @param string $field
     * @param string $value
     * @return mixed
     * @throws ResponseException
     */
    public function checkOfficeExists(string $field, string $value): mixed
    {
        $office = $this->officeRepository->findOne($field, $value);
        if (!$office) {
            throw new ResponseException(HttpStatus::$BAD_REQUEST, OfficeResponse::NOT_FOUND->value);
        }

        return $office;
    }

    /**
     * @param string $user_id
     * @return mixed
     * @throws ResponseException
     */
    public function checkUserExists(string $user_id): mixed
    {
        $user = $this->userRepository->findOne("id", $user_id);
        if (!$user) {
            throw new ResponseException(HttpStatus::$BAD_REQUEST, UserResponse::NOT_FOUND->value);
        }

        return $user;
    }

    /**
     * @param CreateSeatDto $create_seat_dto
     * @return bool|string
     * @throws ResponseException
     */
    public function create(CreateSeatDto $create_seat_dto): bool|string
    {
        $this->checkOfficeExists("id", strval($create_seat_dto->getOfficeId()));
        $existed_seat = $this->seatRepository->findByOfficeId(
            "label",
            $create_seat_dto->getLabel(),
            $create_seat_dto->getOfficeId()
        );
        if ($existed_seat) {
            return true;
        }

        $seat_entity = new SeatEntity(
            $create_seat_dto->getLabel(),
            $create_seat_dto->getPosition(),
            true,
            $create_seat_dto->getOfficeId()
        );
        return $this->seatRepository->create($seat_entity->toArray());
    }

    /**
     * @param string $office_id
     * @return bool|array
     */
    public function findAllByOfficeId(string $office_id): bool|array
    {
        $this->checkOfficeExists("id", $office_id);
        return $this->seatRepository->findAllByOfficeId($office_id);
    }

    /**
     * @param SetUserToSeatDto $set_user_to_seat_dto
     * @return bool
     * @throws ResponseException
     */
    public function setUserToSeat(SetUserToSeatDto $set_user_to_seat_dto): bool
    {
        $this->checkOfficeExists("id", strval($set_user_to_seat_dto->getOfficeId()));
        $this->checkUserExists(strval($set_user_to_seat_dto->getUserId()));
        $seatUsedByUserId = $this->seatRepository->findByUserId(
            $set_user_to_seat_dto->getUserId(),
            $set_user_to_seat_dto->getOfficeId()
        );
        if ($seatUsedByUserId) {
            $this->removeUserFromSeat($seatUsedByUserId['id']);
        }

        return $this->seatRepository->updateOne(
            strval($set_user_to_seat_dto->getId()),
            ["user_id" => $set_user_to_seat_dto->getUserId(), "available" => 0]
        );
    }

    /**
     * @param int $seat_id
     * @return bool
     */
    public function removeUserFromSeat(int $seat_id): bool
    {
        return $this->seatRepository->updateOne(strval($seat_id), ["user_id" => null, "available" => 1]);
    }

    /**
     * @param string $label
     * @param string $office_id
     * @return bool
     */
    public function deleteSeatByLabel(string $label, string $office_id): bool
    {
        return $this->seatRepository->deleteByLabel($label, $office_id);
    }

    /**
     * @param SwapUsersFromTwoSeatsDto $swap_users_from_two_seat_dto
     * @return void
     */
    public function swapUsersFromTwoSeats(SwapUsersFromTwoSeatsDto $swap_users_from_two_seat_dto): void
    {
        $this->checkUserExists(strval($swap_users_from_two_seat_dto->getFirstUserId()));
        $this->checkUserExists(strval($swap_users_from_two_seat_dto->getSecondUserId()));
        $this->checkOfficeExists("id", strval($swap_users_from_two_seat_dto->getOfficeId()));
        $this->seatRepository->updateOne(
            strval($swap_users_from_two_seat_dto->getFirstSeatId()),
            ["user_id" => $swap_users_from_two_seat_dto->getSecondUserId()]
        );
        $this->seatRepository->updateOne(
            strval($swap_users_from_two_seat_dto->getSecondSeatId()),
            ["user_id" => $swap_users_from_two_seat_dto->getFirstUserId()]
        );
    }
}