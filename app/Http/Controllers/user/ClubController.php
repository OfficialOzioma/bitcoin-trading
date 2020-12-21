<?php

namespace App\Http\Controllers\user;

use App\Http\Requests\TransferCoinRequest;
use App\Model\MembershipBonusDistributionHistory;
use App\Model\MembershipClub;
use App\Model\MembershipPlan;
use App\Model\Wallet;
use App\Repository\ClubRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ClubController extends Controller
{
    /**
     * Initialize product service
     *
     * ProductController constructor.
     */
    public function __construct()
    {
        $this->clubRepo = new ClubRepository;
    }

    // club membership plan
    public function membershipClubPlan()
    {
        $data['title'] = __('Membership Plans');
        $data['plans'] = MembershipPlan::where(['status'=>STATUS_ACTIVE])->get();
        $data['wallets'] = Wallet::where(['user_id' => Auth::id()])->where('balance','>',0)->get();
        $data['small_plan'] = MembershipPlan::where(['status'=>STATUS_ACTIVE])->orderBy('amount','asc')->first();

        if (isset($data['plans'][0])) {
            return view('user.club.plan',$data);
        } else {
            return redirect()->back()->with('dismiss', __('No plan found'));
        }
    }

    // my membership details
    public function myMembership(Request $request)
    {
        $data['title'] = __('My Membership Details');
        $data['club'] = MembershipClub::where(['user_id' => Auth::id(), 'status'=>STATUS_ACTIVE])->first();

        if ($request->ajax()) {
            $data['items'] = MembershipBonusDistributionHistory::join('users', 'users.id','=','membership_bonus_distribution_histories.user_id')
                ->where('membership_bonus_distribution_histories.user_id',Auth::id())
                ->select('membership_bonus_distribution_histories.*','users.email as email');

            return datatables()->of($data['items'])
                ->addColumn('plan_id', function ($item) {
                    return !empty($item->plan->plan_name) ? $item->plan->plan_name : 'N/A';
                })
                ->addColumn('wallet_id', function ($item) {
                    return !empty($item->wallet->name) ? $item->wallet->name : 'N/A';
                })
                ->addColumn('status', function ($item) {
                    return status($item->status);
                })
                ->make(true);
        }
        return view('user.club.membership',$data);
    }

    // transfer coin to club
    public function transferCoinToClub(TransferCoinRequest $request)
    {
        $response = $this->clubRepo->transferCoinToMembershipClub($request);
        if ($response['success'] == true) {
            return redirect()->back()->with('success', $response['message']);
        } else {
            return redirect()->back()->withInput()->with('success', $response['message']);
        }
    }


    // transfer coin to my wallet
    public function transferCoinToWallet(TransferCoinRequest $request)
    {
        $response = $this->clubRepo->transferCoinToMyWallet($request);
        if ($response['success'] == true) {
            return redirect()->back()->with('success', $response['message']);
        } else {
            return redirect()->back()->withInput()->with('success', $response['message']);
        }
    }
}
