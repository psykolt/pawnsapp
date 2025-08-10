<?php

namespace App\Http\Requests;

use App\Models\PointsTransaction;
use Illuminate\Foundation\Http\FormRequest;

class ClaimTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var PointsTransaction $transaction */
        $transaction = $this->route('transaction');
        $user = $this->user();

        return $transaction->user_id === $user->id;
    }
}
