<?php

namespace App\Services;

class WalletService
{
    public $user    = null;
    public $wallet  = null;
    public $balance = 0.0;

    public function __construct($user = null)
    {
        $this->user = $user ?? auth('api')->user();
        
        if ($this->user) {
            $this->wallet  = $this->user->wallet()->with(['transactions' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])->firstOrCreate();
            $this->balance = $this->wallet->balance;
        }
    }

    public static function __callStatic($name, $arguments)
    {
        $instance = new self(); // or `new static()` for late static binding
        if (method_exists($instance, $name)) {
            return call_user_func_array([$instance, $name], $arguments);
        }

        throw new \BadMethodCallException("Method $name does not exist.");
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function getWallet()
    {
        return $this->wallet;
    }

    public function getTransactions()
    {
        return $this->wallet->transactions;
    }

    public function addReferralBonus($amount, $modelable_type = null, $modelable_id = null)
    {
        $transaction = $this->deposit($amount, $modelable_type, $modelable_id);
        $this->user->increment('promotional_balance', $amount);
        return $transaction;
    }

    public function deposit($amount, $modelable_type = null, $modelable_id = null)
    {
        if (! $this->wallet || ! $this->user) return false;

        $price_after = $this->balance + $amount;

        $transaction = $this->wallet->transactions()->create([
            'user_id'        => $this->user->id,
            'modelable_type' => $modelable_type,
            'modelable_id'   => $modelable_id,
            'balance_before' => $this->balance,
            'balance_after'  => $price_after,
            'amount'         => $amount,
            'type'           => 'deposit',
        ]);

        $this->wallet->update(['balance' => $price_after]);

        return $transaction;
    }

    public function withdrawal($amount, $modelable_type = null, $modelable_id = null)
    {
        if (! $this->wallet || ! $this->user) return false;

        $price_after = $this->balance - $amount;

        $transaction = $this->wallet->transactions()->create([
            'user_id'        => $this->user->id,
            'modelable_type' => $modelable_type,
            'modelable_id'   => $modelable_id,
            'balance_before' => $this->balance,
            'balance_after'  => $price_after,
            'amount'         => $amount,
            'type'           => 'withdrawal',
        ]);

        $this->wallet->update(['balance' => $price_after]);

        return $transaction;
    }

    public function requestTransfer($amount, $bank_name, $iban)
    {
        if (! $this->wallet || ! $this->user) return false;

        $price_after = $this->balance - $amount;

        $transaction = $this->wallet->transactions()->create([
            'user_id'        => $this->user->id,
            'balance_before' => $this->balance,
            'balance_after'  => $price_after,
            'amount'         => $amount,
            'type'           => 'transfer',
            'bank_name'      => $bank_name,
            'iban'           => $iban,
        ]);

        $this->wallet->update(['balance' => $price_after]);

        return $transaction;
    }
}
