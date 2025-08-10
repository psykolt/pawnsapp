<?php

namespace App\Services;

use App\DTOs\ProfileAnswerDTO;
use App\Events\RewardUser;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserService
{
    /**
     * @param ProfilingQuestionService $profilingService
     */
    public function __construct(private readonly ProfilingQuestionService $profilingService)
    {
    }

    /**
     * @param array $data
     * @return User
     */
    public function register(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'country' => 'TODO',
        ]);

        Log::info('User created ' .  $data['name']);

        return $user;
    }

    /**
     * @param string $email
     * @param string $password
     * @return string
     * @throws AuthenticationException
     */
    public function login(string $email, string $password): string
    {
        if (!Auth::attempt(['email' => $email, 'password' => $password])) {
            throw new AuthenticationException('Invalid credentials');
        }

        return User::where('email', $email)->first()->createToken('auth_token')->plainTextToken;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function hasUpdateProfileToday(User $user): bool
    {
        return $user->profile()->whereDate('updated_at', today())->exists();
    }

    /**
     * @param User $user
     * @param array $answers
     * @return void
     */
    public function updateProfile(User $user, array $answers): void
    {
        /** @var ProfileAnswerDTO $answer */
        foreach ($answers as $answer) {
            $this->profilingService->validateQuestion($answer);
        }

        /** @var ProfileAnswerDTO $answer */
        foreach ($answers as $answer) {
            $this->profilingService->saveProfile($user->id, $answer);
        }

        RewardUser::dispatch($user, 'profile.updated', 5);
    }
}
