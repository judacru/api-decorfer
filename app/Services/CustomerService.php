<?php

namespace App\Services;

use App\DTO\Customer as Transform;
use App\Models\Customer as Model;
use Exception;

/**
 * Servicio que maneja todos los clientes en el sistema
 *
 * @author Juan Cruz
 *
 * @version 1.0
 */
class CustomerService
{
    public const CUSTOMER_KEY = 'messages.Customer';
    public const ERROR_CUSTOMER = 'messages.An error occurred while getting the customer';
    public const ERROR_REGISTERING_CUSTOMER = 'messages.There is a customer registered with this name';
    public const ERROR_UPDATING_CUSTOMER = 'messages.An error occurred while updating the customer';

    /**
     * Registra un cliente en la base de datos
     *
     * @param Transform $data
     * @return Transform
     * @throws Exception
     */
    public function create(Transform $data): Transform
    {
        $result = $this->exists($data->getEmail());
        if ($result) {
            throw new Exception(__(self::ERROR_REGISTERING_CUSTOMER));
        }

        $result = Model::create($data->toCreate());
        $data->setId($result->id);

        return $data;
    }

    /**
     * Actualiza un cliente en la base de datos
     *
     * @param Transform $data
     * @return Transform
     * @throws Exception
     */
    public function update(Transform $data): Transform
    {
        $result = Model::find($data->getId());
        if (is_null($result)) {
            throw new Exception(__(self::ERROR_UPDATING_CUSTOMER));
        }

        if ($result->email === $data->getEmail() && $result->id !== $data->getId()) {
            throw new Exception(__(self::ERROR_REGISTERING_CUSTOMER));
        }

        $result->update($data->toUpdate());

        return $data;
    }

    /**
     * Verifica si un cliente ya existe en la base de datos
     *
     * @param string $email
     * @return bool
     */
    private function exists(string $email): bool
    {
        return Model::where('email', $email)->exists();
    }

    /**
     * Obtiene un cliente por su id
     *
     * @param int $id
     * @return Transform|null
     */
    public function findById(int $id): ?Transform
    {
        $result = Model::find($id);
        if (! is_null($result)) {
            return $this->transform($result);
        }

        return null;
    }

    /**
     * Obtiene todos los clientes registrados en el sistema
     *
     * @return array<Transform>
     */
    public function findAll(): array
    {
        $rows = Model::orderBy('active', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        $results = [];
        foreach ($rows as $row) {
            $self = $this->transform($row);

            $results[] = $self;
        }

        return $results;
    }

    /**
     * inactiva un cliente
     *
     * @param int $id
     * @return void
     */
    public function inactivate(int $id): void
    {
        $result = Model::find($id);
        if (! is_null($result)) {
            $result->update(['active' => !$result->active]);
        }
    }

    /**
     * Transforma un modelo a un DTO
     * @param Model $model
     * @return Transform
     */
    private function transform(Model $model): Transform
    {
        $self = new Transform();
        $self->setId($model['id']);
        $self->setName($model['name']);
        $self->setIdentification($model['identification']);
        $self->setEmail($model['email']);
        $self->setPhone($model['phone']);
        $self->setCellPhone($model['cellphone']);
        $self->setAddress($model['address']);
        $self->setActive($model['active']);
        $self->setSpecial($model['special']);

        return $self;
    }
}
