<?php

namespace App\DTO;

use App\DTO\Common\Person;

class Remission extends Registry
{
    use Person;

    /**
     * @var string
     */
    private string $consecutive;

    /**
     * @var Customer
     */
    private Customer $customer;

    /**
     * @var float
     */
    private float $total;

    /**
     * @var int
     */
    private int $totalPackages;

    /**
     * @var array<RemissionDetail>
     */
    private array $details;

    public function __construct()
    {
        parent::__construct();
        $this->details = [];
    }

    /**
     * @return string
     */
    public function getConsecutive(): string
    {
        return $this->consecutive;
    }

    /**
     * @param  string  $consecutive
     * @return void
     */
    public function setConsecutive(string $consecutive): void
    {
        $this->consecutive = $consecutive;
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
     * @return int
     */
    public function getTotalPackages(): int
    {
        return $this->totalPackages;
    }

    /**
     * @param int $totalPackages
     * @return void
     */
    public function setTotalPackages(int $totalPackages): void
    {
        $this->totalPackages = $totalPackages;
    }

    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     * @return void
     */
    public function setCustomer(Customer $customer): void
    {
        $this->customer = $customer;
    }

    /**
     * @return array<RemissionDetail>
     */
    public function getDetails(): array
    {
        return $this->details;
    }

    /**
     * @param RemissionDetail[] ...$details
     * @return void
     */
    public function setDetails(RemissionDetail ...$details): void
    {
        $this->details = $details;
    }

    /**
     * @return array<string, mixed>
     */
    public function toCreate(): array
    {
        return [
            'id' => null,
            'consecutive' => $this->getConsecutive(),
            'total' => $this->getTotal(),
            'totalpackages' => $this->getTotalPackages(),
            'idcustomer' => $this->getCustomer()->getId(),
            'createdby' => $this->getPerson(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toUpdate(): array
    {
        return [
            'total' => $this->getTotal(),
            'totalpackages' => $this->getTotalPackages(),
            'idcustomer' => $this->getCustomer()->getId(),
            'updatedby' => $this->getPerson(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'consecutive' => $this->getConsecutive(),
            'total' => $this->getTotal(),
            'totalpackages' => $this->getTotalPackages(),
            'customer' => $this->getCustomer()->toArray(),
            'details' => array_map(function ($row) {
                return $row->toArray();
            }, $this->getDetails()),
            ...parent::toArray(),
        ];
    }

    /**
     * @param  array<string, mixed>  $request
     */
    public static function fromRequest(array $request): self
    {
        $customer = new Customer();
        $customer->setId($request['customer']);

        $total = 0;
        $packages = 0;
        $self = new self();
        $self->setConsecutive($request['consecutive']);
        $self->setCustomer($customer);
        $self->setDetails(
            ...array_map(function ($row) use (&$total, &$packages) {
                $total += $row['total'];
                $packages += $row['packages'];
                return RemissionDetail::fromRequest($row);
            }, $request['details'])
        );
        $self->setTotal($total);
        $self->setTotalPackages(intval($packages));

        return $self;
    }
}
