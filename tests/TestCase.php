<?php

namespace Tests;

use App\Models\User;
use App\Modules\Proxycheck\ApiClient;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;
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

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests(); // to avoid accidentally calling external APIs
        $apiMock = $this->mock(ApiClient::class);
        // as this uses a separate proxycheck package, preventStrayRequests doesn't work
        $apiMock->shouldReceive('callApi')->andReturn([]);
    }
}
