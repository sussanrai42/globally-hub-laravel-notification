<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use App\Commands\CreateUserCommand;
use App\DTOs\Request\CreateUserData;
use App\Http\Controllers\Controller;
use App\Bus\Contracts\CommandBusContract;
use App\Http\Requests\Api\V1\Auth\UserLoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterUserRequest;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService,
        protected CommandBusContract $commandBus,
    ) {}

    public function login(UserLoginRequest $request): JsonResponse
    {
        try {
            return $this->successResponse(
                data: $this->authService->login($request->email, $request->password)
            );
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        try {
            $user = $this->commandBus->dispatch(new CreateUserCommand(CreateUserData::fromRequest($request)));
            return $this->successResponse(
                message: 'User register successfully',
                data: $user
            );
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }
}
