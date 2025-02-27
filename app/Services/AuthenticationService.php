<?php

namespace App\Services;

use App\Models\User as Model;
use App\DTO\Authentication;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Servicio que maneja la autenticación en el sistema
 *
 * @package App\Services
 * @author Juan Cruz
 * @version 1.0
 */
class AuthenticationService
{
    public const AUTHENTICATION = 'messages.Authentication';
    public const ERROR_LOGIN = 'messages.An error occurred at login';
    public const ERROR_LOGUOT = 'messages.An error occurred at logout';
    public const ERROR_AUTHENTICATION = 'messages.The data provided is incorrect';

    /**
     * Permite el inicio de sesión en el sistema
     *
     * @param string $username
     * @param string $password
     * @return Authentication
     */
    public function login(string $username, string $password): Authentication
    {
        $user = Model::where('user', $username)->first();
        if (is_null($user)) {
            throw new Exception(__(self::ERROR_AUTHENTICATION));
        }

        if ($user['active'] != true) {
            throw new Exception(__(self::ERROR_AUTHENTICATION));
        }

        if (!Hash::check($password, $user->password)) {
            throw new Exception(__(self::ERROR_AUTHENTICATION));
        }

        return Authentication::fromModel(
            $user,
            $user->createToken('API TOKEN')->plainTextToken
        );
    }

    /**
     * Permite el cierre de sesión en el sistema
     *
     * @return void
     */
    public function logout(): void
    {
        $user = Auth::user();
        if (!is_null($user)) {
            $user->tokens()->delete();
        }
    }
}
