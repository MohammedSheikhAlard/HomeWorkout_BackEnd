<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Http\Requests\StoreWalletRequest;
use App\Http\Requests\UpdateWalletRequest;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\apiResponseTrait;
use Illuminate\Support\Facades\Auth;


class WalletController extends Controller
{

    use apiResponseTrait;

    public function createWallet(Request $request)
    {

        $user = $request->user();
        if ($user->wallet) {
            return response()->json(['message' => 'You already have a wallet',], 400);
        }

        $wallet = Wallet::create([
            'user_id' => $user->id,
            'balance' => 0,
        ]);

        return $this->apiResponse($wallet, "Wallet created successfully", 200);
    }


    public function getBalance(Request $request)
    {

        $wallet = $request->user()->wallet;

        return $this->apiResponse($wallet, "this is your balance", 200);
    }


    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.1',
        ]);

        $wallet = $request->user()->wallet;
        $wallet->balance += $request->amount;
        $wallet->save();

        Transaction::create([
            'amount' => $request->amount,
            'type' => 'deposit',
            'wallet_id' => $wallet->id,
        ]);
        return $this->apiResponse($wallet, "Amount deposited successfully", 200);
    }
    //**********************************


    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.1',
        ]);

        $wallet = $request->user()->wallet;

        if ($wallet->balance < $request->amount) {
            return response()->json(['message' => 'Insufficient balance'], 400);
        }

        $wallet->balance -= $request->amount;
        $wallet->save();

        Transaction::create([
            'amount' => $request->amount,
            'type' => 'withdraw',
            'wallet_id' => $wallet->id,
        ]);
        return $this->apiResponse($wallet, "Amount withdrawn successfully", 200);
    }


    public function getTransactions(Request $request)
    {
        $transactions = $request->user()->wallet->transaction()->get();

        return $this->apiResponse($transactions, "The operations were fetched successfully", 200);
    }

    public function checkUserHaveWallet(Request $request)
    {

        $user = $request->user();

        if ($user == null) {
            return $this->apiResponse(null, "something went wrong", 400);
        }

        $wallet = Wallet::where('user_id', $user->id)->first();

        if (!$wallet) {
            return $this->apiResponse([
                'have Wallet' => false
            ], "there is no wallet for this user", 404);
        }

        return $this->apiResponse([
            'have Wallet' => true
        ], "there is wallet for this user", 200);
    }
}
