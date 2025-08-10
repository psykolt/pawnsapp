<?php

namespace Tests\Feature;

use App\Enums\Currency;
use App\Mail\TransactionClaimed;
use App\Models\PointsTransaction;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class WalletControllerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /**
     * @return void
     */
    public function testShowWallet(): void
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
        $user = $this->sanctumLogin();
        $wallet = Wallet::factory()->for($user)->create([
            'amount' => 0,
            'currency' => Currency::USD->value,
        ]);

        $pointsTransaction = PointsTransaction::factory()->for($user)->create(['points' => 100]);

        Mail::spy();

        $response = $this->post(route('wallet.claimTransaction', $pointsTransaction->uuid));

        $response->assertStatus(200);

        $pointsTransaction->refresh();
        $wallet->refresh();

        $this->assertTrue($pointsTransaction->claimed);
        $this->assertEquals($wallet->amount, 1);

        Mail::assertSent(TransactionClaimed::class);
    }
}
