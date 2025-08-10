<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Services\UserService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @param UserService $userService
     */
    public function __construct(private readonly UserService $userService)
    {
    }

    /**
     * Register new user
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->userService->register($request->validated());

        return $this->success([
            'user' => $user->name . ' - ' . $user->email,
            'message' => 'User created successfully.',
        ]);
    }


    /**
     * Login user
     *
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws AuthenticationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $token = $this->userService->login($request->validated('email'), $request->validated('password'));

        return $this->success([
            'success' => true,
            'token' => $token,
        ]);
    }

    /**
     * Update User profile
     *
     * @param UpdateProfileRequest $request
     * @return JsonResponse
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        if ($this->userService->hasUpdateProfileToday($request->user())) {
            return $this->error('Profile update is not allowed. Please try again tomorrow.', Response::HTTP_TOO_EARLY);
        }

        $answers = $request->getAnsweredQuestions();
        $this->userService->updateProfile($request->user(), $answers);

        return $this->success();
    }
}
