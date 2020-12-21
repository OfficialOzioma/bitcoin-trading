<?php

namespace App\Http\Controllers\user;

use App\Http\Requests\btcDepositeRequest;
use App\Model\Bank;
use App\Model\BuyCoinHistory;
use App\Services\CoinPaymentsAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoinController extends Controller
{
    // buy coin
    public function buyCoinPage()
    {
        try {
            $data['title'] = __('Buy Coin');
            $data['settings'] = allsetting();
            $data['banks'] = Bank::where(['status' => STATUS_ACTIVE])->get();
            $url = file_get_contents('https://min-api.cryptocompare.com/data/price?fsym=USD&tsyms=BTC');
            $data['btc_dlr'] = json_decode($url,true)['BTC'];

            return view('user.buy_coin.index',$data);
        } catch (\Exception $e) {
            return redirect()->back();
        }

    }

    public function buyCoinRate(Request $request)
    {
        if ($request->ajax()) {
            $data['amount'] = isset($request->amount) ? $request->amount : 0;

            if(env('APP_ENV') != "production") {
                $data['coin_type'] = paymentTypes(PAYMENT_TYPE_LTC);
            } else {
                $data['coin_type'] = isset($request->payment_type) ? paymentTypes($request->payment_type) : allsetting('base_coin_type');
            }

            $data['coin_price'] = bcmul(settings('coin_price'),$request->amount);
            if ($request->pay_type == BTC) {
                $coinpayment = new CoinPaymentsAPI();
                $api_rate = $coinpayment->GetRates('');

                $data['btc_dlr'] = converts_currency($data['coin_price'], $data['coin_type'],$api_rate);

            } else {
                $url = file_get_contents('https://min-api.cryptocompare.com/data/price?fsym=USD&tsyms=BTC');
                $data['btc_dlr'] = json_decode($url,true)['BTC'];
            }

            $data['btc_dlr'] = custom_number_format($data['btc_dlr']);

            return response()->json($data);
        }
    }


    // buy coin process
    public function buyCoin(btcDepositeRequest $request)
    {
        $url = file_get_contents('https://min-api.cryptocompare.com/data/price?fsym=USD&tsyms=BTC');

        if (isset(json_decode($url, true)['BTC'])) {
            $coin_price_doller = bcmul($request->coin, settings('coin_price'));
            $coin_price_btc = bcmul(json_decode($url, true)['BTC'], $coin_price_doller);
            $coin_price_btc = number_format($coin_price_btc, 8);

            if ($request->payment_type == BTC) {

                DB::beginTransaction();
                try {
                    $data['data'] = (object)[]; // placed order
                    $data['success'] = false;
                    $data['message'] = __('Invalid operation');
                    if ($request->payment_coin_type == PAYMENT_TYPE_USD) {
                        $btc_transaction = new BuyCoinHistory();
                        $btc_transaction->address = 'N/A';
                        $btc_transaction->type = BTC;
                        $btc_transaction->user_id = Auth::id();
                        $btc_transaction->coin = $request->coin;
                        $btc_transaction->doller = $coin_price_doller;
                        $btc_transaction->btc = $coin_price_btc;
                        $btc_transaction->coin_type = paymentTypes($request->payment_coin_type);
                        $btc_transaction->save();

                        $data['data'] = $btc_transaction; // placed order
                        $data['success'] = true;
                        $data['message'] = __('Order placed successfully');
                    } else {
                        $coin_payment = new CoinPaymentsAPI();

                        $amount = isset($request->coin) ? $request->coin : 0;
                        if(env('APP_ENV') != "production") {
                            $coin_type = paymentTypes(PAYMENT_TYPE_LTC);
                        } else {
                            $coin_type = isset($request->payment_coin_type) ? paymentTypes($request->payment_coin_type) : allsetting('base_coin_type');
                        }
                        $address = $coin_payment->GetCallbackAddress($coin_type);

                        $coin_price_doller = bcmul(settings('coin_price'),$request->coin);
                        $coin_price_doller = number_format($coin_price_doller,8);
                        if ( isset($address['error']) && ($address['error'] == 'ok') ) {

                            $coinpayment = new CoinPaymentsAPI();
                            $api_rate = $coinpayment->GetRates('');


                            $coin_price_btc = converts_currency($coin_price_doller, $coin_type,$api_rate);

                            if ( $address ) {

                                if (isset($coin_price_btc) && $coin_price_btc > 0) {
                                    $btc_transaction = new BuyCoinHistory();
                                    $btc_transaction->address = $address['result']['address'];
                                    $btc_transaction->type = BTC;
                                    $btc_transaction->user_id = Auth::id();
                                    $btc_transaction->coin = $request->coin;
                                    $btc_transaction->doller = $coin_price_doller;
                                    $btc_transaction->btc = $coin_price_btc;
                                    $btc_transaction->coin_type = $coin_type;
                                    $btc_transaction->save();

                                    $data['data'] = $btc_transaction; // placed order
                                    $data['success'] = true;
                                    $data['message'] = __('Order placed successfully');
                                } else {
                                    $data['data'] = (object)[]; // placed order
                                    $data['success'] = false;
                                    $data['message'] = __('Coin payment not working properly');
                                }
                            }
                        } else {
                            $data['data'] = (object)[]; // placed order
                            $data['success'] = false;
                            $data['message'] = __('Coin payment address not generated');
                        }
                    }


                    DB::commit();
                    if ($data['success'] == false) {
                        return redirect()->back()->with('dismiss', $data['message']);
                    } else {
                        if ($btc_transaction->coin_type == 'USDT') {
                            return redirect()->route('buyCoinByAddress', $btc_transaction->id)->with('success', "Request submitted successful, please send dollar to admin account");

                        } else {
                            return redirect()->route('buyCoinByAddress', $btc_transaction->address)->with('success', "Request submitted successful,please send Coin with this address");
                        }
                    }
                } catch (\Exception $e) {
                    DB::rollBack();
                    return redirect()->back()->with('dismiss', $e->getMessage());
//                    return redirect()->back()->with('dismiss', __("Something went wrong"));
                }

            } elseif ($request->payment_type == CARD) {
                $common_servie = new CommonService();
                $all_req = $request->all();
                $all_req['btn_amount'] = $coin_price_btc;
                $all_req['total_coin_price_in_dollar'] = $coin_price_doller;
                $trans = $common_servie->make_transaction((object)$all_req);
                if ($trans['success']) {
                    DB::beginTransaction();
                    try {
                        $btc_transaction = new BuyCoinHistory();
                        $btc_transaction->type = CARD;
                        $btc_transaction->user_id = Auth::id();
                        $btc_transaction->coin = $request->coin;
                        $btc_transaction->transaction_id = $trans['data']->networkTransactionId;
                        $btc_transaction->doller = $coin_price_doller;
                        $btc_transaction->btc = $coin_price_btc;
                        $btc_transaction->status = STATUS_SUCCESS;
                        $btc_transaction->save();

                        //  add  coin on balance //
                        $default_wallet = Wallet::where('user_id', Auth::id())->where('is_primary', 1)->first();
                        $default_wallet->balance = $default_wallet->balance + $request->coin;
                        $default_wallet->save();

                        DB::commit();
                        return redirect()->back()->with('success', "Coin purchased successfully ");

                        // all good
                    } catch (\Exception $e) {

                        DB::rollback();
                        // something went wrong
                        return redirect()->back()->with('dismiss', __("Something went wrong"));
                    }
                } else {
                    return redirect()->back()->with('dismiss', $trans['message']);
                }
            } elseif ($request->payment_type = BANK_DEPOSIT) {
                $btc_transaction = new BuyCoinHistory();
                $btc_transaction->type = BANK_DEPOSIT;
                $btc_transaction->address = 'N/A';
                $btc_transaction->user_id = Auth::id();
                $btc_transaction->doller = $coin_price_doller;
                $btc_transaction->btc = $coin_price_btc;
                $btc_transaction->coin = $request->coin;
                $btc_transaction->bank_id = $request->bank_id;
                $btc_transaction->bank_sleep = uploadFile($request->file('sleep'), IMG_SLEEP_PATH);
                $btc_transaction->save();

                return redirect()->back()->with('success', "Request submitted successful,Please wait for admin approval");
            }
        } else {
            return redirect()->back()->with('dismiss', "Something went wrong");
        }
    }

    //bank details
    public function bankDetails(Request $request)
    {
        $data = ['success' => false, 'message' => __('Invalid request'), 'data_genetare'=> ''];
        $data_genetare = '';
        if(isset($request->val)) {
            $bank = Bank::where('id', $request->val)->first();
            if (isset($bank)) {
                $data_genetare = '<h3 class="text-center">'.__('Bank Details').'</h3><table class="table">';
                $data_genetare .= '<tr><td>'.__("Bank Name").' :</td> <td>'.$bank->bank_name.'</td></tr>';
                $data_genetare .= '<tr><td>'.__("Account Holder Name").' :</td> <td>'.$bank->account_holder_name.'</td></tr>';
                $data_genetare .= '<tr><td>'.__("Bank Address").' :</td> <td>'.$bank->bank_address.'</td></tr>';
                $data_genetare .= '<tr><td>'.__("Country").' :</td> <td>'.country($bank->country).'</td></tr>';
                $data_genetare .= '<tr><td>'.__("IBAN").' :</td> <td>'.$bank->iban.'</td></tr>';
                $data_genetare .= '<tr><td>'.__("Swift Code").' :</td> <td>'.$bank->swift_code.'</td></tr>';
                $data_genetare .= '</table>';
                $data['data_genetare'] = $data_genetare;
                $data['success'] = true;
                $data['message'] = __('Data get successfully.');
            }
        }

        return response()->json($data);
    }

    // coin payment success page
    public function buyCoinByAddress($address)
    {
        $data['title'] = __('Coin Payment');
        if (is_numeric($address)) {
            $coinAddress = BuyCoinHistory::where(['user_id' => Auth::id(), 'id' => $address, 'status' => STATUS_PENDING])->first();
        } else {
            $coinAddress = BuyCoinHistory::where(['user_id' => Auth::id(), 'address' => $address, 'status' => STATUS_PENDING])->first();
        }
        if (isset($coinAddress)) {
            $data['coinAddress'] = $coinAddress;
            return view('user.buy_coin.payment_success', $data);
        } else {
            return redirect()->back()->with('dismiss', __('Address not found'));
        }
    }

    // buy coin history
    public function buyCoinHistory(Request $request)
    {
        $data['title'] = __('Buy Coin History');
        if ($request->ajax()) {
            $items = BuyCoinHistory::where(['user_id'=>Auth::id()]);
            return datatables($items)
                ->addColumn('type', function ($item) {
                    return byCoinType($item->type);
                })
                ->addColumn('status', function ($item) {
                    return deposit_status($item->status);
                })
                ->make(true);
        }

        return view('user.buy_coin.buy_history', $data);
    }

}
