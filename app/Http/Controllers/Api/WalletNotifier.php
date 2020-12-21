<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Model\BuyCoinHistory;
use App\Model\Wallet;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Pusher\Pusher;
use Pusher\PusherException;

class WalletNotifier extends Controller
{
    // Wallet notifier for checking and confirming order process
    public function coinPaymentNotifier(Request $request)
    {

        $raw_request = $request->all();

        $merchant_id = settings('ipn_merchant_id');
        $secret = settings('ipn_secret');

        if (env('APP_ENV') != "local"){
            if (!isset($_SERVER['HTTP_HMAC']) || empty($_SERVER['HTTP_HMAC'])) {
                Log::info('No HMAC signature sent');

                die("No HMAC signature sent");

            }

            $merchant = isset($_POST['merchant']) ? $_POST['merchant']:'';
            if (empty($merchant)) {
                Log::info('No Merchant ID passed');

                die("No Merchant ID passed");

            }

            if ($merchant != $merchant_id) {
                Log::info('Invalid Merchant ID');

                die("Invalid Merchant ID");

            }

            $request = file_get_contents('php://input');
            if ($request === FALSE || empty($request)) {
                Log::info('Error reading POST data');

                die("Error reading POST data");

            }

            $hmac = hash_hmac("sha512", $request, $secret);

            if ($hmac != $_SERVER['HTTP_HMAC']) {
                Log::info('HMAC signature does not match');

                die("HMAC signature does not match");

            }
        }

        return $this->depositeWallet($raw_request);





    }

    public function depositeWallet($request){
        $data = ['success'=>false,'message'=>'something went wrong'];
        $channel = "";

        DB::beginTransaction();

        try {
//            if (empty(getPhase())){
//                $data['message'] = '';
//                return $data;
//            }

            $request = (object)$request;
            $buy_coin_histories = BuyCoinHistory::where([
                'address'=>$request->address
                ,'status'=>STATUS_PENDING
            ])->first();


            if (!empty($buy_coin_histories))
                $channel = 'deposit_'.$buy_coin_histories->user_id;


            if (!empty($buy_coin_histories) && ($request->ipn_type == "deposit") && ($request->status >= 100)){
                $wallet =  Wallet::where(['user_id' => $buy_coin_histories->user_id])->first();
                $data['user_id'] = $wallet->user_id;
                if (!empty($wallet)){
                    $curr = (env('APP_ENV') != 'production') ? "LTCT" : "BTC";
                    if ($request->amount >= $buy_coin_histories->price_in_btc) {

                        $buy_coin_histories->status = STATUS_SUCCESS;
                        $buy_coin_histories->transaction_id = $request->txn_id;
                        $buy_coin_histories->btc = $request->amount;
                        $buy_coin_histories->confirmations = $request->confirms;
                        $buy_coin_histories->save();
                        if (!empty($wallet->balance)) {
                            $wallet->increment('balance', $buy_coin_histories->coin);
                        } else {
                            $wallet->balance = $buy_coin_histories->coin;
                            $wallet->save();
                        }
                        $data['message'] = 'payment successfully';
                        $data['success'] = true;
                    }else{
                        $data['message'] = 'Payment failed';
                        $data['success'] = false;
                    }
                }else{
                    $data['message'] = 'No wallet found';
                    return $data;
                }
            }
            DB::commit();
            try {
                if (!empty($buy_coin_histories)){
                    $data['balance'] = $wallet->balance;
                }
                if (!empty($buy_package_histories) && ($buy_package_histories->status == STATUS_SUCCESS)){
                    $data['message'] = __('Package added successfully');

                 //   $broadcust = $broadcust->trigger($channel,'buy_package_successfully',$data);
                }



                //dispatch(new PhaseRefresh())->onQueue('amz-refresh');

            } catch (\Exception $exception) {

            }
            return $data;
            // all good
        } catch (\Exception $e) {
            $data['message'] = $e->getMessage().' '.$e->getLine();
            DB::rollback();
            return $data;
            // something went wrong
        }



    }


