<?php

namespace App\Http\Controllers;

use App\DTO\Customer as Transform;
use App\Http\Requests\CustomerRequest as Request;
use App\Services\CustomerService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Judacru\Start\Controllers\RestController;

/**
 * Controlador que maneja los clientes
 *
 * @see RestController
 *
 * @author Juan Cruz
 *
 * @version 1.0
 */
class CustomerController extends RestController
{
    private CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * Registra un cliente en el sistema
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $transform = Transform::fromRequest($request->validated());
            $result = $this->customerService->create($transform);

            return $this->created([
                'id' => $result->getId(),
            ], strval(__('messages.The customer has been created')));
        } catch (Exception $e) {
            Log::error($e);

            return $this->error($e->getMessage());
        }
    }

    /**
     * Registra un cliente en el sistema
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $transform = Transform::fromRequest($request->validated());
            $transform->setId($id);

            $this->customerService->update($transform);

            return $this->message(strval(__('messages.The customer has been updated')));
        } catch (Exception $e) {
            Log::error($e);

            return $this->error($e->getMessage());
        }
    }

    /**
     * Obtiene todos los clientes registrados en el sistema
     */
    public function findAll(): JsonResponse
    {
        try {
            $result = $this->customerService->findAll();

            return $this->ok(array_map(function ($row) {
                return $row->toArray();
            }, $result));
        } catch (Exception $e) {
            Log::error($e);

            return $this->error($e->getMessage());
        }
    }

    /**
     * Obtiene el detalle de un cliente registrada en el sistema
     */
    public function detail(int|string $id): JsonResponse
    {
        try {
            if (! is_numeric($id) || empty($id)) {
                throw new Exception(__(CustomerService::ERROR_CUSTOMER));
            }

            $result = $this->customerService->findById(intval($id));
            if (is_null($result)) {
                throw new Exception(__(CustomerService::ERROR_CUSTOMER));
            }

            return $this->ok($result->toArray());
        } catch (Exception $e) {
            Log::error($e);

            return $this->error($e->getMessage());
        }
    }

    /**
     * Inactiva un cliente
     */
    public function inactivate(int|string $id): JsonResponse
    {
        try {
            if (! is_numeric($id) || empty($id)) {
                throw new Exception(__(CustomerService::ERROR_CUSTOMER));
            }

            $this->customerService->inactivate(intval($id));

            return $this->message(strval(__('messages.The customer has been inactivated')));
        } catch (Exception $e) {
            Log::error($e);

            return $this->error($e->getMessage());
        }
    }
}
