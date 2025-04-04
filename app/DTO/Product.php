<?php

namespace App\DTO;

use App\DTO\Common\Person;

class Product extends Registry
{
    use Person;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string|null
     */
    private ?string $description;

    /**
     * @var float
     */
    private float $price;

    /**
     * @var bool
     */
    private bool $active;

    public function __construct()
    {
        parent::__construct();
        $this->description = null;
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
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return void
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return void
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
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
     * @return array<string, mixed>
     */
    public function toCreate(): array
    {
        return [
            'id' => null,
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'price' => $this->getPrice(),
            'active' => true,
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
            'description' => $this->getDescription(),
            'price' => $this->getPrice(),
            'updatedby' => $this->getPerson(),
        ];
    }

    /**
     * @return Select
     */
    public function toSelect(): Select
    {
        $select = new Select();
        $select->setId($this->getId());
        $select->setName($this->getName());

        return $select;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'price' => $this->getPrice(),
            'active' => $this->getActive(),
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
        $self->setDescription($request['description'] ?? null);
        $self->setPrice($request['price']);
        return $self;
    }
}
