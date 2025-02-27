<?php

namespace App\Services;

use App\DTO\Customer;
use App\DTO\Role;
use App\DTO\User as Transform;
use App\Models\User as Model;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Servicio que maneja todos los usuarios en el sistema
 *
 * @author Juan Cruz
 * @version 1.0
 */
class UserService
{
    public const DEFAULT_USER = 'judacru';

    public const USER_KEY = 'messages.User';

    public const ERROR_USER = 'messages.An error occurred while getting the user';

    public const ERROR_REGISTERING_USER = 'messages.There is a user registered with this name';

    public const ERROR_UPDATING_USER = 'messages.An error occurred while updating the user';

    public const ERROR_CREATING_USER = 'messages.An error occurred while creating the user';

    public const ERROR_NOT_ALLOWED = 'messages.You cannont have privileges to realize this action';

    private RoleService $roleService;

    public function __construct(
        RoleService $roleService,
    ) {
        $this->roleService = $roleService;
    }

    /**
     * Registra un usuario en la base de datos
     *
     * @throws Exception
     */
    public function create(Transform $data): Transform
    {
        $result = $this->exists($data->getUser());
        if ($result) {
            throw new Exception(__(self::ERROR_REGISTERING_USER));
        }

        $toModel = $data->toCreate();

        $password = Hash::make($data->getPassword());
        $toModel['password'] = $password;

        $result = Model::create($toModel);
        $data->setId($result->id);

        return $data;
    }

    /**
     * Actualiza un usuario en la base de datos
     *
     * @throws Exception
     */
    public function update(Transform $transform): Transform
    {
        $result = Model::find($transform->getId());
        if (is_null($result)) {
            throw new Exception(__(self::ERROR_UPDATING_USER));
        }

        if ($result->system) {
            throw new Exception(__(self::ERROR_NOT_ALLOWED));
        }

        $result->update($transform->toUpdate());

        return $transform;
    }

    /**
     * Verifica si un usuario ya existe en la base de datos
     */
    private function exists(string $user): bool
    {
        return Model::where('user', $user)->exists();
    }

    /**
     * Obtiene un usuario por su id
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
     * Obtiene todos los usuarios registradas en el sistema
     *
     * @return array<Transform>
     */
    public function findAll(): array
    {
        $rows = Model::select(
            'users.*',
        )
            ->where('users.system', false)
            ->orderBy('users.user', 'asc')
            ->get();

        $results = [];
        foreach ($rows as $row) {
            $self = $this->transform($row);

            if (! is_null($row->role)) {
                $role = new Role();
                $role->setId($row->role->id);
                $role->setName($row->role->name);
                $role->setDescription($row->role->description);
                $self->setRole($role);
            }

            if (! is_null($row->customer)) {
                $customer = new Customer();
                $customer->setId($row->customer->id);
                $customer->setName($row->customer->name);
                $customer->setIdentification($row->customer->identification);
                $customer->setAddress($row->customer->address);
                $customer->setEmail($row->customer->email);
                $customer->setPhone($row->customer->phone);
                $customer->setCellphone($row->customer->cellphone);
                $customer->setActive($row->customer->active);
                $self->setCustomer($customer);
            }

            $results[] = $self;
        }

        return $results;
    }

    /**
     * Obtiene la cuenta de usuario autenticada
     *
     * @throws Exception
     */
    public function getCurrent(): int
    {
        $user = Auth::user();
        if (is_null($user)) {
            throw new Exception(__(self::ERROR_USER));
        }

        return intval($user->id);
    }

    private function transform(Model $model): Transform
    {
        $role = new Role();
        $role->setId($model['idrole']);
        $role->setName($model['role']['name']);
        $role->setDescription($model['role']['description']);

        $self = new Transform();
        $self->setId($model['id']);
        $self->setName($model['name']);
        $self->setEmail($model['email']);
        $self->setUser($model['user']);
        $self->setPassword($model['password']);
        $self->setActive($model['active']);
        $self->setSystem($model['system']);
        $self->setRole($role);

        return $self;
    }

    /**
     * Inicializa la cuenta de usuario por defecto
     */
    public function start(): void
    {
        $role = $this->roleService->findByName(RoleService::DEFAULT_ROLE);
        if (is_null($role)) {
            throw new Exception(__(RoleService::ERROR_ROLE));
        }

        $password = Hash::make(config('USER_PASSWORD', 'password'));
        $user = $this->exists(self::DEFAULT_USER);
        if (! $user) {
            Model::create([
                'id' => null,
                'name' => config('NAME', ''),
                'user' => config('NICK', ''),
                'email' => config('EMAIL', ''),
                'password' => $password,
                'idrole' => $role->getId(),
                'active' => true,
                'idcustomer' => null,
                'system' => true,
            ]);
        }
    }
}
