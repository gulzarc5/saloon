<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletHistory;
use App\User;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function walletCreditForm($user)
    {
        $wallet = Wallet::where('user_id', $user)->firstOrFail();
        return view('admin.users.wallet_credit',compact('wallet'));
    }
    public function walletDebitForm($user)
    {
        $wallet = Wallet::where('user_id', $user)->firstOrFail();
        return view('admin.users.wallet_debit',compact('wallet'));
    }

    public function walletCreditSubmit(Request $request)
    {
        $this->Validate($request, [
            'wallet_id' => 'required|numeric',
            'amount' => 'required|numeric|min:1',
            'comment' => 'required|string',
        ]);
        $amount = $request->input('amount');
        $wallet = Wallet::FindOrFail($request->input('wallet_id'));
        $wallet->amount = $wallet->amount + $amount;
        if ($wallet->save()) {
            $this->walletHistoryAdd($wallet,1,$request);
            return back()->with('message','Amount Credited Successfully');
        } else {
            return back()->with('error','Something went wrong please try again');
        }
    }

    public function walletDebitSubmit(Request $request)
    {
        $this->Validate($request, [
            'wallet_id' => 'required|numeric',
            'amount' => 'required|numeric|min:1',
            'comment' => 'required|string',
        ]);
        $amount = $request->input('amount');
        $wallet = Wallet::FindOrFail($request->input('wallet_id'));
        $wallet->amount = $wallet->amount - $amount;
        if ($wallet->save()) {
            $this->walletHistoryAdd($wallet,2,$request);
            return back()->with('message','Amount Debited Successfully');
        } else {
            return back()->with('error','Something went wrong please try again');
        }
    }

    public function walletHistoryAdd(Wallet $wallet,$type,$request)
    {
        # type 1 = credit, 2 = debit
        $wallet_history = new WalletHistory();
        $wallet_history->wallet_id = $wallet->id;
        $wallet_history->transaction_type = $type;
        $wallet_history->amount = $request->input('amount');
        $wallet_history->total_amount = $wallet->amount;
        $wallet_history->comment = $request->input('comment');
        $wallet_history->save();
    }
}
