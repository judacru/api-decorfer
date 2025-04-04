<?php

namespace App\Http\Controllers;

use App\DTO\Product as Transform;
use App\Http\Requests\ProductRequest as Request;
use App\Services\ProductService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Log;
use Judacru\Start\Controllers\RestController;

/**
 * Controlador que maneja los productos
 *
 * @see RestController
 *
 * @author Juan Cruz
 *
 * @version 1.0
 */
class ProductController extends RestController
{
    private ProductService $productService;
    private UserService $userService;

    /**
     * @param ProductService $productService
     * @param UserService $userService
     */
    public function __construct(ProductService $productService, UserService $userService)
    {
        $this->productService = $productService;
        $this->userService = $userService;
    }

    /**
     * Registra un producto en el sistema
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

            $result = $this->productService->create($transform);

            return $this->created([
                'id' => $result->getId(),
            ], strval(__('messages.The product has been created')));
        } catch (Exception $e) {
            Log::error($e);

            return $this->error($e->getMessage());
        }
    }

    /**
     * Registra un producto en el sistema
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

            $this->productService->update($transform);

            return $this->message(strval(__('messages.The product has been updated')));
        } catch (Exception $e) {
            Log::error($e);

            return $this->error($e->getMessage());
        }
    }

    /**
     * Obtiene todos los productos registrados en el sistema
     *
     * @return JsonResponse
     */
    public function findAll(HttpRequest $request): JsonResponse
    {
        try {
            $active = $request->input('active', null);
            $result = $this->productService->findAll($active);

            return $this->ok(array_map(function ($row) {
                return $row->toArray();
            }, $result));
        } catch (Exception $e) {
            Log::error($e);

            return $this->error($e->getMessage());
        }
    }

    /**
     * Obtiene todos los productos asociados al cliente
     *
     * @param int|string $id
     * @return JsonResponse
     */
    public function findAllByCustomer(int|string $id): JsonResponse
    {
        try {
            if (!is_numeric($id) || empty($id)) {
                throw new Exception(__(ProductService::ERROR_PRODUCT));
            }

            $result = $this->productService->findByCustomer(intval($id), true);

            return $this->ok(array_map(function ($row) {
                return $row->toArray();
            }, $result));
        } catch (Exception $e) {
            Log::error($e);

            return $this->error($e->getMessage());
        }
    }

    /**
     * Obtiene el detalle de un producto registrado en el sistema
     *
     * @param int|string $id
     * @return JsonResponse
     */
    public function detail(int|string $id): JsonResponse
    {
        try {
            if (!is_numeric($id) || empty($id)) {
                throw new Exception(__(ProductService::ERROR_PRODUCT));
            }

            $result = $this->productService->findById(intval($id));
            if (is_null($result)) {
                throw new Exception(__(ProductService::ERROR_PRODUCT));
            }

            return $this->ok($result->toArray());
        } catch (Exception $e) {
            Log::error($e);

            return $this->error($e->getMessage());
        }
    }

    /**
     * Inactiva un producto
     *
     * @param int|string $id
     * @return JsonResponse
     */
    public function inactivate(int|string $id): JsonResponse
    {
        try {
            if (!is_numeric($id) || empty($id)) {
                throw new Exception(__(ProductService::ERROR_PRODUCT));
            }

            $this->productService->inactivate(intval($id));

            return $this->message(strval(__('messages.The product has been inactivated')));
        } catch (Exception $e) {
            Log::error($e);

            return $this->error($e->getMessage());
        }
    }
}
