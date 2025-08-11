<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $date
 * @property int $points_created
 * @property int $points_claimed
 * @property float $usd_claimed
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class PlatformStatistic extends Model
{
    /** @use HasFactory<\Database\Factories\PlatformStatisticFactory> */
    use HasFactory;

    /**
     * @var array<string>
     */
    protected $guarded = [];
}
