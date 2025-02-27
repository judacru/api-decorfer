<?php

namespace App\DTO;

class Registry
{
    /**
     * @var int|null
     */
    private ?int $id;

    /**
     * @var int|null
     */
    private ?int $idpersonregister;

    /**
     * @var string|null
     */
    private ?string $createdAt;

    /**
     * @var string|null
     */
    private ?string $createdBy;

    /**
     * @var int|null
     */
    private ?int $idpersonedition;

    /**
     * @var string|null
     */
    private ?string $updatedAt;

    /**
     * @var string|null
     */
    private ?string $updatedBy;

    public function __construct()
    {
        $this->idpersonregister = null;
        $this->createdAt = null;
        $this->createdBy = null;
        $this->idpersonedition = null;
        $this->updatedAt = null;
        $this->updatedBy = null;
    }

    /**
     * @param string $createdAt
     * @return void
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    /**
     * @param string $createdBy
     * @return void
     */
    public function setCreatedBy(string $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return string|null
     */
    public function getCreatedBy(): ?string
    {
        return ! is_null($this->createdBy) ? trim($this->createdBy) : null;
    }

    /**
     * @param string $updatedAt
     * @return void
     */
    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    /**
     * @param string $updatedBy
     * @return void
     */
    public function setUpdatedBy(string $updatedBy): void
    {
        $this->updatedBy = $updatedBy;
    }

    /**
     * @return string|null
     */
    public function getUpdatedBy(): ?string
    {
        return ! is_null($this->updatedBy) ? trim($this->updatedBy) : null;
    }

    /**
     * @param int|null $idpersonregister
     * @return void
     */
    public function setIdPersonRegister(?int $idpersonregister): void
    {
        $this->idpersonregister = $idpersonregister;
    }

    /**
     * @return int|null
     */
    public function getIdPersonRegister(): ?int
    {
        return $this->idpersonregister;
    }

    /**
     * @param int|null $idpersonedition
     * @return void
     */
    public function setIdPersonEdition(?int $idpersonedition): void
    {
        $this->idpersonedition = $idpersonedition;
    }

    /**
     * @return int|null
     */
    public function getIdPersonEdition(): ?int
    {
        return $this->idpersonedition;
    }

    /**
     * @param int|null $id
     * @return Registry
     */
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'idpersonalregistro' => $this->getIdPersonRegister(),
            'createdAt' => $this->getCreatedAt(),
            'createdBy' => $this->getCreatedBy(),
            'idpersonaledicion' => $this->getIdPersonEdition(),
            'updatedAt' => ! empty($this->getUpdatedBy()) ? $this->getUpdatedAt() : null,
            'updatedBy' => $this->getUpdatedBy(),
        ];
    }
}
