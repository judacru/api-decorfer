<?php

namespace App\Services;

use App\DTO\Product as Transform;
use App\Models\Product as Model;
use Exception;

/**
 * Servicio que maneja todos los usuarios en el sistema
 *
 * @author Juan Cruz
 *
 * @version 1.0
 */
class ProductService
{
    public const PRODUCT_KEY = 'messages.Product';

    public const ERROR_PRODUCT = 'messages.An error occurred while getting the product';

    public const ERROR_REGISTERING_PRODUCT = 'messages.There is a product registered with this name';

    public const ERROR_UPDATING_PRODUCT = 'messages.An error occurred while updating the product';

    public const ERROR_CREATING_PRODUCT = 'messages.An error occurred while creating the product';

    public const ERROR_NOT_ALLOWED = 'messages.You cannont have privileges to realize this action';

    /**
     * Registra un producto en la base de datos
     *
     * @throws Exception
     */
    public function create(Transform $data): Transform
    {
        $result = $this->exists($data->getName());
        if ($result) {
            throw new Exception(__(self::ERROR_REGISTERING_PRODUCT));
        }

        $result = Model::create($data->toCreate());
        $data->setId($result->id);

        return $data;
    }

    /**
     * Actualiza un producto en la base de datos
     *
     * @throws Exception
     */
    public function update(Transform $transform): Transform
    {
        $result = Model::find($transform->getId());
        if (is_null($result)) {
            throw new Exception(__(self::ERROR_REGISTERING_PRODUCT));
        }

        if ($result->name === $transform->getName() && $result->id !== $transform->getId()) {
            throw new Exception(__(self::ERROR_NOT_ALLOWED));
        }

        $result->update($transform->toUpdate());

        return $transform;
    }

    /**
     * Verifica si un producto ya existe en la base de datos
     */
    private function exists(string $name): bool
    {
        return Model::where('name', $name)->exists();
    }

    /**
     * Obtiene un producto por su id
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
     * inactiva un producto
     */
    public function inactivate(int $id): void
    {
        $result = Model::find($id);
        if (! is_null($result)) {
            $result->update(['active' => !$result->active]);
        }
    }

    /**
     * Obtiene todos los productos registradas en el sistema
     *
     * @param bool|null $active
     * @return array<Transform>
     */
    public function findAll(?bool $active = null): array
    {
        $rows = Model::select('*')
            ->when(!is_null($active), function ($query) use ($active) {
                return $query->where('active', $active);
            })
            ->orderBy('active', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        $results = [];
        foreach ($rows as $row) {
            $self = $this->transform($row);

            $results[] = $self;
        }

        return $results;
    }

    private function transform(Model $model): Transform
    {
        $self = new Transform();
        $self->setId($model['id']);
        $self->setName($model['name']);
        $self->setActive($model['active']);
        $self->setDescription($model['description']);
        $self->setPrice($model['price']);

        return $self;
    }
}
