<?php
declare(strict_types=1);

namespace modules\auth\dto;
use shared\interfaces\IDto;

class RegisterUserDto implements IDto
{
    private string $email;
    private string $full_name;
    private string $password;
    private string $role = "admin";

    public function __construct(string $email, string $full_name, string $password)
    {
        $this->email = $email;
        $this->full_name = $full_name;
        $this->password = $password;
    }

    public static function fromArray(array $raw_data): RegisterUserDto
    {
        return new RegisterUserDto(
            $raw_data['email'],
            $raw_data['full_name'],
            $raw_data['password'],
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
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->full_name;
    }

    public function toArray(): array{
        return [
            "email" => $this->email,
            "full_name" => $this->full_name,
            "password" => $this->password,
            "role" => $this->role
        ];
    }
}