<?php

namespace App\DTO;

class Customer extends Registry
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $phone;

    /**
     * @var string
     */
    private string $cellPhone;

    /**
     * @var string
     */
    private string $address;

    /**
     * @var string
     */
    private string $identification;

    /**
     * @var string
     */
    private string $email;

    /**
     * @var bool
     */
    private bool $active;

    /**
     * @var bool
     */
    private bool $special;

    /**
     * @var float
     */
    private float $minimunValue;

    public function __construct()
    {
        parent::__construct();
        $this->special = false;
        $this->active = true;
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
    public function getCellPhone(): string
    {
        return $this->cellPhone;
    }

    /**
     * @param string $cellPhone
     * @return void
     */
    public function setCellPhone(string $cellPhone): void
    {
        $this->cellPhone = $cellPhone;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return void
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return void
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getIdentification(): string
    {
        return $this->identification;
    }

    /**
     * @param string $identification
     * @return void
     */
    public function setIdentification(string $identification): void
    {
        $this->identification = $identification;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
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
    public function isSpecial(): bool
    {
        return $this->special;
    }

    /**
     * @param bool $special
     * @return void
     */
    public function setSpecial(bool $special): void
    {
        $this->special = $special;
    }

    /**
     * @return float
     */
    public function getMinimunValue(): float
    {
        return $this->minimunValue;
    }

    /**
     * @param float $minimunValue
     * @return void
     */
    public function setMinimunValue(float $minimunValue): void
    {
        $this->minimunValue = $minimunValue;
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
            'phone' => $this->getPhone(),
            'cellphone' => $this->getCellPhone(),
            'address' => $this->getAddress(),
            'identification' => $this->getIdentification(),
            'minimunvalue' => $this->getMinimunValue(),
            'active' => true,
            'special' => $this->isSpecial(),
            'createdby' => 1,
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
            'phone' => $this->getPhone(),
            'cellphone' => $this->getCellPhone(),
            'address' => $this->getAddress(),
            'identification' => $this->getIdentification(),
            'special' => $this->isSpecial(),
            'minimunvalue' => $this->getMinimunValue(),
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
            'phone' => $this->getPhone(),
            'cellphone' => $this->getCellPhone(),
            'address' => $this->getAddress(),
            'identification' => $this->getIdentification(),
            'active' => $this->isActive(),
            'special' => $this->isSpecial(),
            'minimunvalue' => $this->getMinimunValue(),
            ...parent::toArray(),
        ];
    }

    /**
     * @param  array<string, mixed>  $request
     */
    public static function fromRequest(array $request): self
    {
        $self = new self();
        $self->setName($request['name']);
        $self->setIdentification($request['identification']);
        $self->setEmail($request['email']);
        $self->setPhone($request['phone']);
        $self->setCellPhone($request['cellphone']);
        $self->setAddress($request['address']);
        $self->setMinimunValue($request['minimunvalue']);
        $self->setSpecial($request['special']);

        return $self;
    }
}
