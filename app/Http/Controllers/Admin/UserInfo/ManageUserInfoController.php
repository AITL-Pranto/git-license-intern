<?php

namespace App\Http\Controllers\Admin\UserInfo;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Enum\MessageTypeEnum;
use App\Http\Controllers\Enum\UserTypeEnum;
use App\Models\AdminRole;
use App\Models\Ward;
use App\Models\UserInfo;
use App\Models\User;
use App\UtilityFunction;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ManageUserInfoController extends Controller
{
    public function viewUserInfo()
    {
        try {
            $count = UserInfo::count();
            $userinfos = UserInfo::latest()->first()->paginate(env('PAGINATION_XSMALL'));
            //dd($userinfos);
            return view('backend.pages.userinfo.user_info')->with('count', $count)->with('userinfos', $userinfos);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back()->with('error_message', trans('messages.error_message.global_error_message'));
        }
    }
}
