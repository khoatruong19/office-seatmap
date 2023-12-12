<?php
declare(strict_types=1);

namespace modules\office\dto;
use shared\interfaces\IDto;

class UpdateOfficeDto implements IDto
{
    private int $id;
    private string $name;
    private bool $visible;
    private array $seats;
    private string $blocks;
    private array $delete_seats;


    public function __construct(int $id, string $name, bool $visible, array $seats, string $blocks, array $delete_seats)
    {
        $this->id = $id;
        $this->name = $name;
        $this->visible = $visible;
        $this->seats = $seats;
        $this->blocks = $blocks;
        $this->delete_seats = $delete_seats;
    }
    public static function fromArray(array $raw_data): UpdateOfficeDto
    {
        return new UpdateOfficeDto(
            $raw_data['id'],
            $raw_data['name'],
            $raw_data['visible'],
            $raw_data['seats'],
            $raw_data['blocks'],
            $raw_data['delete_seats'],
        );
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getBlocks(): string
    {
        return $this->blocks;
    }

    /**
     * @return array
     */
    public function getSeats(): array
    {
        return $this->seats;
    }

    /**
     * @return bool
     */
    public function getVisible(): bool
    {
        return $this->visible;
    }

    /**
     * @return array
     */
    public function getDeleteSeats(): array
    {
        return $this->delete_seats;
    }
}