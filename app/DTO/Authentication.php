<?php

namespace App\DTO;

class Authentication
{
    /**
     * @var User
     */
    private User $user;

    /**
     * @var string
     */
    private string $token;

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return void
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return void
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [
            'user' => [
                'id' => $this->getUser()->getId(),
                'name' => $this->getUser()->getName(),
                'email' => $this->getUser()->getEmail(),
                'user' => $this->getUser()->getUser(),
            ],
            'token' => $this->getToken(),
        ];
    }

    /**
     * @param mixed $data
     * @param string $token
     * @return self
     */
    public static function fromModel($data, string $token): self
    {
        $user = new User();
        $user->setId($data['id']);
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setUser($data['user']);

        $self = new self();
        $self->setUser($user);
        $self->setToken($token);
        return $self;
    }
}
