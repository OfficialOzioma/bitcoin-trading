<?php

namespace App\Http\Controllers\user;

use Aloha\Twilio\Twilio;
use App\Http\Requests\driveingVerification;
use App\Http\Requests\passportVerification;
use App\Http\Requests\resetPasswordRequest;
use App\Http\Requests\UserProfileUpdate;
use App\Http\Requests\verificationNid;
use App\Http\Services\AuthService;
use App\Http\Services\SmsService;
use App\Model\ActivityLog;
use App\Model\User\Wallet;
use App\Model\VerificationDetails;
use App\User;
use Clickatell\Rest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    //my profile
    public function userProfile(Request $request)
    {
        $data['title'] = __('My Profile');
        $data['user'] = User::where('id', Auth::id())->first();
        $data['clubInfos'] = get_plan_info(Auth::id());
        $data['nid_front'] = VerificationDetails::where('user_id',Auth::id())->where('field_name','nid_front')->first();
        $data['nid_back'] = VerificationDetails::where('user_id',Auth::id())->where('field_name','nid_back')->first();
        $data['pass_front'] = VerificationDetails::where('user_id',Auth::id())->where('field_name','pass_front')->first();
        $data['pass_back'] = VerificationDetails::where('user_id',Auth::id())->where('field_name','pass_back')->first();
        $data['drive_front'] = VerificationDetails::where('user_id',Auth::id())->where('field_name','drive_front')->first();
        $data['drive_back'] = VerificationDetails::where('user_id',Auth::id())->where('field_name','drive_back')->first();
        $data['qr'] = (!empty($request->qr)) ? $request->qr : 'profile-tab';

        if($request->ajax()){
            $data['activities'] = ActivityLog::where('user_id', Auth::id())->select('*');

            return datatables()->of($data['activities'])
                ->addColumn('action',function ($item) {return userActivity($item->action);})
                ->make(true);
        }
        return view('user.profile.profile', $data);
    }


    // profile upload image
    public function uploadProfileImage(Request $request)
    {
        $rules['file_one'] = 'required|image|max:3048|mimes:jpg,jpeg,png,jpg,gif,svg|max:2048|dimensions:max_width=500,max_height=500';
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


    // update user profile
    public function userProfileUpdate(UserProfileUpdate $request)
    {
        if (strpos($request->phone, '+') !== false) {
            return redirect()->back()->with('dismiss',__("Don't put plus sign with phone number"));
        }
        $data['first_name'] = $request->first_name;
        $data['last_name'] = $request->last_name;
        $data['country'] = $request->country;
        $data['gender'] = $request->gender;
        $user = (!empty($request->id)) ? User::find(decrypt($request->id)) : Auth::user();

        if ($user->phone != $request->phone){
            $data['phone'] =  $request->phone;
            $data['phone_verified'] = 0;
        }
        $user->update($data);

        return redirect()->back()->with('success',__('Profile updated successfully'));
    }

    // send sms
    public function sendSMS()
    {
        if (!empty(Auth::user()->phone)) {
            if (!empty(Cookie::get('code'))) {
                $key = Cookie::get('code');
            } else {
                $key = randomNumber(8);
            }
            $minute = 100;
            try {
                Cookie::queue(Cookie::make('code', $key, $minute * 60));
                $text = __('Your verification code id ') . ' ' . $key;
                $number = '+' . Auth::user()->phone;
                if (settings('sms_getway_name') == 'twillo') {
                    $sendSms = app(SmsService::class)->send("+".$number, $text);
                }

                return redirect()->back()->with('success', __('We sent a verification code in your phone please input this code in this box.'));
            } catch (\Exception $exception) {
                Cookie::queue(Cookie::forget('code'));
                return redirect()->back()->with('dismiss', __('Please contact your system admin,Something went wrong.'));
            }
        } else {
            return redirect()->back()->with('dismiss', 'you should add your phone number first.');
        }
    }

    // phone verification process
    public function phoneVerify(Request $request)
    {
        if (!empty($request->code)) {
            $cookie = Cookie::get('code');
            if (!empty($cookie)) {
                if ($request->code == $cookie) {
                    $user = User::find(Auth::id());
                    $user->phone_verified = 1;
                    $user->save();
                    Cookie::queue(Cookie::forget('code'));

                    return redirect()->back()->with('success',__('Phone verified successfully.'));
                } else {
                    return redirect()->back()->with('dismiss',__('You entered wrong OTP.'));
                }
            } else {
                return redirect()->back()->with('dismiss',__('Your OTP is expired.'));
            }
        } else {
            return redirect()->back()->with('dismiss',__("OTP can't be empty."));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function nidUpload(verificationNid $request)
    {
        $img = Input::file('file_two');
        $img2 = Input::file('file_three');

        if ($img !== null) {
            $details = VerificationDetails::where('user_id', Auth::id())->where('field_name', 'nid_front')->first();
            if (empty($details)) {
                $details = new VerificationDetails();
            }

            $details->field_name = 'nid_front';
            $details->user_id = Auth::id();
            $details->status = STATUS_PENDING;
            $photo = uploadFile($img, IMG_USER_PATH, !empty($details->photo) ? $details->photo : '');
            $details->photo = $photo;
            $details->save();
        }
        if ($img2 !== null) {

            $details = VerificationDetails::where('user_id', Auth::id())->where('field_name', 'nid_back')->first();
            if (empty($details)) {
                $details = new VerificationDetails();
            }
            $details->field_name = 'nid_back';
            $details->user_id = Auth::id();
            $details->status = STATUS_PENDING;
            $photo = uploadFile($img2, IMG_USER_PATH, !empty($details->photo) ? $details->photo : '');
            $details->photo = $photo;
            $details->save();
        }

        return response()->json(['success' => true, 'message' => __('NID photo uploaded successfully')]);
    }

    // upload passport
    public function passUpload(passportVerification $request)
    {
        $img = Input::file('file_two');
        $img2 = Input::file('file_three');

        if ($img !== null) {
            $details= VerificationDetails::where('user_id',Auth::id())->where('field_name','pass_front')->first();
            if (empty($details)) {
                $details = new VerificationDetails();
            }

            $details->field_name = 'pass_front';
            $details->user_id = Auth::id();
            $details->status = STATUS_PENDING;
            $photo = uploadFile($img, IMG_USER_PATH, !empty($details->photo) ? $details->photo : '');
            $details->photo = $photo;
            $details->save();
        }

        if ($img2 !== null) {
            $details= VerificationDetails::where('user_id',Auth::id())->where('field_name','pass_back')->first();
            if (empty($details)){
                $details = new VerificationDetails();
            }
            $details->field_name = 'pass_back';
            $details->user_id = Auth::id();
            $details->status = STATUS_PENDING;
            $photo = uploadFile($img2, IMG_USER_PATH, !empty($details->photo) ? $details->photo : '');
            $details->photo = $photo;
            $details->save();
        }

        return response()->json(['success'=>true,'message'=>__('Passport photo uploaded successfully')]);
    }

    // driving licence upload
    public function driveUpload(driveingVerification $request)
    {
        $img = Input::file('file_two');
        $img2 = Input::file('file_three');

        if ($img !== null) {
            $details= VerificationDetails::where('user_id',Auth::id())->where('field_name','drive_front')->first();
            if (empty($details)){
                $details = new VerificationDetails();
            }

            $details->field_name = 'drive_front';
            $details->user_id = Auth::id();
            $details->status = STATUS_PENDING;
            $photo = uploadFile($img, IMG_USER_PATH, !empty($details->photo) ? $details->photo : '');
            $details->photo = $photo;
            $details->save();
        }

        if ($img2 !== null) {
            $details= VerificationDetails::where('user_id',Auth::id())->where('field_name','drive_back')->first();
            if (empty($details)) {
                $details = new VerificationDetails();
            }
            $details->field_name = 'drive_back';
            $details->user_id = Auth::id();
            $details->status = STATUS_PENDING;
            $photo = uploadFile($img2, IMG_USER_PATH, !empty($details->photo) ? $details->photo : '');
            $details->photo = $photo;
            $details->save();
        }

        return response()->json(['success'=>true,'message'=>__('Driving licence photo uploaded successfully')]);
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
}
