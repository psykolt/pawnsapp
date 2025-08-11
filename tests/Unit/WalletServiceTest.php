<?php

namespace Tests\Unit;

use App\Enums\Currency;
use App\Services\WalletService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class WalletServiceTest extends TestCase
{
    /**
     * @var WalletService
     */
    private WalletService $walletService;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->walletService = new WalletService();
    }

    #[DataProvider('currencyTestData')]
    public function testConvertPointsToCurrency(int $points, string $currency, float $expected)
    {
        $result = $this->walletService->convertPointsToCurrency($currency, $points);
        $this->assertEquals($expected, $result);
    }

    /**
     * @return array[]
     */
    public static function currencyTestData(): array
    {
        return [
            '100 to USD' => [100, Currency::USD->value, 1.00],
            '100 to Euro' => [100, Currency::EUR->value, 1.2],
            '100 to Yen' => [100, Currency::YEN->value, 0.1],
        ];
    }
}
