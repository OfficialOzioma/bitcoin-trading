<?php

namespace App\Http\Controllers\user;

use App\Http\Services\CommonService;
use App\Model\BuyCoinHistory;
use App\Model\DepositeTransaction;
use App\Model\Faq;
use App\Model\Notification;
use App\Model\WithdrawHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function userDashboard(Request $request)
    {
        $data['title'] = __('Dashboard');
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET','https://api.nomics.com/v1/exchange-rates?key=demo-26240835858194712a4f8cc0dc635c7a');
        $body = $response->getBody()->getContents();
        $data['api_response'] = json_decode($body);
        $data['balance'] = getUserBalance(Auth::id());
        $data['total_buy_coin'] = BuyCoinHistory::where(['user_id'=> Auth::id(),'status'=> STATUS_ACTIVE])->sum('coin');
        $from = Carbon::now()->subMonth(6)->format('Y-m-d h:i:s');
        $to = Carbon::now()->format('Y-m-d h:i:s');

        $common_service = new CommonService();

        if (!$request->ajax()){
            $sixmonth_diposites = $common_service->AuthUserDiposit($from,$to);
            $sixmonth_withdraws = $common_service->AuthUserWithdraw($from,$to);

            ///////////////////////////////////////////   six month data /////////////////////////////
            $data['sixmonth_diposites'] = [];
            $months = previousMonthName(5);

            foreach ($months as $key => $month){
                $data['sixmonth_diposites'][$key]['country'] = $month;
                $data['sixmonth_diposites'][$key]['year2004'] = (isset($sixmonth_diposites[$month])) ? $sixmonth_diposites[$month] : 0;
                $data['sixmonth_diposites'][$key]['year2005'] = (isset($sixmonth_withdraws[$month])) ? $sixmonth_withdraws[$month] : 0;
            }
        }


        $data['completed_withdraw']  = WithdrawHistory::join('wallets','wallets.id','withdraw_histories.wallet_id')
            ->where('withdraw_histories.status',STATUS_SUCCESS)
            ->where('wallets.user_id',Auth::id())->sum('withdraw_histories.amount');
        $data['pending_withdraw']  = WithdrawHistory::join('wallets','wallets.id','withdraw_histories.wallet_id')
            ->where('withdraw_histories.status',STATUS_PENDING)
            ->where('wallets.user_id',Auth::id())->sum('withdraw_histories.amount');


        $data['histories'] = DepositeTransaction::get();
        $data['withdraws'] = WithdrawHistory::get();
        $allMonths = all_months();
        // deposit
        $monthlyDeposits = DepositeTransaction::join('wallets', 'wallets.id', 'deposite_transactions.receiver_wallet_id')
            ->where('wallets.user_id', Auth::id())
            ->select(DB::raw('sum(deposite_transactions.amount) as totalDepo'), DB::raw('MONTH(deposite_transactions.created_at) as months'))
            ->whereYear('deposite_transactions.created_at', Carbon::now()->year)
            ->where('deposite_transactions.status', STATUS_SUCCESS)
            ->groupBy('months')
            ->get();

        if (isset($monthlyDeposits[0])) {
            foreach ($monthlyDeposits as $depsit) {
                $data['deposit'][$depsit->months] = $depsit->totalDepo;
            }
        }
        $allDeposits = [];
        foreach ($allMonths as $month) {
            $allDeposits[] =  isset($data['deposit'][$month]) ? $data['deposit'][$month] : 0;
        }
        $data['monthly_deposit'] = $allDeposits;

        // withdrawal
        $monthlyWithdrawals = WithdrawHistory::join('wallets', 'wallets.id', 'withdraw_histories.wallet_id')
            ->select(DB::raw('sum(withdraw_histories.amount) as totalWithdraw'), DB::raw('MONTH(withdraw_histories.created_at) as months'))
            ->whereYear('withdraw_histories.created_at', Carbon::now()->year)
            ->where('withdraw_histories.status', STATUS_SUCCESS)
            ->groupBy('months')
            ->get();

        if (isset($monthlyWithdrawals[0])) {
            foreach ($monthlyWithdrawals as $withdraw) {
                $data['withdrawal'][$withdraw->months] = $withdraw->totalWithdraw;
            }
        }
        $allWithdrawal = [];
        foreach ($allMonths as $month) {
            $allWithdrawal[] =  isset($data['withdrawal'][$month]) ? $data['withdrawal'][$month] : 0;
        }
        $data['monthly_withdrawal'] = $allWithdrawal;

        // withdrawal
        $monthlyBuyCoins = BuyCoinHistory::select(DB::raw('sum(coin) as totalCoin'), DB::raw('MONTH(created_at) as months'))
            ->whereYear('created_at', Carbon::now()->year)
            ->where(['user_id'=> Auth::id(),'status'=> STATUS_SUCCESS])
            ->groupBy('months')
            ->get();

        if (isset($monthlyBuyCoins[0])) {
            foreach ($monthlyBuyCoins as $coin) {
                $data['coin'][$coin->months] = $coin->totalCoin;
            }
        }
        $allBuyCoin = [];
        foreach ($allMonths as $month) {
            $allBuyCoin[] =  isset($data['coin'][$month]) ? $data['coin'][$month] : 0;
        }
        $data['monthly_buy_coin'] = $allBuyCoin;

        return view('user.dashboard', $data);
    }

    // user faq list
    public function userFaq()
    {
        $data['title'] = __('FAQ');
        $data['items'] = Faq::where('status',STATUS_ACTIVE)->get();

        return view('user.faq.index', $data);
    }

    // show notification
    public function showNotification(Request $request)
    {
        $notification = Notification::where('id', $request->id)->first();
        $data['title'] = $notification->title;
        $data['notice'] = $notification->notification_body;
        $data['date'] = date('d M y', strtotime($notification->created_at));


        $read = $notification->update(['status' => 1]);
        return response()->json(['data' => $data]);
    }
}
