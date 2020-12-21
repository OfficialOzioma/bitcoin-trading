<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\Admin\PlanSaveRequest;
use App\Model\MembershipBonusDistributionHistory;
use App\Model\MembershipClub;
use App\Model\MembershipPlan;
use App\Model\MembershipTransactionHistory;
use App\Repository\ClubRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClubController extends Controller
{
    // member List
    public function membershipList(Request $request)
    {
        $data['title'] = __('Membership List');
        if ($request->ajax()) {
            $data['items'] = MembershipClub::join('users', 'users.id','=','membership_clubs.user_id')
            ->select('membership_clubs.*','users.email as email');
            return datatables()->of($data['items'])
                ->addColumn('plan_name', function ($item) {
                    return !empty($item->plan_id) ? $item->plan->plan_name : 'N/A';
                })
                ->addColumn('bonus', function ($item) {
                    return 0;
                })
                ->addColumn('status', function ($item) {
                    return status($item->status);
                })
                ->make(true);
        }

        return view('admin.club.member-list', $data);
    }

    // coin transfer history
    public function coinTransactionHistory(Request $request)
    {
        $data['title'] = __('Transaction List');
        if ($request->ajax()) {
            $data['items'] = MembershipTransactionHistory::join('users', 'users.id','=','membership_transaction_histories.user_id')
            ->select('membership_transaction_histories.*','users.email as email');
            return datatables()->of($data['items'])
                ->addColumn('wallet_id', function ($item) {
                    return !empty($item->wallet->name) ? $item->wallet->name : 'N/A';
                })
                ->addColumn('type', function ($item) {
                    return $item->type == CREDIT ? __('CREDIT') : __('DEBIT');
                })
                ->addColumn('status', function ($item) {
                    return status($item->status);
                })
                ->make(true);
        }

        return view('admin.club.transaction-list', $data);
    }

    // club bonus distribution history
    public function clubBonusDistribution(Request $request)
    {
        $data['title'] = __('Club Bonus Distribution');
        if ($request->ajax()) {
            $data['items'] = MembershipBonusDistributionHistory::join('users', 'users.id','=','membership_bonus_distribution_histories.user_id')
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

        return view('admin.club.bonus-distribution-list', $data);
    }


    // plan List
    public function planList(Request $request)
    {
        $data['title'] = __('Plan List');
        if ($request->ajax()) {
            $data['items'] = MembershipPlan::select('membership_plans.*');
            return datatables()->of($data['items'])
                ->addColumn('bonus_type', function ($item) {
                    return sendFeesType($item->bonus_type);
                })
                ->addColumn('status', function ($item) {
                    return status($item->status);
                })
                ->addColumn('actions', function ($item) {
                    return '<ul class="d-flex activity-menu">
                        <li class="viewuser"><a href="' . route('planEdit', $item->id) . '"><i class="fa fa-pencil"></i></a> </li>
                        </ul>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('admin.club.plan-list', $data);
    }

    // plan edit
    public function planEdit($id)
    {
        $data['title'] = __('Update Plan');
        $data['item'] = MembershipPlan::where('id', $id)->first();

        return view('admin.club.addEdit', $data);
    }

    // plan save
    public function planSave(PlanSaveRequest $request)
    {
        try {
            $response = app(ClubRepository::class)->saveClubPlan($request);
            if ($response['success'] == true) {
                return redirect()->route('planList')->with('success', $response['message']);
            } else {
                return redirect()->route('planList')->withInput()->with('success', $response['message']);
            }
        } catch(\Exception $e) {
            return redirect()->back()->with(['dismiss' => $e->getMessage()]);
        }
    }


    // club bonus distribution process
    public function adminClubBonusDistribution()
    {
        try {
            $response = app(ClubRepository::class)->clubBonusDistributionProcess();
            if ($response['success'] == true) {
                return redirect()->back()->with('success', $response['message']);
            } else {
                return redirect()->back()->withInput()->with('success', $response['message']);
            }
        } catch(\Exception $e) {
            return redirect()->back()->with(['dismiss' => $e->getMessage()]);
        }
    }
}
