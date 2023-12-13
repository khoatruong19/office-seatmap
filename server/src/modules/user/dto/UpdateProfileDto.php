<?php
declare(strict_types=1);

namespace modules\user\dto;
use shared\interfaces\IDto;

class UpdateProfileDto implements IDto
{
    private string $full_name;
    public function __construct(string $full_name)
    {
        $this->full_name = $full_name;
    }

    /**
     * @param array $raw_data
     * @return UpdateProfileDto
     */
    public static function fromArray(array $raw_data): UpdateProfileDto
    {
        return new UpdateProfileDto(
            $raw_data['full_name']
        );
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->full_name;
    }

}