<?php

namespace Tests\Feature;

use App\Models\PointsTransaction;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WalletControllerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function testShowWallet()
    {
        $user = $this->sanctumLogin();
        $wallet = Wallet::factory()->for($user)->create([
            'amount' => $this->faker->randomFloat(2, 0, 1000),
        ]);

        PointsTransaction::factory(5)
            ->sequence(
                ['points' => 1],
                ['points' => 2],
                ['points' => 3],
                ['points' => 4],
                ['points' => 5],
            )
            ->for($user)
            ->create();

        $response = $this->get(route('wallet.get'));

        $response->assertSuccessful()
        ->assertJson([
            'data' => [
                'amount' => $wallet->amount,
                'currency' => $wallet->currency,
                'unclaimed_points' => 15,
            ]
        ]);
    }

    /**
     * A basic feature test example.
     */
    public function testClaimTransaction(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
