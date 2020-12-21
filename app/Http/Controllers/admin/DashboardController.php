<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\resetPasswordRequest;
use App\Http\Requests\UserProfileUpdate;
use App\Http\Services\AuthService;
use App\Http\Services\CommonService;
use App\Jobs\SendMail;
use App\Model\BuyCoinHistory;
use App\Model\DepositeTransaction;
use App\Model\MembershipBonusDistributionHistory;
use App\Model\MembershipClub;
use App\Model\Wallet;
use App\Model\WithdrawHistory;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        $data['title'] = __('Admin Dashboard');
        $data['total_coin'] = Wallet::sum('balance');
        $data['total_sold_coin'] = BuyCoinHistory::sum('coin');
        $data['total_blocked_coin'] = MembershipClub::sum('amount');
        $data['total_member'] = MembershipClub::where('status',STATUS_ACTIVE)->count();
        $data['bonus_distribution'] = MembershipBonusDistributionHistory::where('status',STATUS_ACTIVE)->sum('bonus_amount');
        $data['total_user'] = User::count();
        $total_active_user = User::where('status', STATUS_ACTIVE)->count();
        $total_inactive_user = User::where('status','<>', STATUS_ACTIVE)->count();
        $data['active_percentage'] = ($total_active_user*100)/$data['total_user'];
        $data['inactive_percentage'] = ($total_inactive_user*100)/$data['total_user'];
        $allMonths = all_months();
        // deposit
        $monthlyDeposits = DepositeTransaction::select(DB::raw('sum(amount) as totalDepo'), DB::raw('MONTH(created_at) as months'))
            ->whereYear('created_at', Carbon::now()->year)
            ->where('status', STATUS_SUCCESS)
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
        $monthlyWithdrawals = WithdrawHistory::select(DB::raw('sum(amount) as totalWithdraw'), DB::raw('MONTH(created_at) as months'))
            ->whereYear('created_at', Carbon::now()->year)
            ->where('status', STATUS_SUCCESS)
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

        return view('admin.dashboard', $data);
    }

    // admin profile
    public function adminProfile(Request $request)
    {
        $data['title'] = __('Profile');
        $data['tab']='profile';
        $data['user']= User::where('id', Auth::id())->first();
        $data['settings'] = allsetting();

        return view('admin.profile.index',$data);
    }

    // update user profile
    public function UserProfileUpdate(UserProfileUpdate $request)
    {
        if (strpos($request->phone, '+') !== false) {
            return redirect()->back()->with('dismiss',__("Don't put plus sign with phone number"));
        }
        $data['first_name'] = $request->first_name;
        $data['last_name'] = $request->last_name;
        $user = (!empty($request->id)) ? User::find(decrypt($request->id)) : Auth::user();

        if ($user->phone != $request->phone){
            $data['phone'] =  $request->phone;
            $data['phone_verified'] = null;
        }
        $user->update($data);

        return redirect()->back()->with('success',__('Profile updated successfully'));
    }

    // profile upload image
    public function uploadProfileImage(Request $request)
    {
        $rules['file_one'] = 'required|image|max:2024|mimes:jpg,jpeg,png,jpg,gif,svg|max:2048|dimensions:max_width=500,max_height=500';
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            $message = $validator->getMessageBag()->getMessages()['file_one'][0];
            if ($message == 'The file one has invalid image dimensions.')
                $message =  __('Image size must be less than (height:500,width:500)');

            return redirect()->back()->with('dismiss',$message);
        }
        try {
            $img = Input::file('file_one');
            $user_data = (!empty($request->id) ) ? User::find(decrypt($request->id)) : Auth::user();

            if ($img !== null) {
                $photo = uploadFile($img, IMG_USER_PATH, !empty($user_data->photo) ? $user_data->photo : '');
                $user = User::find($user_data->id);
                $user->photo  = $photo;
                $user->save();
                return redirect()->back()->with('success',__('Profile picture uploaded successfully'));
            } else {
                return redirect()->back()->with('dismiss',__('Please input a image'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('dismiss', $e->getMessage());
        }

    }

    public function changePasswordSave(resetPasswordRequest $request)
    {
        $service = new AuthService();
        $change = $service->changePassword($request);
        if ($change['success']) {
            return redirect()->back()->with('success',$change['message']);
        } else {
            return redirect()->back()->with('dismiss',$change['message']);
        }
    }

    // send email
    public function sendEmail()
    {
        $data['title'] = __('Send Email');

        return view('admin.notification.email', $data);
    }

    //send notification
    public function sendNotification()
    {
        $data['title'] = __('Send Notification');

        return view('admin.notification.notification', $data);
    }

    // send mail process
    public function sendEmailProcess(Request $request)
    {
        $rules = [
            'subject' => 'required',
            'email_message' => 'required',
            'email_type' => 'required'
        ];
        $messages = [
            'subject.required' => __('Subject field can not be empty'),
            'email_message.required' => __('Message field can not be empty'),
            'email_type.required' => __('Email type field can not be empty'),
        ];
        $validator = Validator::make( $request->all(), $rules, $messages );
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with(['dismiss' => $validator->errors()->first() ]);
        } else {
            $data['subject'] = $request->subject;
            $data['email_message'] = $request->email_message;
            $data['type'] = $request->email_type;
            $data['mailTemplate'] = 'email.genericemail';

            if (!empty($request->email_headers)) {
                $data['email_header'] = $request->email_headers;
            }
            if (!empty($request->footers)) {
                $data['email_footer'] = $request->footers;
            }

//            app(CommonService::class)->sendEmailToAlUser($data);
            dispatch(new SendMail($data))->onQueue('send-email');

            return redirect()->back()->with('success',__('Mail sent successfully'));
        }
    }

    // send notification process
    public function sendNotificationProcess(Request $request)
    {
        $rules = [
            'title' => 'required',
            'notification_body' => 'required',
        ];

        $messages = [
            'title.required' => 'Notification title can not be empty',
            'notification_body.required' => 'Notification body can not be empty',
        ];

        $this->validate($request, $rules, $messages);

        $service = new CommonService();
        try {
            $response = $service->sendNotificationProcess($request);
            return redirect()->back()->with(['success' => 'Notification sent successfully']);

        } catch (\Exception $exception) {
            return redirect()->back()->with(['dismiss' => 'Something went wrong. Please try again']);
        }
    }
}
