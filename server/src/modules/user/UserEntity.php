<?php
declare(strict_types=1);

namespace modules\user;

class UserEntity
{
    private string $email;
    private string $full_name;
    private string $password;
    private string $role;
    private string | null $avatar;

    public function __construct(string $email, string $full_name, string $password, string $role, string | null $avatar = null)
    {
        $this->email = $email;
        $this->full_name = $full_name;
        $this->password = $password;
        $this->role = $role;
        $this->avatar = $avatar;
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
    public function getFullname(): string
    {
        return $this->full_name;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function getEmail(): string
    {
        return $this->email;
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
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    /**
     * @param $full_name
     * @return void
     */
    public function setFullname($full_name): void
    {
        $this->full_name = $full_name;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @param $email
     * @return void
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    /**
     * @param string $avatar
     */
    public function setAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function toArray(): array
    {
        return [
            "email" => $this->email,
            "full_name" => $this->full_name,
            "password" => $this->password,
            "role" => $this->role,
            "avatar" => $this->avatar
        ];
    }

}