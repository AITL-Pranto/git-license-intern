<?php

namespace App\Http\Controllers\Collector\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Enum\MessageTypeEnum;
use App\Http\Controllers\Enum\UserTypeEnum;
use App\Models\Setting;
use App\Models\User;
use App\UtilityFunction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class AuthController extends Controller
{
    protected $loginPath = '/login';
    protected $redirectTo = '/';
    protected $redirectAfterLogout = '/login';
    protected $redirectPath = '/dashboard';
    protected $destinationPath;

    public function __construct()
    {
        $website_info = Setting::first();
        View::share('website_info', $website_info);
        $this->destinationPath = env('USER_PHOTO_PATH');
    }

    public function collectorLogin(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email_or_mobile' => 'required',
                'password' => 'required',
            ], [
                'email_or_mobile.required' => trans('messages.error_message.email_or_mobile_is_required'),
                'password.required' => trans('messages.error_message.password_is_required'),
            ]);

            if ($validator->fails()) {
                $firstError = $validator->errors()->first();
                return redirect()->back()->with('error_message', $firstError)->withInput();
            }
            $get_Attempted_DB_UserDetails = User::where('user_type', UserTypeEnum::COLLECTOR);

            if (filter_var($request->email_or_mobile, FILTER_VALIDATE_EMAIL)) {
                $get_Attempted_DB_UserDetails = $get_Attempted_DB_UserDetails->where('email', trim($request->email_or_mobile));
            } else {
                $get_Attempted_DB_UserDetails = $get_Attempted_DB_UserDetails->where('phone', trim($request->email_or_mobile));
            }
            $get_Attempted_DB_UserDetails = $get_Attempted_DB_UserDetails->first();

            //verifying recapcha
            $recaptcha = UtilityFunction::recaptchaV3(env('NOCAPTCHA_SECRET'), $request->recaptcha_response);

            /* if ($recaptcha == false) {
                return redirect()->back()->with('error_message', trans('messages.error_message.captcha_error'));
            } */

            if (isset($get_Attempted_DB_UserDetails)) {
                if (Hash::check($request->password, $get_Attempted_DB_UserDetails->password)) {
                    if ($get_Attempted_DB_UserDetails->status == 0) {
                        if ($get_Attempted_DB_UserDetails->user_type == UserTypeEnum::COLLECTOR) {
                            if ($get_Attempted_DB_UserDetails->user_status == UserTypeEnum::ACTIVE_USER) {

                                if (filter_var($request->email_or_mobile, FILTER_VALIDATE_EMAIL)) {
                                    if (Auth::attempt(['email' => $request->email_or_mobile, 'password' => $request->password, 'user_type' => UserTypeEnum::COLLECTOR])) {
                                        return redirect()->route('viewCollectorDashboard');
                                    }
                                } else {
                                    if (Auth::attempt(['phone' => $request->email_or_mobile, 'password' => $request->password, 'user_type' => UserTypeEnum::COLLECTOR])) {
                                        return redirect()->route('viewCollectorDashboard');
                                    }
                                }
                            } else {
                                return redirect()->back()->with('error_message', trans('messages.error_message.suspended_user'));
                            }
                        } else {
                            return redirect()->back()->with('error_message', trans('messages.error_message.invalid_mobile_or_password'));
                        }
                    } else {
                        return redirect()->back()->with('error_message', trans('messages.error_message.suspended_user'));
                    }
                } else {
                    return redirect()->back()->with('error_message', trans('messages.error_message.invalid_mobile_or_password'));
                }
            }
            //return redirect()->back()->with('TOASTR_MESSAGE', MessageTypeEnum::ERROR . trans('messages.error_message.no_account_found'));
            return redirect()->back()->with('error_message', trans('messages.error_message.no_account_found'));
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back()->with('error_message', trans('messages.error_message.global_error_message'));
        }
    }

    protected function collectorLogout()
    {
        Auth::logout();
        session()->flush();
        return redirect()->intended($this->redirectAfterLogout);
    }

    public function viewAdminProfile()
    {
        try {
            return view('backend.auth.update_profile');
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back()->with('error_message', trans('messages.error_message.global_error_message'));
        }
    }

    public function updateAdminProfile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string',
            ], [
                'username.required' => 'আপনার পুরো নাম পূরণ করা আবশ্যিক',
            ]);

            if ($validator->fails()) {
                $firstError = $validator->errors()->first();
                return redirect()->back()->with('error_message', $firstError)->withInput();
            }

            $user = User::where('id', auth()->user()->id)->first();
            if (isset($user)) {
                $user->username = $request->username;
                $user->email = $request->email;
                $user->phone = $request->phone;
                $user->username = $request->username;
                $uncropped_photo = $request->upload_image;
                $cropped_photo = $request->cropped_image;
                if (isset($cropped_photo)) {
                    $image_array_1 = explode(";", $cropped_photo);
                    $image_array_2 = explode(",", $image_array_1[1]);
                    $data = base64_decode($image_array_2[1]);
                    $imageName = time() . '.png';
                    $path = $this->destinationPath . $imageName;
                    file_put_contents($path, $data);
                    $user->photo = $imageName;
                } else {
                    if (!empty($uncropped_photo)) {
                        $imageName = time() . '.png';
                        if ($uncropped_photo->move($this->destinationPath, $imageName)) {
                            $user->photo = $imageName;
                        }
                    }
                }
                $user->save();
                return redirect()->back()->with('success_message', trans('messages.lang.update_success_message'));
            } else {
                return redirect()->back()->with('error_message', trans('messages.error_message.global_error_message'));
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back()->with('error_message', trans('messages.error_message.global_error_message'));
        }
    }
    public function changeAdminPassword()
    {
        try {
            return view('backend.auth.change_password');
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back()->with('error_message', trans('messages.error_message.global_error_message'));
        }
    }

    public function updateAdminPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required',
                'password' => 'required',
                'confirm_password' => 'required',
            ], [
                'current_password.required' => 'পুরাতন পাসওয়ার্ড পূরণ করা আবশ্যিক!',
                'password.required' => trans('messages.error_message.password_is_required'),
                'confirm_password.required' => 'কনফার্ম পাসওয়ার্ড পূরণ করা আবশ্যিক',
            ]);

            if ($validator->fails()) {
                $firstError = $validator->errors()->first();
                return redirect()->back()->with('error_message', $firstError)->withInput();
            }
            $user = User::find(auth()->user()->id);
            if (isset($user)) {
                if ($request->password != $request->confirm_password) {
                    return redirect()->back()->with('error_message', 'নতুন প্রদানকৃত পাসওয়ার্ড এবং পুনরায় প্রদানকৃত পাসওয়ার্ড মিলছে না। সঠিকভাবে টাইপ করুন।');
                }
                if (!isset($user->password) || Hash::check($request->current_password, $user->password)) {
                    try {
                        $user->password = bcrypt($request->password);
                        $user->save();
                        return redirect()->back()->with('success_message', 'পাসওয়ার্ড সফলভাবে পরিবর্তন করা হয়েছে।');
                    } catch (\Exception $e) {
                        return redirect()->back()->with('error_message', trans('messages.error_message.global_error_message'));
                    }
                }
                return redirect()->back()->with('error_message', 'পুরাতন প্রদানকৃত পাসওয়ার্ড সঠিক নয়!');
            }
            return view('backend.auth.change_password');
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back()->with('error_message', trans('messages.error_message.global_error_message'));
        }
    }
}
