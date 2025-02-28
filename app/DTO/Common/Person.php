<?php

namespace App\DTO\Common;

trait Person
{
    /**
     * @var int
     */
    private int $person;

    /**
     * @return int
     */
    public function getPerson(): int
    {
        return $this->person;
    }

    /**
     * @param int $person
     * @return void
     */
    public function setPerson(int $person): void
    {
        $this->person = $person;
    }
}
