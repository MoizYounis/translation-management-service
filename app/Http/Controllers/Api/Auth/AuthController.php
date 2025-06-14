<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Enum\ResponseMessages;
use App\Contracts\AuthContract;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Resources\LoginResponse;
use App\Http\Requests\Api\LoginRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(protected AuthContract $auth) {}

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="Login",
     *     operationId="login",
     *
     *      @OA\RequestBody(
     *         description="Login",
     *         required=true,
     *
     *         @OA\JsonContent(
     *               required={"email", "password"},
     *
     *               @OA\Property(property="email", type="string", format="email", example="test@example.com"),
     *               @OA\Property(property="password", type="string", format="password", example="password"),
     *           ),
     *     ),
     *
     *     @OA\Response(
     *         response="default",
     *         description="Success"
     *     ),
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $user = $this->auth->login($request->prepareRequest());

            return $this->sendJson(true, __('lang.messages.login_success'), [
                'user' => $user,
                'token' => $user->createToken('api-token')->plainTextToken
            ]);
        } catch (CustomException $e) {
            return $this->sendJson(false, $e->getMessage());
        } catch (\Throwable $th) {
            logMessage('login', $request->prepareRequest(), $th->getMessage());

            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Auth"},
     *     summary="logout",
     *     operationId="logout",
     *     security={ {"sanctum": {} }},
     *
     *     @OA\Response(
     *         response="default",
     *         description="Success"
     *     ),
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $this->auth->logout($user);
            return $this->sendJson(true, __('lang.messages.logout_success'));
        } catch (\Throwable $th) {
            logMessage('logout', $request->prepareRequest(), $th->getMessage());
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        }
    }
}
