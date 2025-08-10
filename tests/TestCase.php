<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    /**
     * @return User
     */
    protected function sanctumLogin(): User
    {
        $user = User::factory()->create(['name' => 'Test User', 'email' => 'test@test.com']);
        Sanctum::actingAs($user);

        return $user;
    }
}
