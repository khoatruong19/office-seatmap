<?php
declare(strict_types=1);

namespace modules\office;

class OfficeEntity
{
    private string $name;
    private bool $visible;
    private string $blocks;

    public function __construct(string $name, bool $visible = true, string $blocks = "[]")
    {
        $this->name = $name;
        $this->visible = $visible;
        $this->blocks = $blocks;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            "name" => $this->name,
            "visible" => $this->visible ? 1 : 0,
            "blocks" => $this->blocks,
        ];
    }

}