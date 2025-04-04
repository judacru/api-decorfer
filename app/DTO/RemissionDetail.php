<?php

namespace App\DTO;

use App\DTO\Common\Person;

class RemissionDetail extends Registry
{
    use Person;

    /**
     * @var string|null
     */
    private ?string $reference;

    /**
     * @var Product
     */
    private Product $product;

    /**
     * @var float
     */
    private float $price;

    /**
     * @var float
     */
    private float $total;

    /**
     * @var int
     */
    private int $packages;

    /**
     * @var int
     */
    private int $quantity;

    /**
     * @var int
     */
    private int $colors;

    /**
     * @var bool
     */
    private bool $minimum;

    /**
     * @var int|null
     */
    private ?int $return;

    public function __construct()
    {
        parent::__construct();
        $this->reference = null;
        $this->return = null;
    }

    /**
     * @return string|null
     */
    public function getReference(): ?string
    {
        return $this->reference;
    }

    /**
     * @param string|null $reference
     * @return void
     */
    public function setReference(?string $reference): void
    {
        $this->reference = $reference;
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        return $this->total;
    }

    /**
     * @param float $total
     * @return void
     */
    public function setTotal(float $total): void
    {
        $this->total = $total;
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
     * @return int
     */
    public function getPackages(): int
    {
        return $this->packages;
    }

    /**
     * @param int $packages
     * @return void
     */
    public function setPackages(int $packages): void
    {
        $this->packages = $packages;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return void
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return int
     */
    public function getColors(): int
    {
        return $this->colors;
    }

    /**
     * @param int $colors
     * @return void
     */
    public function setColors(int $colors): void
    {
        $this->colors = $colors;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     * @return void
     */
    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    /**
     * @return bool
     */
    public function isMinimum(): bool
    {
        return $this->minimum;
    }

    /**
     * @param bool $minimum
     * @return void
     */
    public function setMinimum(bool $minimum): void
    {
        $this->minimum = $minimum;
    }

    /**
     * @return int|null
     */
    public function getReturn(): ?int
    {
        return $this->return;
    }

    /**
     * @param int|null $return
     * @return void
     */
    public function setReturn(?int $return): void
    {
        $this->return = $return;
    }

    /**
     * @return array<string, mixed>
     */
    public function toCreate(int $remission): array
    {
        return [
            'id' => null,
            'idremission' => $remission,
            'idproduct' => $this->getProduct()->getId(),
            'total' => $this->getTotal(),
            'packages' => $this->getPackages(),
            'price' => $this->getPrice(),
            'quantity' => $this->getQuantity(),
            'reference' => $this->getReference(),
            'colors' => $this->getColors(),
            'minimum' => $this->isMinimum(),
            'return' => $this->getReturn(),
            'createdby' => $this->getPerson(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'product' => $this->getProduct()->toArray(),
            'total' => $this->getTotal(),
            'packages' => $this->getPackages(),
            'price' => $this->getPrice(),
            'quantity' => $this->getQuantity(),
            'reference' => $this->getReference(),
            'colors' => $this->getColors(),
            'minimum' => $this->isMinimum(),
            'return' => $this->getReturn(),
            ...parent::toArray(),
        ];
    }

    /**
     * @param  array<string, mixed>  $request
     */
    public static function fromRequest(array $request): self
    {
        $product = new Product();
        $product->setId($request['product']);

        $self = new self();
        $self->setId($request['id'] ?? null);
        $self->setTotal($request['total']);
        $self->setPackages($request['packages']);
        $self->setPrice($request['price']);
        $self->setQuantity($request['quantity']);
        $self->setReference($request['reference']);
        $self->setColors($request['colors']);
        $self->setMinimum($request['minimum']);
        $self->setReturn($request['return']);
        $self->setProduct($product);

        return $self;
    }
}
