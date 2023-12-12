<?php
declare( strict_types=1 );

namespace modules\office;

use core\HttpStatus;
use modules\office\dto\CreateOfficeDto;
use modules\office\dto\UpdateOfficeDto;
use modules\seat\dto\CreateSeatDto;
use modules\seat\SeatService;
use shared\enums\OfficeResponse;
use shared\exceptions\ResponseException;

class OfficeService
{
    public function __construct(private readonly OfficeRepository $officeRepository, private readonly SeatService $seatService)
    {
    }

    /**
     * @param CreateOfficeDto $create_office_dto
     * @return bool|string
     * @throws ResponseException
     */
    public function create(CreateOfficeDto $create_office_dto): bool|string
    {
        $existing_office = $this->officeRepository->findOne("name", $create_office_dto->getName());
        if($existing_office) {
            throw new ResponseException(HttpStatus::$BAD_REQUEST, OfficeResponse::OFFICE_NAME_EXISTS->value);
        }

        $office_entity = new OfficeEntity($create_office_dto->getName());
        return $this->officeRepository->create($office_entity->toArray());
    }

    /**
     * @param UpdateOfficeDto $update_office_dto
     * @return bool
     * @throws ResponseException
     */
    public function update(UpdateOfficeDto $update_office_dto): bool
    {
        $office_id = strval($update_office_dto->getId());
        $office = $this->officeRepository->findOne("id", $office_id);
        if(!$office) throw new ResponseException(HttpStatus::$BAD_REQUEST, OfficeResponse::NOT_FOUND->value);

        $office_entity = new OfficeEntity($update_office_dto->getName(), $update_office_dto->getVisible(), $update_office_dto->getBlocks());
        $this->officeRepository->updateOne($office_id, $office_entity->toArray());
        $seats = $update_office_dto->getSeats();
        foreach ($seats as $seat){
            $seat['office_id'] = $update_office_dto->getId();
            $create_seat_dto = CreateSeatDto::fromArray($seat);
            $this->seatService->create($create_seat_dto);
        }
        return true;
    }

    /**
     * @param string $field
     * @param string $value
     * @return mixed
     * @throws ResponseException
     */
    public function findOne(string $field, string $value): mixed
    {
        $office = $this->officeRepository->findOne($field, $value);
        if(!$office) throw new ResponseException(HttpStatus::$BAD_REQUEST, OfficeResponse::NOT_FOUND->value);

        $seats = $this->seatService->findAllByOfficeId(strval($office['id']));
        $office['seats'] = $seats;
        return $office;
    }

    /**
     * @return array|bool
     */
    public function findAll(): bool|array
    {
        return $this->officeRepository->findAll();
    }

    /**
     * @return array|bool
     */
    public function findAllVisibleOffices(): bool|array
    {
        return $this->officeRepository->findAllVisibleOffices();
    }

    /**
     * @throws ResponseException
     */
    public function delete(string $office_id): void
    {
        $office = $this->officeRepository->findOne("id", $office_id);
        if(!$office) throw new ResponseException(HttpStatus::$BAD_REQUEST, OfficeResponse::NOT_FOUND->value);

        $is_deleted = $this->officeRepository->delete($office_id);
        if(!$is_deleted) throw new ResponseException(HttpStatus::$INTERNAL_SERVER_ERROR, OfficeResponse::DELETE_OFFICE_FAIL->value);
    }
}