<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\AdminCreateUser;
use App\Model\UserVerificationCode;
use App\Model\VerificationDetails;
use App\Model\Wallet;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    // user list
    public function adminUsers(Request $request)
    {
        $data['title'] = __('Users');
        if ( !$request->ajax() ) {
            return view('admin.users.users', $data);
        } else {
            $users = [];
            if ($request->type == 'active_users') {}
            $users = User::where('status', STATUS_SUCCESS)->where('id', '<>', Auth::user()->id);
            if ($request->type == 'suspend_user')
                $users = User::where('status', STATUS_SUSPENDED)->where('id', '<>', Auth::user()->id);
            if ($request->type == 'deleted_user')
                $users = User::where('status', STATUS_DELETED)->where('id', '<>', Auth::user()->id);
            if ($request->type == 'email_pending')
                $users = User::where('is_verified','!=', STATUS_SUCCESS )->where('id', '<>', Auth::user()->id);
            return datatables($users)

                ->addColumn('status', function ($item) {
                    return statusAction($item->status);
                })
                ->addColumn('type', function ($item) {
                    return userRole($item->role);
                })
                ->editColumn('created_at', function ($item) {
                    return $item->created_at ? with(new Carbon($item->created_at))->format('d M Y') : '';
                })
                ->filterColumn('created_at', function ($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(created_at,'%d %M %Y') like ?", ["%$keyword%"]);
                })
                ->addColumn('activity', function ($item) use ($request) {
                    return getActionHtml($request->type,$item->id,$item);
                })
                ->rawColumns(['activity'])
                ->make(true);
        }
    }

    // generate verification key
    private function generate_email_verification_key()
    {
        $key = randomNumber(6);
        return $key;
    }

    // create and edit user
    public function UserAddEdit(AdminCreateUser $request)
    {
        DB::beginTransaction();
        try {
            if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
                return redirect()->back()->withInput()->with('dismiss', __('Invalid email address'));
            }
            $mail_key = $this->generate_email_verification_key();
            $user = User::create([
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
                'email' => $request['email'],
                'role' => $request->role,
                'phone' => $request->phone,
                'status' => STATUS_SUCCESS,
                'password' => Hash::make(randomString(8)),
            ]);

            Wallet::create([
                'user_id' => $user->id,
                'name' => 'Default wallet',
                'status' => STATUS_SUCCESS,
                'is_primary' => STATUS_SUCCESS,
                'balance' => 0.0000000,

            ]);

            $key = randomNumber(6);
            $existsToken = User::join('user_verification_codes', 'user_verification_codes.user_id', 'users.id')
                ->where('user_verification_codes.user_id', $user->id)
                ->whereDate('user_verification_codes.expired_at', '>=', Carbon::now()->format('Y-m-d'))
                ->first();

            if ( !empty($existsToken) ) {
                $token = $existsToken->code;
            } else {
                $s = UserVerificationCode::create(['user_id' => $user->id, 'code' => $key, 'expired_at' => date('Y-m-d', strtotime('+15 days')), 'status' => STATUS_PENDING]);
                $token = $key;
            }

            $user_data = [
                'email' => $user->email,
                'token' => $token,
            ];

            DB::commit();
            try {
                Mail::send('email.password_reset', $user_data, function ($message) use ($user) {
                    $message->to($user->email, $user->username)->from(settings('mail_from'), env('APP_NAME'))->subject('Change password');
                });
                $data['message'] = 'Mail sent Successfully to ' . $user->email . ' with Password reset Code.';
                $data['success'] = true;
                Session::put(['resend_email' => $user->email]);

                return redirect()->back()->with('success', $data['message']);
            } catch (\Exception $e) {
                return redirect()->back()->with('dismiss', __('New user created successfully but Mail not sent'));
            }
            // all good
        } catch (\Exception $e) {
            DB::rollback();
        }

        if ( !empty($user) ) {
            $userName = $user->first_name . ' ' . $user->last_name;
            $userEmail = $user->email;
            $subject = __('Email Verification | :companyName', ['companyName' => env('APP_NAME')]);
            $data['data'] = $user;
            $data['key'] = $mail_key;

            Mail::send('email.verifyWeb', $data, function ($message) use ($user) {
                $message->to($user->email, $user->username)->from('noreply@monttra.com', env('APP_NAME'))->subject('Email confirmation');
            });

            return redirect()->route('login')->with('success', __('Email send successful,please verify your email'));

        } else {
            return redirect()->back()->with('dismiss', __('Something went wrong'));
        }
    }

    // user edit page
    public function adminUserProfile(Request $request)
    {
        $data['title'] = __('User Profile');
        $data['user'] = User::find(decrypt($request->id));
        $data['type'] = $request->type;
        $data['clubInfos'] = get_plan_info($data['user']->id);

        return view('admin.users.profile',$data);
    }

    // user edit page
    public function UserEdit(Request $request)
    {
        $data['title'] = __('User Edit');
        $data['user'] = User::find(decrypt($request->id));
        $data['type'] = $request->type;
        return view('admin.users.edit',$data);
    }

    // delete user
    public function adminUserDelete($id)
    {
        $user = User::find(decrypt($id));
        $user->status = STATUS_DELETED;
        $user->save();
        return redirect()->back()->with('success','User deleted successfully');
    }

    // suspend user
    public function adminUserSuspend($id){
        $user = User::find(decrypt($id));
        $user->status = STATUS_SUSPENDED;
        $user->save();
        return redirect()->back()->with('success','User suspended successfully');
    }

    // remove user gauth
    public function adminUserRemoveGauth($id){
        $user = User::find(decrypt($id));
        $user->google2fa_secret = '';
        $user->g2f_enabled  = '0';
        $user->save();
        return redirect()->back()->with('success','User gauth removed successfully');
    }

    // activate user
    public function adminUserActive($id){
        $user = User::find(decrypt($id));
        $user->status = STATUS_SUCCESS;
        $user->save();
        return redirect()->back()->with('success','User activated successfully');
    }

    // verify user email
    public function adminUserEmailVerified($id){
        $user = User::find(decrypt($id));
        $user->is_verified = STATUS_SUCCESS;
        $user->save();
        return redirect()->back()->with('success','Email verified successfully');
    }

    //ID Verification
    public function adminUserIdVerificationPending(Request $request)
    {
        if ($request->ajax()) {
            $data['items'] = VerificationDetails::join('users','users.id','verification_details.user_id')
                ->select('users.id','users.updated_at', 'users.first_name', 'users.last_name', 'users.email')
                ->groupBy('user_id')
                ->where('verification_details.status',STATUS_PENDING)
                ->where(function ($query) {
                });

            return datatables()->of($data['items'])
                ->addColumn('actions', function ($item) {
                    return '<ul class="d-flex activity-menu">
                        <li class="viewuser"><a title="'.__('Details').'" href="' . route('adminUserDetails', encrypt($item->id)) . '?tab=photo_id"><i class="fa fa-eye"></i></a></li>
                        </ul>';
                })->rawColumns(['actions'])
                ->make(true);
        }

        return view('admin.users.users-pending-id-verification');
    }

    // verification details
    public function VerificationDetails($id){
        $data['user_id'] = $id;
        $data['pending'] = VerificationDetails::where('user_id',decrypt($id))->where('status',STATUS_PENDING)->get();
        $data['fields_name'] = VerificationDetails::where('user_id',decrypt($id))->where('status',STATUS_PENDING)->get()->pluck('id','field_name')->toArray();
        if (!empty($data['pending'])) {
            return view('admin.users.users-pending-id-verification-details',$data);
        }

        return redirect()->route('adminUserIdVerificationPending');
    }

    // activate user verification
    public function adminUserVerificationActive($id,$type)
    {
        try {
            if ($type == 'nid'){
                $verified = ['nid_front','nid_back'];
                VerificationDetails::where('user_id',decrypt($id))
                    ->whereIn('field_name',$verified)->update(['status'=>STATUS_SUCCESS]);

                return redirect()->route('adminUserIdVerificationPending')->with(['success' => __('Successfully Updated')]);
            } elseif ($type == 'driving'){
                $verified = ['drive_front','drive_back'];
                VerificationDetails::where('user_id',decrypt($id))
                    ->whereIn('field_name',$verified)->update(['status'=>STATUS_SUCCESS]);

                return redirect()->route('adminUserIdVerificationPending')->with(['success' => __('Successfully Updated')]);
            } elseif ($type == 'passport') {
                $verified = ['pass_front','pass_back'];
                VerificationDetails::where('user_id',decrypt($id))
                    ->whereIn('field_name',$verified)->update(['status'=>STATUS_SUCCESS]);

                return redirect()->route('adminUserIdVerificationPending')->with(['success' => __('Successfully Updated')]);
            }
        } catch (\Exception $exception){
            return redirect()->route('adminUserIdVerificationPending')->with(['dismiss' => __('Something went wrong')]);
        }
    }

    // verification reject process
    public function varificationReject(Request $request){
        try {
            $companyName = env('APP_NAME');
            $data['data'] = User::find(decrypt($request->user_id));
            $data['cause'] = $request->couse;
            $data['email'] = $data['data']->email;
            $user = $data['data'] ;
            Mail::send('email.verification_fields', $data, function ($message) use ($user) {
                $message->to($user->email, $user->name)->from(settings('mail_from'), env('APP_NAME'))->subject('Id Verification');
            });

            if (isset($request->ids[0])) {
                foreach ($request->ids as $key => $value) {
                    deleteFile(IMG_USER_PATH, $value);
                }
            }
            VerificationDetails::whereIn('photo',$request->ids)->update(['status'=>STATUS_REJECTED, 'photo'=>'']);

            return redirect()->route('adminUserIdVerificationPending')->with('success',__('Rejected successfully'));
        } catch (\Exception $e) {
//            return redirect()->back()->with('dismiss', $e->getMessage());
            return redirect()->back()->with('dismiss',__('Something went wrong'));
        }

    }

    public function sendIdVerificationEmail($data,$user)
    {
        Mail::send('email.verification_fields', $data, function ($message) use ($user) {
            $message->to($user->email, $user->name)->from(settings('mail_from'), env('APP_NAME'))->subject('Id Verification');
        });
        return true;
    }

}
