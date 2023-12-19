<?php

declare(strict_types=1);

namespace modules\auth\dto;

use shared\interfaces\IDto;

class LoginUserDto implements IDto
{
    private string $email;
    private string $password;

    public function __construct(string $email, string $password)
    {
        $this->email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $this->password = $password;
    }

    public static function fromArray(array $raw_data): LoginUserDto
    {
        return new LoginUserDto(
            $raw_data['email'],
            $raw_data['password']
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
}