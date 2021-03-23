<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WalletAmountService;
use Illuminate\Http\Request;

class CustomerWalletController extends Controller
{
    public function walletFetch(Request $request)
    {
        $user_id = $request->user()->id;
        $wallet_check = WalletAmountService::walletFetch($user_id);
        return response()->json($wallet_check['data'], 200);
    }
}
