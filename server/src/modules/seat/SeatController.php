<?php

declare(strict_types=1);

namespace modules\seat;

use core\HttpStatus;
use core\Request;
use core\Response;
use core\Controller;
use modules\seat\dto\SetUserToSeatDto;
use modules\seat\dto\SwapUsersFromTwoSeatsDto;
use shared\enums\ParamKeys;
use shared\enums\SeatResponse;
use shared\exceptions\ResponseException;

class SeatController extends Controller
{
    public function __construct(
        public Request $request,
        public Response $response,
        private readonly SeatService $seatService
    ) {
    }

    /**
     * @return void
     * @throws ResponseException
     */
    public function setUser(): void
    {
        $this->requestBodyValidation(require_once "validation/setUser.php");
        $seat_id = $this->request->getIntParam(ParamKeys::SEAT_ID->value);
        $raw_data = $this->request->getBody();
        $raw_data['id'] = $seat_id;
        $set_user_to_seat = SetUserToSeatDto::fromArray($raw_data);
        $this->seatService->setUserToSeat($set_user_to_seat);
        $this->response->response(HttpStatus::$OK, SeatResponse::SET_USER_SUCCESS->value, null, $seat_id);
    }

    /**
     * @return void
     */
    public function removeUser(): void
    {
        $seat_id = $this->request->getIntParam(ParamKeys::SEAT_ID->value);
        $this->seatService->removeUserFromSeat($seat_id);
        $this->response->response(HttpStatus::$OK, SeatResponse::REMOVE_USER_SUCCESS->value, null, $seat_id);
    }

    /**
     * @return void
     * @throws ResponseException
     */
    public function swapUsers(): void
    {
        $this->requestBodyValidation(require_once "validation/swapUsers.php");
        $raw_data = $this->request->getBody();
        $swap_users_from_two_seat_dto = SwapUsersFromTwoSeatsDto::fromArray($raw_data);
        $this->seatService->swapUsersFromTwoSeats($swap_users_from_two_seat_dto);
        $this->response->response(HttpStatus::$OK, SeatResponse::SWAP_USERS_SUCCESS->value);
    }
}