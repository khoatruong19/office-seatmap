<?php
declare(strict_types=1);

namespace modules\seat;

class SeatEntity
{
    private string $label;
    private int $position;
    private bool $available;
    private int $office_id;

    public function __construct(string $label, int $position, bool $available, int $office_id)
    {
        $this->label = $label;
        $this->position = $position;
        $this->available = $available;
        $this->office_id = $office_id;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
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
    public function getOfficeId(): int
    {
        return $this->office_id;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            "label" => $this->label,
            "position" => $this->position,
            "available" => $this->available,
            "office_id" => $this->office_id
        ];
    }

}