<?php

namespace App\Services;

use App\DTO\Role as Transform;
use App\Models\Role as Model;
use Illuminate\Support\Facades\Auth;

/**
 * Servicio que maneja los roles de los usuarios
 *
 * @author Juan Cruz
 *
 * @version 1.0
 */
class RoleService
{
    public const DEFAULT_ROLE = 'Administrador';

    public const CUSTOMER_ROLE = 'Cliente';

    public const ROLE_KEY = 'messages.Role';

    public const ERROR_ROLE = 'messages.An error occurred while getting the role';

    public const ERROR_ROLE_NOT_ALLOWED = 'messages.You cannont have privileges to realize this action';

    /**
     * Verifica si el role ya existe en el sistema
     */
    private function exists(string $name): bool
    {
        return Model::where('name', $name)->exists();
    }

    /**
     * Verifica si el role ya existe en el sistema
     */
    public function findByName(string $name): ?Transform
    {
        $result = Model::where('name', $name)->first();

        if (! is_null($result)) {
            return $this->transform($result);
        }

        return null;
    }

    /**
     * Obtiene el perfil del usuario autenticado
     */
    public function getCurrent(): ?Transform
    {
        $user = Auth::user();
        if (! is_null($user) && isset($user->role)) {
            $role = $user['role'];
            $this->transform($role);
        }

        return null;
    }

    /**
     * Obtiene todos los roles registrados e el sistema
     *
     * @return array<Transform>
     */
    public function findAll(bool $active): array
    {
        $rows = Model::orderBy('name', 'asc')
            ->get();

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->transform($row);
        }

        return $results;
    }

    private function transform(Model $model): Transform
    {
        $self = new Transform();
        $self->setId($model['id']);
        $self->setName($model['name']);
        $self->setDescription($model['description']);

        return $self;
    }

    /**
     * Inicializa el role por defecto del sistema
     */
    public function start(): void
    {
        $role = $this->exists(self::DEFAULT_ROLE);
        if (! $role) {
            $result = Model::create([
                'id' => null,
                'name' => self::DEFAULT_ROLE,
                'description' => 'Admin profile',
            ]);
        }

        $role = $this->exists(self::CUSTOMER_ROLE);
        if (! $role) {
            $result = Model::create([
                'id' => null,
                'name' => self::CUSTOMER_ROLE,
                'description' => 'Clients profile',
            ]);
        }
    }
}
