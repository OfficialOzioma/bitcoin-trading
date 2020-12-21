<?php

namespace App\Http\Controllers\user;

use App\Http\Requests\withDrawRequest;
use App\Http\Services\TransactionService;
use App\Jobs\Withdrawal;
use App\Model\DepositeTransaction;
use App\Model\Wallet;
use App\Model\WalletAddressHistory;
use App\Model\WithdrawHistory;
use App\Services\BitCoinApiService;
use App\Services\CoinPaymentsAPI;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PragmaRX\Google2FA\Google2FA;


class WalletController extends Controller
{
    // my pocket
    public function myPocket()
    {
        $data['wallets'] = Wallet::where('user_id',Auth::id())->get();
        $data['title'] = __('My Pocket');
        return view('user.pocket.index',$data);
    }

    // make default account
    public function makeDefaultAccount($account_id)
    {
        Wallet::where('user_id',Auth::id())->update(['is_primary'=>0]);
        Wallet::updateOrCreate(['id'=>$account_id],['is_primary'=>1]);

        return redirect()->back()->with('success',__('Default set successfully'));
    }

    // create new wallet
    public function createWallet(Request $request)
    {
        if (!empty($request->wallet_name)) {
            $wallet = new Wallet();
            $wallet->user_id = Auth::id();
            $wallet->name = $request->wallet_name;
            $wallet->status = STATUS_SUCCESS;
            $wallet->balance = 0;
            $wallet->save();

            return redirect()->back()->with('success',__("Pocket created successfully"));
        }
        return redirect()->back()->with('dismiss',__("Pocket name can't be empty"));
    }

    // wallet details
    public function walletDetails(Request $request,$id)
    {
        $payment_type = (env('APP_ENV') != "production") ? paymentTypes(PAYMENT_TYPE_LTC) : allsetting('base_coin_type');
        $exists = WalletAddressHistory::where('wallet_id',$id)->orderBy('created_at','desc')->first();
        $data['address'] = (!empty($exists)) ? $exists->address : get_coin_payment_address($payment_type);
        $data['wallet_id'] = $id;
        $data['wallet'] = Wallet::find($id);
        $data['address_histories'] = WalletAddressHistory::where('wallet_id',$id)->get();
        $data['histories'] = DepositeTransaction::where('receiver_wallet_id',$id)->get();
        $data['withdraws'] = WithdrawHistory::where('wallet_id',$id)->get();
        $data['active'] = $request->q;
        $data['title'] = $request->q;

        if (empty($exists)) {
            $history = new \App\Services\wallet();
            $history->AddWalletAddressHistory($id,  $data['address']);
        }
        return view('user.pocket.wallet_details',$data);
    }

