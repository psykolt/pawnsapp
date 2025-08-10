<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $uuid
 * @property string $type
 * @property int $points
 * @property bool $claimed
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class PointsTransaction extends Model
{
    /** @use HasFactory<\Database\Factories\PointsTransactionFactory> */
    use HasFactory;
    use HasUuids;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return string[]
     */
    public function casts(): array
    {
        return [
            'claimed' => 'boolean',
        ];
    }

    /**
     * Generate a new UUID for the model.
     */
    public function newUniqueId(): string
    {
        $hash = 'PWN' . $this->user_id. 'TR' . date('YmdHis') . '.';

        return $hash . bin2hex(random_bytes(12));
    }

    /**
     * Get the columns that should receive a unique identifier.
     *
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
