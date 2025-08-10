<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClaimTransactionRequest;
use App\Http\Resources\WalletResource;
use App\Models\PointsTransaction;
use App\Services\WalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * @param WalletService $walletService
     */
    public function __construct(private readonly WalletService $walletService)
    {
    }

    /**
     * Get User's wallet
     *
     * @param Request $request
     * @return WalletResource
     */
    public function getWallet(Request $request): WalletResource
    {
        return new WalletResource($request->user());
    }

    /**
     * Claim Points transaction
     *
     * @param PointsTransaction $transaction
     * @param ClaimTransactionRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function claimTransaction(PointsTransaction $transaction, ClaimTransactionRequest $request): JsonResponse
    {
        $this->walletService->claimTransaction($request->user(), $transaction);

        return $this->success();
    }
}