    // generate new wallet address
    public function generateNewAddress(Request $request)
    {
        try {
            $wallet = new \App\Services\wallet();
            $payment_type = (env('APP_ENV') != "production") ? paymentTypes(PAYMENT_TYPE_LTC) : allsetting('base_coin_type');
            $address = get_coin_payment_address($payment_type);

            if (!empty($address)) {
                $wallet->AddWalletAddressHistory($request->wallet_id,$address);
                return redirect()->back()->with(['success'=>__('Address generated successfully')]);
            } else {
                return redirect()->back()->with(['dismiss'=>__('Address not generated ')]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('dismiss', $e->getMessage());
        }

    }

    // generate qr code
    public function qrCodeGenerate(Request $request)
    {
        $image = QRCode::text($request->address)->png();
        return response($image)->header('Content-type','image/png');
    }

    // withdraw balance
    public function WithdrawBalance(withDrawRequest $request) {
        $wallet = Wallet::find($request->wallet_id);
        $NodeDetails = new \App\Services\wallet();

        $address = $request->address;
        $user = $wallet->user;
        if ($request->ajax()) {
            if ($wallet->balance >= $request->amount) {

                if ( filter_var($address, FILTER_VALIDATE_EMAIL) ) {
                    $receiverUser = User::where('email', $address)->first();
                    if ( empty($receiverUser) ) {
                        return response()->json(['success'=>false,'message'=> __('Not a valid email address to send amount!')]);
                    }
                    if ( $user->id == $receiverUser->id ) {
                        return response()->json(['success'=>false,'message'=> __('You can\'t send to your own wallet!')]);
                    }
                    $fees = 0;

                } else {
                    $walletAddress = $this->isInternalAddress($address);
                    if ($walletAddress) {
                        $fees = 0;
                        $receiverUser = $walletAddress->wallet->user;
                        if ( $user->id == $receiverUser->id ) {
                            return response()->json(['success'=>false,'message'=> __('You can\'t send to your own wallet!')]);
                        }
                    } else {
                        $fees = calculate_fees($request->amount, settings('send_fees_type'));
                        if ($wallet->balance < ($request->amount + $fees)) {
                            return response()->json(['success'=>false,'message'=>__('Wallet has no enough balance')]);
                        }
                    }
                }

                if (($request->amount + $fees) < settings('minimum_withdrawal_amount') ) {
                    $message = __('Minimum withdrawal amount ') . settings('minimum_withdrawal_amount') . ' '.settings('coin_name');
                    return response()->json(['success'=>false,'message'=> $message]);
                }
                if (($request->amount + $fees) > settings('maximum_withdrawal_amount') ) {
                    $message = __('Maximum withdrawal amount ') . settings('maximum_withdrawal_amount') . ' '.settings('coin_name');
                    return response()->json(['success'=>false,'message'=> $message]);
                }
                    return response()->json(['success'=>true]);
//                    return response()->json(['success'=>false,'message'=>__('Address is not valid')]);

            } else {
                return response()->json(['success'=>false,'message'=>__('Wallet has no enough balance')]);
            }

        } else {
            if ( filter_var($address, FILTER_VALIDATE_EMAIL) ) {

                $receiverUser = User::where('email', $address)->first();

                if ( empty($receiverUser) ) {
                    return redirect()->back()->with('dismiss', __('Not a valid email address to send amount!'));
                }
                if ( $user->id == $receiverUser->id ) {
                    return redirect()->back()->with('dismiss', __('You can\'t send to your own wallet!'));
                }
                $fees = 0 ;

            } else {
                $walletAddress = $this->isInternalAddress($address);
                if ($walletAddress) {
                    $fees = 0;
                    $receiverUser = $walletAddress->wallet->user;
                    if ( $user->id == $receiverUser->id ) {
                        return redirect()->back()->with('dismiss', __('You can\'t send to your own wallet!'));
                    }
                }  else {

                    $fees = calculate_fees($request->amount, settings('send_fees_type'));
                    if ($wallet->balance < ($request->amount + $fees)) {
                        return response()->json(['success'=>false,'message'=>__('Wallet has no enough balance')]);
                    }
                }
            }
            if (($request->amount + $fees) < settings('minimum_withdrawal_amount') ) {
                $message = __('Minimum withdrawal amount ') . settings('minimum_withdrawal_amount') . ' '.settings('coin_name');
                return response()->json(['success'=>false,'message'=> $message]);
            }
            if (($request->amount + $fees) > settings('maximum_withdrawal_amount') ) {
                $message = __('Maximum withdrawal amount ') . settings('maximum_withdrawal_amount') . ' '.settings('coin_name');
                return response()->json(['success'=>false,'message'=> $message]);
            }

            $user = Auth::user();
            $google2fa = new Google2FA();
            if(empty($request->code)) {
                return redirect()->back()->with('dismiss',__('Verify code is required'));
            }
            $valid = $google2fa->verifyKey($user->google2fa_secret, $request->code);

            $data = $request->all();
            $data['user_id'] = Auth::id();
            $request = new Request();
            $request = $request->merge($data);

            if ($valid) {
                if ($wallet->balance >= $request->amount) {
//                    $request =$request->all();
//
//                    $trans = new TransactionService();
//                    $response = $trans->send($request['wallet_id'],$request['address'],$request['amount'],'','',$request['user_id'],$request['message']);
//                    return $response;
                    dispatch(new Withdrawal($request->all()))->onQueue('withdrawal');
                    return redirect()->back()->with('success',__('Withdrawal placed successfully'));
                } else
                    return redirect()->back()->with('dismiss',__('Wallet has no enough balance'));
            } else
                return redirect()->back()->with('dismiss',__('Google two factor authentication is invalid'));
        }
    }

    //check internal address
    private function isInternalAddress($address)
    {
        return WalletAddressHistory::where('address', $address)->with('wallet')->first();
    }

    // transaction history
    public function transactionHistories(Request $request)
    {
        if ($request->ajax()){
            $tr = new TransactionService();
            if ($request->type == 'deposit') {
                $histories = $tr->depositTransactionHistories(Auth::id())->get();
            } else {
                $histories = $tr->withdrawTransactionHistories(Auth::id())->get();
            }
            return datatables( $histories)
                ->addColumn('address', function ($item) {
                    return $item->address;
                })
                ->addColumn('amount', function ($item) {
                    return $item->amount;
                })
                ->addColumn('hashKey', function ($item) use ($request){
                    if ($request->type == 'deposit')
                        return (!empty($item)) ? $item->transaction_id : '';
                    else
                        return (!empty($item)) ? $item->transaction_hash : '';
                })
                ->addColumn('status', function ($item) {
                    return statusAction($item->status);
                })
                ->rawColumns(['user'])
                ->make(true);
        }
    }

    // withdraw rate
    public function withdrawCoinRate(Request $request)
    {
        if ($request->ajax()) {
            $data['amount'] = isset($request->amount) ? $request->amount : 0;

            if(env('APP_ENV') != "production") {
                $data['coin_type'] = paymentTypes(PAYMENT_TYPE_LTC);
            } else {
                $data['coin_type'] = allsetting('base_coin_type');
            }

            $data['coin_price'] = bcmul(settings('coin_price'),$request->amount);
            $coinpayment = new CoinPaymentsAPI();
            $api_rate = $coinpayment->GetRates('');

            $data['btc_dlr'] = converts_currency($data['coin_price'], $data['coin_type'],$api_rate);
            $data['btc_dlr'] = custom_number_format($data['btc_dlr']);

            return response()->json($data);
        }
    }

}
