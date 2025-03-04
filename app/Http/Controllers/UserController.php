<?php

namespace App\Http\Controllers;

use App\DTO\User as Transform;
use App\Http\Requests\UserRequest as Request;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Judacru\Start\Controllers\RestController;

/**
 * Controlador que maneja los usuarios
 *
 * @see RestController
 *
 * @author Juan Cruz
 *
 * @version 1.0
 */
class UserController extends RestController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Registra un usuario en el sistema
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $person = $this->userService->getCurrent();
            $transform = Transform::fromRequest($request->validated());
            $transform->setPerson($person);
            $result = $this->userService->create($transform);

            return $this->created([
                'id' => $result->getId(),
            ], strval(__('messages.The user has been created')));
        } catch (Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    /**
     * Registra un usuario en el sistema
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $person = $this->userService->getCurrent();
            $transform = Transform::fromRequest($request->validated());
            $transform->setPerson($person);
            $transform->setId($id);

            $this->userService->update($transform);

            return $this->message(strval(__('messages.The user has been updated')));
        } catch (Exception $e) {
            Log::error($e);

            return $this->error($e->getMessage());
        }
    }

    /**
     * Obtiene todos los usuarios registradas en el sistema
     *
     * @return JsonResponse
     */
    public function findAll(): JsonResponse
    {
        try {
            $result = $this->userService->findAll();

            return $this->ok(array_map(function ($row) {
                return $row->toArray();
            }, $result));
        } catch (Exception $e) {
            Log::error($e);

            return $this->error($e->getMessage());
        }
    }

    /**
     * Obtiene el detalle del usuario conectado
     *
     * @return JsonResponse
     */
    public function getUserMe(): JsonResponse
    {
        try {
            $result = $this->userService->getUserMe();

            return $this->ok($result->toArray());
        } catch (Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    /**
     * Obtiene el detalle de un usuario registrada en el sistema
     *
     * @param int|string $id
     * @return JsonResponse
     */
    public function detail(int|string $id): JsonResponse
    {
        try {
            if (! is_numeric($id) || empty($id)) {
                throw new Exception(__(UserService::ERROR_USER));
            }

            $result = $this->userService->findById(intval($id));
            if (is_null($result)) {
                throw new Exception(__(UserService::ERROR_USER));
            }

            return $this->ok($result->toArray());
        } catch (Exception $e) {
            Log::error($e);

            return $this->error($e->getMessage());
        }
    }
}
