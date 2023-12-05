<?php
declare( strict_types=1 );

namespace modules\office;

use core\HttpStatus;
use modules\auth\JwtService;
use modules\auth\SessionManager;
use modules\user\UserService;
use shared\enums\EnumTypeJwt;
use shared\exceptions\ResponseException;
use modules\office\OfficeRepository;

class OfficeService
{
    public function __construct(private readonly OfficeRepository $officeRepository)
    {
    }

    /**
     * @param array $register_data
     * @return array
     * @throws ResponseException
     */
    public function create(array $create_data): array
    {
        $existing_office = $this->officeRepository->findOne("name", $create_data['name']);

        if($existing_office) {
            throw new ResponseException(HttpStatus::$BAD_REQUEST, "Office existed!");
        }

        $id = $this->officeRepository->create($create_data);

        return $id;
    }
}