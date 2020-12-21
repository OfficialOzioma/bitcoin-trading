<?php

namespace App\Http\Controllers\admin;

use App\Model\AdminSetting;
use App\Model\Faq;
use App\Repository\SettingRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->settingRepo = new SettingRepository();
    }
    // admin setting
    public function adminSettings(Request $request)
    {
        if (isset($request->itech) && ($request->itech == 99)) {
            $data['itech'] = 'itech';
        }
        $data['tab']='general';
        if(isset($_GET['tab'])){
            $data['tab']=$_GET['tab'];
        }
        $data['title'] = __('General Settings');
        $data['settings'] = allsetting();

        return view('admin.settings.general', $data);
    }

    // admin common settings save process
    public function adminCommonSettings(Request $request)
    {
        $rules=[];
//        $messages=[];
        if(!empty($request->logo)){
            $rules['logo']='image|mimes:jpg,jpeg,png|max:2000';
        }
        if(!empty($request->favicon)){
            $rules['favicon']='image|mimes:jpg,jpeg,png|max:2000';
        }
        if(!empty($request->login_logo)){
            $rules['login_logo']='image|mimes:jpg,jpeg,png|max:2000';
        }
        if(!empty($request->coin_price)){
            $rules['coin_price']='numeric';
        }
        if(!empty($request->number_of_confirmation)){
            $rules['number_of_confirmation']='integer';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = [];
            $e = $validator->errors()->all();
            foreach ($e as $error) {
                $errors[] = $error;
            }
            $data['message'] = $errors;

            return redirect()->route('adminSettings', ['tab' => 'general'])->with(['dismiss' => $errors[0]]);
        }
        try {
            if ($request->post()) {
                $response = $this->settingRepo->saveCommonSetting($request);
                if ($response['success'] == true) {
                    return redirect()->route('adminSettings', ['tab' => 'general'])->with('success', $response['message']);
                } else {
                    return redirect()->route('adminSettings', ['tab' => 'general'])->withInput()->with('success', $response['message']);
                }
            }
        } catch(\Exception $e) {
            return redirect()->back()->with(['dismiss' => $e->getMessage()]);
        }

    }

    // admin email setting save
    public function adminSaveEmailSettings(Request $request)
    {
        if ($request->post()) {
            $rules = [
                'mail_host' => 'required'
                ,'mail_port' => 'required'
                ,'mail_username' => 'required'
                ,'mail_password' => 'required'
                ,'mail_encryption' => 'required'
                ,'mail_from_address' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errors = [];
                $e = $validator->errors()->all();
                foreach ($e as $error) {
                    $errors[] = $error;
                }
                $data['message'] = $errors;
                return redirect()->route('adminSettings', ['tab' => 'email'])->with(['dismiss' => $errors[0]]);
            }

            try {
                $response = $this->settingRepo->saveEmailSetting($request);
                if ($response['success'] == true) {
                    return redirect()->route('adminSettings', ['tab' => 'email'])->with('success', $response['message']);
                } else {
                    return redirect()->route('adminSettings', ['tab' => 'email'])->withInput()->with('success', $response['message']);
                }
            } catch(\Exception $e) {
                return redirect()->back()->with(['dismiss' => $e->getMessage()]);
            }
        }
    }

    // admin twillo setting save
    public function adminSaveSmsSettings(Request $request)
    {
        if ($request->post()) {
            $rules = [
                'twillo_secret_key' => 'required'
                ,'twillo_auth_token' => 'required'
                ,'twillo_number' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errors = [];
                $e = $validator->errors()->all();
                foreach ($e as $error) {
                    $errors[] = $error;
                }
                $data['message'] = $errors;
                return redirect()->route('adminSettings', ['tab' => 'sms'])->with(['dismiss' => $errors[0]]);
            }

            try {
                $response = $this->settingRepo->saveTwilloSetting($request);
                if ($response['success'] == true) {
                    return redirect()->route('adminSettings', ['tab' => 'sms'])->with('success', $response['message']);
                } else {
                    return redirect()->route('adminSettings', ['tab' => 'sms'])->withInput()->with('success', $response['message']);
                }
            } catch(\Exception $e) {
                return redirect()->back()->with(['dismiss' => $e->getMessage()]);
            }
        }
    }


    // admin referral setting save
    public function adminReferralFeesSettings(Request $request)
    {
        if ($request->post()) {
            $rules = [
                'referral_signup_reward' => 'required|numeric'
            ];
            if($request->fees_level1) {
                $rules['fees_level1'] = 'numeric|min:0|max:100';
            }
            if($request->fees_level2) {
                $rules['fees_level2'] = 'numeric|min:0|max:100';
            }
            if($request->fees_level3) {
                $rules['fees_level3'] = 'numeric|min:0|max:100';
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errors = [];
                $e = $validator->errors()->all();
                foreach ($e as $error) {
                    $errors[] = $error;
                }
                $data['message'] = $errors;
                return redirect()->route('adminSettings', ['tab' => 'referral'])->with(['dismiss' => $errors[0]]);
            }

            try {
                $response = $this->settingRepo->saveReferralSetting($request);
                if ($response['success'] == true) {
                    return redirect()->route('adminSettings', ['tab' => 'referral'])->with('success', $response['message']);
                } else {
                    return redirect()->route('adminSettings', ['tab' => 'referral'])->withInput()->with('success', $response['message']);
                }
            } catch(\Exception $e) {
                return redirect()->back()->with(['dismiss' => $e->getMessage()]);
            }
        }
    }

    // admin withdrawal setting save
    public function adminWithdrawalSettings(Request $request)
    {
        if ($request->post()) {
            $rules = [
                'minimum_withdrawal_amount' => 'required|numeric',
                'maximum_withdrawal_amount' => 'required|numeric',
                'max_send_limit' => 'required|numeric',
                'send_fees_type' => 'required|numeric',
                'send_fees_fixed' => 'required|numeric',
                'send_fees_percentage' => 'required|numeric',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errors = [];
                $e = $validator->errors()->all();
                foreach ($e as $error) {
                    $errors[] = $error;
                }
                $data['message'] = $errors;
                return redirect()->route('adminSettings', ['tab' => 'withdraw'])->with(['dismiss' => $errors[0]]);
            }

            try {
                $response = $this->settingRepo->saveWithdrawSetting($request);
                if ($response['success'] == true) {
                    return redirect()->route('adminSettings', ['tab' => 'withdraw'])->with('success', $response['message']);
                } else {
                    return redirect()->route('adminSettings', ['tab' => 'withdraw'])->withInput()->with('success', $response['message']);
                }
            } catch(\Exception $e) {
                return redirect()->back()->with(['dismiss' => $e->getMessage()]);
            }
        }
    }

    // admin referral setting save
    public function adminSavePaymentSettings(Request $request)
    {
        if ($request->post()) {
            $rules = [
                'COIN_PAYMENT_PUBLIC_KEY' => 'required',
                'COIN_PAYMENT_PRIVATE_KEY' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errors = [];
                $e = $validator->errors()->all();
                foreach ($e as $error) {
                    $errors[] = $error;
                }
                $data['message'] = $errors;
                return redirect()->route('adminSettings', ['tab' => 'payment'])->with(['dismiss' => $errors[0]]);
            }

            try {
                $response = $this->settingRepo->savePaymentSetting($request);
                if ($response['success'] == true) {
                    return redirect()->route('adminSettings', ['tab' => 'payment'])->with('success', $response['message']);
                } else {
                    return redirect()->route('adminSettings', ['tab' => 'payment'])->withInput()->with('success', $response['message']);
                }
            } catch(\Exception $e) {
                return redirect()->back()->with(['dismiss' => $e->getMessage()]);
            }
        }
    }

    // Faq List
    public function adminFaqList(Request $request)
    {
        $data['title'] = __('FAQs');
        if ($request->ajax()) {
            $data['items'] = Faq::orderBy('id', 'desc');
            return datatables()->of($data['items'])
                ->addColumn('status', function ($item) {
                    return status($item->status);
                })
                ->addColumn('actions', function ($item) {
                    return '<ul class="d-flex activity-menu">
                        <li class="viewuser"><a href="' . route('adminFaqEdit', $item->id) . '"><i class="fa fa-pencil"></i></a> </li>
                        <li class="deleteuser"><a href="' . route('adminFaqDelete', $item->id) . '"><i class="fa fa-trash"></i></a></li>
                        </ul>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('admin.faq.list', $data);
    }

    // View Add new faq page
    public function adminFaqAdd(){
        $data['title']=__('Add FAQs');
        return view('admin.faq.addEdit',$data);
    }

    // Create New faq
    public function adminFaqSave(Request $request){
        $rules=[
            'question'=>'required',
            'answer'=>'required',
            'status'=>'required',
        ];
        $messages = [
            'question.required' => __('Question field can not be empty'),
            'answer.required' => __('Answer field can not be empty'),
            'status.required' => __('Status field can not be empty'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = [];
            $e = $validator->errors()->all();
            foreach ($e as $error) {
                $errors[] = $error;
            }
            return redirect()->back()->withInput()->with(['dismiss' => $errors[0]]);
        }

        $data=[
            'question'=>$request->question
            ,'answer'=>$request->answer
            ,'status'=>$request->status
            ,'author'=>Auth::id()
        ];
        if(!empty($request->edit_id)){
            Faq::where(['id'=>$request->edit_id])->update($data);
            return redirect()->route('adminFaqList')->with(['success'=>__('Faq Updated Successfully!')]);
        }else{
            Faq::create($data);
            return redirect()->route('adminFaqList')->with(['success'=>__('Faq Added Successfully!')]);
        }
    }

    // Edit Faqs
    public function adminFaqEdit($id){
        $data['title']=__('Update FAQs');
        $data['item']=Faq::findOrFail($id);

        return view('admin.faq.addEdit',$data);
    }

    // Delete Faqs
    public function adminFaqDelete($id){
        if(isset($id)){
            Faq::where(['id'=>$id])->delete();
        }

        return redirect()->back()->with(['success'=>__('Deleted Successfully!')]);
    }

    // admin payment setting
    public function adminPaymentSetting()
    {
        $data['title'] = __('Payment Method');
        $data['settings'] = allsetting();
        $data['payment_methods'] = paymentMethods();

        return view('admin.settings.payment-method', $data);
    }

    // chnage payment method status
    public function changePaymentMethodStatus(Request $request)
    {
        $settings = allsetting();
        if (!empty($request->active_id)) {
            $value = 1;
            $item = isset($settings[$request->active_id]) ? $settings[$request->active_id] : 2;
            if ($item == 1) {
                $value = 2;
            } elseif ($item == 2) {
                $value = 1;
            }
            AdminSetting::updateOrCreate(['slug' => $request->active_id], ['value' => $value]);
        }
        return response()->json(['message'=>__('Status changed successfully')]);
    }
}
