<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * @param Request $request
     * @return array|JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);
        $credentials = $this->credentials($request);
        $token = \JWTAuth::attempt($credentials);
        return $token ? ['token' => $token] :
            response()->json(
                ['error' => \Lang::get('auth.failed')]
                , 400);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        Auth::guard('api')->logout();
        return response()->json([], 204);
    }

    /**
     * @return UserResource
     */
    public function me()
    {
        $usuario = Auth::guard('api')->user();
        return new UserResource($usuario);
    }

    /**
     * @return array
     */
    public function refresh()
    {
        $token = Auth::guard('api')->refresh();
        return ['token' => $token];
    }
}
