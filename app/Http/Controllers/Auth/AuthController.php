<?php

declare(strict_types=1);

namespace App\Http\Controllers\Core\Auth;

use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;
use Repository\User\UserRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\User\UserResource;
use App\Http\Requests\Auth\LoginPostRequest;
use App\Http\Requests\Auth\RegistrationPostRequest;
use App\Events\LoggedInUserAccessTokenStoreEvent;

class AuthController extends Controller
{
    use JsonResponseTrait;

    function __construct(protected UserRepository $userRepository){}

    public function register(RegistrationPostRequest $request)
    {
        try {
            $user = $this->userRepository->create($request->validated());

            return $this->createdJsonResponse('User registered successfully', ['user' => new UserResource($user)]);
        } catch (\Exception $e) {
            return $this->errorJsonResponse('Error: ' . $e->getMessage());
        }
    }

    public function authorizedUserInformation()
    {
        try {
            $user = Auth::user();

            return $this->successJsonResponse('Logged in user informations', [
                'access_token'  => $user['remember_token'],
                'user'          => new UserResource($user),
            ]);
        } catch (\Exception $e) {
            return $this->unAuthenticatedJsonResponse('Error: ' . $e->getMessage());
        }
    }

    public function login(LoginPostRequest $request)
    {
        try {
            if (!auth()->attempt($request->validated())) {
                return $this->errorJsonResponse('Invalid Credentials');
            }

            $user = [
                'access_token'  => $this->userRepository->generateAccessToken(auth()->user()),
                'user'          =>  auth()->user(),

            ];
            event(new LoggedInUserAccessTokenStoreEvent($user));

            return $this->successJsonResponse('Login successfully', [
                'access_token'  => $user['access_token'],
                'access_type'   => 'Bearer',
                'user'          => new UserResource($user['user']),
            ]);
        } catch (\Exception $e) {
            return $this->errorJsonResponse('An error occurred during login: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return $this->successJsonResponse('User logged out');
    }
}