    /**
     * For broadcast data
     * @param $data
     */
    public function broadCast($data)
    {
        $channelName = 'depositConfirmation.' . customEncrypt($data['userId']);
        $fields = json_encode([
            'channel_name' => $channelName,
            'event_name' => 'confirm',
            'broadcast_data' => $data['broadcastData'],
        ]);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://' . env('BROADCAST_HOST') . '/api/broadcast',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $fields,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                'broadcast-secret: an9$md_eoUqmNpa@bm34Jd'
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
    }


    public function amzDepositNotify(Request $request){

        $log = new Logger();
        $log->log(json_encode($request->all()));
        Log::info(json_encode($request->all()));


        $data['success'] = false;
        $data['message'] = __('Something went wong');


        $transaction_id = $request->transactionId;
        $blockNumber = $request->blockNumber;


        if (AmzDeposit::where('transaction_id',$transaction_id)->exists()){
            $data['message'] = __('Deposit already done');
            return $data;
        }

        DB::beginTransaction();
        try {

            $eosService = new EosService();

            $log->log('deposit amz','start-transaction-id='.$transaction_id);
            $eosService = $eosService->getTransactionDetails($transaction_id,$blockNumber);

            if ($eosService['success'] == true){

                $apiData = $eosService['data'];
                if ($apiData['status'] == 1){
                    $apiData = $apiData['data'];
                    $jsonDecode = json_decode($apiData,true);
                    if (!empty($jsonDecode)){
                        //    protected $fillable = ['id','user_id','wallet_id','transaction_id','sender','receiver','quantity','memo','hex_data','block_num'];
                        $hex = $jsonDecode['trx']['trx']['actions'][0]['hex_data'];
                        $transactionDetails = $jsonDecode['trx']['trx']['actions'][0]['data'];
                        $memo_id = $transactionDetails['memo'];
                        $memoDetails = Memo::where('memo_id',$memo_id)->first();

                        if (!empty($memoDetails)){
                            $log->log('deposit amz','Memo found and try to add balance');

                            $userWallet = Wallet::where(['user_id'=>$memoDetails->user_id,'type'=>WALLET_AMZ])->first();

                            $depositData['user_id'] = $memoDetails->user_id;
                            $depositData['wallet_id'] = $userWallet->id;
                            $depositData['transaction_id'] = $transaction_id;

                            $depositData['sender'] = $transactionDetails['from'];
                            $depositData['receiver'] = $transactionDetails['to'];
                            $depositData['quantity'] =  explode(' ',$transactionDetails['quantity'])[0] ;
                            $depositData['memo'] = $transactionDetails['memo'];
                            $depositData['hex_data'] = $hex;
                            $depositData['block_num'] = $blockNumber;

                            AmzDeposit::create($depositData);
                            $userWallet->increment('balance',$depositData['quantity']);

                            $data['success'] = true;
                            $data['message'] = __('Balance transferred successfully ');

                            $log->log('deposit amz','Deposit successfully');

                            DB::commit();

                            $config = config('broadcasting.connections.pusher');
                            $broadcust = new Pusher($config['key'], $config['secret'], $config['app_id'], $config['options']);

                            $channel = 'deposit_'.$userWallet->user_id;
                            $data['balance'] = $userWallet->balance;
                            $broadcust = $broadcust->trigger($channel,'deposit_coin_successfully',$data);
                            $log = new Logger();
                            $log->log('brodcust',json_encode($broadcust));


                        }

                    }
                }
            }


        } catch (\Exception $e) {

            DB::rollback();
            $log->log('deposit amz','failed-transaction-id='.$transaction_id.'issue--file='.$e->getFile().'---message'.$e->getMessage().'----line='.$e->getLine());
            // something went wrong
        }


        return $data;
    }



}
