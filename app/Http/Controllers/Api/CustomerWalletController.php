<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Wallet\WalletHistoryResource;
use App\Models\Wallet;
use App\Models\WalletHistory;
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

    public function walletHistory(Request $request)
    {
        $user_id = $request->user()->id;
        $wallet = Wallet::where('user_id',$user_id)->first();;

        $walletHistory = WalletHistory::where('wallet_id',$wallet->id)->orderBy('id', 'desc')->paginate(12);

        $response = [
            'status' => true,
            'total_page' => $walletHistory->lastPage(),
            'current_page' =>$walletHistory->currentPage(),
            'total_data' =>$walletHistory->total(),
            'has_more_page' =>$walletHistory->hasMorePages(),
            'wallet_amount' => $wallet->amount,
            'wallet_history' => WalletHistoryResource::collection($walletHistory),
        ];
        return response()->json($response,200);
    }
}
