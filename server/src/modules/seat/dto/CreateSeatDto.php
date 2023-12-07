<?php
declare(strict_types=1);

namespace modules\seat\dto;
use shared\interfaces\IDto;

class CreateSeatDto implements IDto
{
    private string $label;
    private int $position;
    private int $office_id;
    public function __construct(string $label, int $position, int $office_id)
    {
        $this->label = $label;
        $this->position = $position;
        $this->office_id = $office_id;
    }
    public static function fromArray(array $raw_data): CreateSeatDto
    {
        return new CreateSeatDto(
            $raw_data['label'],
            $raw_data['position'],
            $raw_data['office_id'],
        );
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @return int
     */
    public function getOfficeId(): int
    {
        return $this->office_id;
    }

}