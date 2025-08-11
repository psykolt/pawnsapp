<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $question
 * @property string $type
 * @property array|null $options
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ProfilingQuestion extends Model
{
    /** @use HasFactory<\Database\Factories\ProfilingQuestionFactory> */
    use HasFactory;

    /**
     * @var array<string>
     */
    protected $guarded = [];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'options' => 'array',
    ];
}
