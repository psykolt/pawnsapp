<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserService
{
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
}
