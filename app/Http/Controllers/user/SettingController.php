<?php

namespace App\Http\Controllers\user;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use PragmaRX\Google2FA\Google2FA;

class SettingController extends Controller
{
    // user setting
    public function userSetting()
    {
        $data['title'] = __('Setting');
        $data['title'] = __('Settings');
        $default = $data['adm_setting'] = allsetting();
        $google2fa = new Google2FA();
        $google2fa->setAllowInsecureCallToGoogleApis(true);
        $data['google2fa_secret'] = $google2fa->generateSecretKey();

        $google2fa_url = $google2fa->getQRCodeGoogleUrl(
            isset($default['app_title']) && !empty($default['app_title']) ? $default['app_title'] : 'cPoket',
            isset(Auth::user()->email) && !empty(Auth::user()->email) ? Auth::user()->email : 'cpoket@email.com',
            $data['google2fa_secret']
        );
        $data['qrcode'] = $google2fa_url;

        return view('user.setting.setting', $data);
    }

    // google 2fa secret save
    public function g2fSecretSave(Request $request)
    {
        if (!empty($request->code)) {
            $user = User::find(Auth::id());
            $google2fa = new Google2FA();

            if ($request->remove != 1) {
                $valid = $google2fa->verifyKey($request->google2fa_secret, $request->code);
                if ($valid) {
                    $user->google2fa_secret = $request->google2fa_secret;
                    $user->g2f_enabled = 1;
                    $user->save();

                    return redirect()->back()->with('success', __('Google authentication code added successfully'));
                } else {
                    return redirect()->back()->with('dismiss', __('Google authentication code is invalid'));
                }

            } else {
                if (!empty($user->google2fa_secret)) {
                    $google2fa = new Google2FA();
                    $valid = $google2fa->verifyKey($user->google2fa_secret, $request->code);
                    if ($valid) {
                        $user->google2fa_secret = null;
                        $user->g2f_enabled = '0';
                        $user->save();
                        return redirect()->back()->with('success', __('Google authentication code remove successfully'));
                    } else {
                        return redirect()->back()->with('dismiss', __('Google authentication code is invalid'));
                    }
                } else {
                    return redirect()->back()->with('dismiss', __('Google authentication code is invalid'));
                }
            }
            return redirect()->back()->with('dismiss', __('Google authentication code is invalid'));
        }
        return redirect()->back()->with('dismiss', __('Google authentication code can not be empty'));
    }

    // enable google login
    public function googleLoginEnable(Request $request)
    {
        if (!empty(Auth::user()->google2fa_secret)) {
            $user = Auth::user();

            if ($user->g2f_enabled == 0) {
                $user->g2f_enabled = '1';
                Session::put('g2f_checked', true);
            } else {
                $user->g2f_enabled = '0';
                Session::forget('g2f_checked');
            }
            $user->update();

            if ($user->g2f_enabled == 1)
                return redirect()->back()->with('success', __('Google two factor authentication is enabled'));
            return redirect()->back()->with('success', __('Google two factor authentication is disabled'));
        } else
            return redirect()->back()->with('dismiss', __('For using google two factor authentication,please setup your authentication'));

    }

    // save preference
    public function savePreference(Request $request)
    {
        try {
            if ($request->lang) {
                User::where('id', Auth::id())->update(['language' => $request->lang]);
                return redirect()->back()->with('success', __('Language changed successfully'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('dismiss', __('Something went wrong.'));
        }
    }
}
