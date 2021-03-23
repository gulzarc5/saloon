<?php
namespace App\Services;

use App\Models\Wallet;
use App\Models\WalletHistory;

class WalletAmountService
{
    public static function walletFetch($user_id){
        $wallet = Wallet::where('user_id',$user_id)->first();
        if ($wallet) {
            return [
                'status' => true,
                'data' => $wallet,
            ];
        } else {
            return [
                'status' => false,
                'data' => null,
            ];
        }
    }

    public static function walletCredit($user_id,$amount,$comment){
        $wallet = Wallet::where('user_id',$user_id)->first();
        if ($wallet) {
            $wallet->amount = $wallet->amount + $amount;
            $wallet->save();
            self::walletHistoryInsert($wallet,$amount,1,$comment);
        } else {
            return [
                'status' => false,
                'data' => null,
            ];
        }
    }

    private static function walletHistoryInsert($wallet,$amount,$type,$comment){
        //type 1 = credit, 2 = debit
        $walletHistory = new WalletHistory();
        $walletHistory->wallet_id = $wallet->id;
        $walletHistory->transaction_type = $type;
        $walletHistory->amount = $amount;
        $walletHistory->total_amount = $wallet->amount;
        $walletHistory->comment = $comment;
        $walletHistory->save();
        return true;
    }
}