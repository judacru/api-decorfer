<?php

namespace App\DTO;

use App\DTO\Common\Person;

class Customer extends Registry
{
    use Person;

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
     * @var array<Product>
     */
    private array $products;

    public function __construct()
    {
        parent::__construct();
        $this->special = false;
        $this->active = true;
        $this->products = [];
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
     * @return array<Product>
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @param Product[] ...$products
     * @return void
     */
    public function setProducts(Product ...$products): void
    {
        $this->products = $products;
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
            'active' => true,
            'special' => $this->isSpecial(),
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
            'phone' => $this->getPhone(),
            'cellphone' => $this->getCellPhone(),
            'address' => $this->getAddress(),
            'identification' => $this->getIdentification(),
            'special' => $this->isSpecial(),
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
            'phone' => $this->getPhone(),
            'cellphone' => $this->getCellPhone(),
            'address' => $this->getAddress(),
            'identification' => $this->getIdentification(),
            'active' => $this->isActive(),
            'special' => $this->isSpecial(),
            'products' => array_map(function ($row) {
                return $row->toArray();
            }, $this->getProducts()),
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
        $self->setSpecial($request['special'] ?? false);
        $self->setProducts(
            ...array_map(function ($row) {
                $product = new Product();
                $product->setId($row['id']);
                $product->setPrice($row['price']);
                return $product;
            }, $request['products'])
        );

        return $self;
    }
}
