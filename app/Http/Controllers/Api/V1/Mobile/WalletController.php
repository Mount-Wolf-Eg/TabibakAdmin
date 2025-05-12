<?php

namespace App\Http\Controllers\Api\V1\Mobile\Wallet;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepositRequest;
use App\Http\Requests\TransferBalanceRequest;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\WalletResource;
use App\Services\WalletService;

class WalletController extends Controller
{
    private $wallet_service;

    public function __construct()
    {
        $this->wallet_service = new WalletService();
    }

    public function wallet()
    {
        return WalletResource::make($this->wallet_service->getWallet())->additional(['status' => 200]);
    }

    public function transactions()
    {
        return TransactionResource::collection($this->wallet_service->getTransactions())->additional(['status' => 200]);
    }

    public function deposit(DepositRequest $request)
    {
        $validated = $request->validated();
        $deposit   = $this->wallet_service->deposit(amount: $validated['amount'], modelable_type: $validated['modelable_type'], modelable_id: $validated['modelable_id']);
        if ($deposit) return response()->json(['status' => 200, 'data' => null, 'message' => trans('The deposit has been successfully added to your wallet')]);
        return response()->json(['status' => 422, 'data' => null], 422);
    }

    public function transfer(TransferBalanceRequest $request)
    {
        $validated = $request->validated();
        $deposit   = $this->wallet_service->requestTransfer(amount: $validated['amount'], bank_name: $validated['bank_name'], iban: $validated['iban']);
        if ($deposit) return response()->json(['status' => 200, 'data' => null, 'message' => __('The request has been successfully sent')]);
        return response()->json(['status' => 422, 'data' => null], 422);
    }
}
