<?php
declare(strict_types=1);

namespace modules\user\dto;
use shared\interfaces\IDto;

class UpdateUserDto implements IDto
{
    private string $email;
    private string $full_name;
    private string $role;

    public function __construct(string $email, string $full_name, string $role)
    {
        $this->email = htmlspecialchars_decode($email, ENT_QUOTES);
        $this->full_name = htmlspecialchars_decode($full_name, ENT_QUOTES);
        $this->role = $role;
    }

    public static function fromArray(array $raw_data): UpdateUserDto
    {
        return new UpdateUserDto(
            $raw_data['email'],
            $raw_data['full_name'],
            $raw_data['role'],
        );
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->full_name;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }


}