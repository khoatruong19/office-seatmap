<?php

declare(strict_types=1);

namespace modules\user\dto;

use shared\interfaces\IDto;

class CreateUserDto implements IDto
{
    private string $email;
    private string $full_name;
    private string $password;
    private string $role;

    public function __construct(string $email, string $full_name, string $password, string $role)
    {
        $this->email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $this->full_name = $full_name;
        $this->password = $password;
        $this->role = $role;
    }

    public static function fromArray(array $raw_data): CreateUserDto
    {
        return new CreateUserDto(
            $raw_data['email'],
            $raw_data['full_name'],
            $raw_data['password'],
            $raw_data['role']
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

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}