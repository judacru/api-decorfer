<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest as Request;
use App\Services\AuthenticationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Judacru\Start\Controllers\RestController;

/**
 * Controlador que maneja la autenticación
 *
 * @package App\Http\Controllers
 * @see RestController
 * @group Autenticación
 * @author Juan Cruz
 * @version 1.0
 */
class AuthenticationController extends RestController
{
    /**
     * @var AuthenticationService
     */
    private AuthenticationService $authenticationService;

    /**
     * @param AuthenticationService $authenticationService
     */
    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    /**
     * Permite el inicio de sesión en el sistema
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $result = $this->authenticationService->login($data['user'], $data['password']);

            return $this->created($result->toArray(), strval(__('messages.The user is logged in')));
        } catch (Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    /**
     * Permite el cierre de sesión en el sistema
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            $this->authenticationService->logout();

            return $this->message(strval(__('messages.User is logged out')));
        } catch (Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }
}
