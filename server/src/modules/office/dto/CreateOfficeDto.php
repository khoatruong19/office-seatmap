<?php
declare(strict_types=1);

namespace modules\office\dto;
use shared\interfaces\IDto;

class CreateOfficeDto implements IDto
{
    private string $name;
    public function __construct(string $name)
    {
        $this->name = $name;
    }
    public static function fromArray(array $raw_data): CreateOfficeDto
    {
        return new CreateOfficeDto(
            $raw_data['name'],
        );
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}