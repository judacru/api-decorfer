<?php

namespace App\DTO;

use App\DTO\Common\Person;

class User extends Registry
{
    use Person;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $user;

    /**
     * @var string
     */
    private string $email;

    /**
     * @var string
     */
    private string $password;

    /**
     * @var bool
     */
    private bool $active;

    /**
     * @var bool
     */
    private bool $system;

    /**
     * @var Role
     */
    private Role $role;

    /**
     * @var Customer|null
     */
    private ?Customer $customer;

    public function __construct()
    {
        parent::__construct();
        $this->customer = null;
        $this->system = false;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @param string $user
     * @return void
     */
    public function setUser(string $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return void
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return bool
     */
    public function getActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return void
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return bool
     */
    public function getSystem(): bool
    {
        return $this->system;
    }

    /**
     * @param bool $system
     * @return void
     */
    public function setSystem(bool $system): void
    {
        $this->system = $system;
    }

    /**
     * @return Role
     */
    public function getRole(): Role
    {
        return $this->role;
    }

    /**
     * @param Role $role
     * @return void
     */
    public function setRole(Role $role): void
    {
        $this->role = $role;
    }

    /**
     * @return Customer|null
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer|null $customer
     * @return void
     */
    public function setCustomer(?Customer $customer): void
    {
        $this->customer = $customer;
    }

    /**
     * @return array<string, mixed>
     */
    public function toCreate(): array
    {
        return [
            'id' => null,
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'user' => $this->getUser(),
            'active' => $this->getActive(),
            'system' => false,
            'idrole' => $this->getRole()->getId(),
            'idcustomer' => ! is_null($this->getCustomer()) ? $this->getCustomer()->getId() : null,
            'createdby' => $this->getPerson(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toUpdate(): array
    {
        return [
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'user' => $this->getUser(),
            'active' => $this->getActive(),
            'idrole' => $this->getRole()->getId(),
            'idcustomer' => ! is_null($this->getCustomer()) ? $this->getCustomer()->getId() : null,
            'updatedby' => $this->getPerson(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'user' => $this->getUser(),
            'active' => $this->getActive(),
            'role' => $this->getRole()->toArray(),
            'customer' => ! is_null($this->getCustomer()) ? $this->getCustomer()->toArray() : null,
            ...parent::toArray(),
        ];
    }

    /**
     * @param  array<string, mixed>  $request
     */
    public static function fromRequest(array $request): self
    {
        $role = new Role();
        $role->setId($request['role']);

        $self = new self();
        $self->setName($request['name']);
        $self->setEmail($request['email']);
        $self->setUser($request['user']);
        $self->setPassword($request['password']);
        $self->setActive($request['active']);
        $self->setRole($role);
        $self->setPerson($request['person']);

        if (isset($request['customer'])) {
            $customer = new Customer();
            $customer->setId($request['customer']);
            $self->setCustomer($customer);
        }

        return $self;
    }
}
