<?php

namespace App\Http\Controllers;

use App\DTO\Remission as Transform;
use App\Http\Requests\RemissionRequest as Request;
use App\Services\ProductService;
use App\Services\RemissionService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Judacru\Start\Controllers\RestController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controlador que maneja las las facturas
 *
 * @see RestController
 *
 * @author Juan Cruz
 *
 * @version 1.0
 */
class RemissionController extends RestController
{
    private RemissionService $remissionService;
    private UserService $userService;

    /**
     * @param RemissionService $remissionService
     * @param UserService $userService
     */
    public function __construct(RemissionService $remissionService, UserService $userService)
    {
        $this->remissionService = $remissionService;
        $this->userService = $userService;
    }

    /**
     * Registra una factura en el sistema
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function create(Request $request): JsonResponse|Response
    {
        try {
            $person = $this->userService->getCurrent();
            $transform = Transform::fromRequest($request->validated());
            $transform->setPerson($person);

            $result = $this->remissionService->create($transform);

            if (is_null($result->getId())) {
                throw new Exception(__(RemissionService::ERROR_CREATING_REMISSION));
            }

            $pdf = $this->remissionService->generatePDF($result->getId());

            $name = RemissionService::PDF_NAME;
            $path = storage_path("app/{$name}");
            $pdf->save($path);

            return $pdf->stream($name);
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

            $this->remissionService->update($transform);

            return $this->message(strval(__('messages.The invoice has been updated')));
        } catch (Exception $e) {
            Log::error($e);

            return $this->error($e->getMessage());
        }
    }

    /**
     * Obtiene todos las facturas registradas en el sistema
     *
     * @return JsonResponse
     */
    public function findAll(): JsonResponse
    {
        try {
            $result = $this->remissionService->findAll();

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

            $result = $this->remissionService->findById(intval($id));
            if (is_null($result)) {
                throw new Exception(__(RemissionService::ERROR_REMISSION));
            }

            return $this->ok($result->toArray());
        } catch (Exception $e) {
            Log::error($e);

            return $this->error($e->getMessage());
        }
    }

    /**
     * Obtiene el pdf de una remission
     * @param int|string $id
     * @return JsonResponse|Response
     */
    public function generatePDF(int|string $id): JsonResponse|Response
    {
        try {
            if (!is_numeric($id) || empty($id)) {
                throw new Exception(__(ProductService::ERROR_PRODUCT));
            }

            $pdf = $this->remissionService->generatePDF(intval($id));

            $name = RemissionService::PDF_NAME;
            $path = storage_path("app/{$name}");
            $pdf->save($path);

            /* $emailContent = View::make('email')->render();

            $customerEmail = 'ferchohc37@hotmail.com';

            Mail::send([], [], function (Message $message) use ($customerEmail, $emailContent, $path, $name) {
                $message->to($customerEmail)
                        ->subject('Factura de Remisión')
                        ->html($emailContent)
                        ->attach($path, [
                            'as' => $name,
                            'mime' => 'application/pdf',
                        ]);
            }); */
            return $pdf->stream($name);
        } catch (Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    /**
     * Obtiene el detalle de un producto registrado en el sistema
     *
     * @return JsonResponse
     */
    public function consecutive(): JsonResponse
    {
        try {
            $result = $this->remissionService->consecutive();

            return $this->message($result);
        } catch (Exception $e) {
            Log::error($e);

            return $this->error($e->getMessage());
        }
    }
}
