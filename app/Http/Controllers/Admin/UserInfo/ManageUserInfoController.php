<?php

namespace App\Http\Controllers\Admin\UserInfo;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Enum\MessageTypeEnum;
use App\Http\Controllers\Enum\UserTypeEnum;
use App\Models\AdminRole;
use App\Models\UserInfo;
use App\Models\User;
use App\UtilityFunction;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ManageUserInfoController extends Controller
{
    public function viewUserInfo()
    {
        try {
            $count = UserInfo::count();
            $userinfos = DB::table('user_infos')
                ->join('users', 'user_infos.user_id', '=', 'users.id')
                ->select('user_infos.*', 'users.username')
                ->paginate(env('PAGINATION_XSMALL'));
            //$userinfos = UserInfo::latest()->first()->paginate(env('PAGINATION_XSMALL'));
            //dd($userinfos);
            return view('backend.pages.userinfo.user_info')->with('count', $count)->with('userinfos', $userinfos);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back()->with('error_message', trans('messages.error_message.global_error_message'));
        }
    }

    public function deleteUserInfo($id)
    {
        try {
            $UserInfo = UserInfo::find(($id));
            $UserInfo->delete();
            return redirect()->back()->with('TOASTR_MESSAGE', MessageTypeEnum::WARNING . 'ব্যবহারকারীর তথ্য মুছে ফেলা হয়েছে');
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back()->with('error_message', trans('messages.error_message.global_error_message'));
        }
    }

    public function userInfoSave(Request $request)
    {

        try {
            $userInfo = new UserInfo();
            $users = User::all();

            if (isset($_GET['edit_id']) || isset($_GET['add_id'])) {
                if (isset($_GET['edit_id'])) {
                    $userInfo = UserInfo::findOrFail($_GET['edit_id']);
                }
                $data_generate = '';
                $data_generate .= '<div class="form-group"><div class="row">';
                $data_generate .= '<div class="col-sm-6"><div class="form-group"><label class="required_field">ব্যবহারকারীর নাম</label>
                                <select required type="text" class="form-control" name="user_id" value="'. $userInfo->user_id .'" >';
                foreach ($users as $user) {
                    $data_generate .= '<option value="'. $user->id .'">'. $user->id .'-'. $user->username .'</option>';
                }
                $data_generate .= '</select></div></div>';
                $data_generate .= '<div class="col-sm-6"><div class="form-group"><label class="required_field">ওয়ার্ডের নাম</label>
                                <select required type="text" class="form-control" name="user_id" value="'. $userInfo->user_id .'" >';
                foreach ($users as $user) {
                    $data_generate .= '<option value="'. $user->id .'">'. $user->id .'-'. $user->username .'</option>';
                }
                $data_generate .= '</select></div></div>';
                $data_generate .= '</div></div>';

                if (!isset($_GET['add_id']))
                    $data_generate .= '<input type="hidden" class="form-control" name="edit_id" value="' . $userInfo->id . '">';

                return response()->json(array('success' => true, 'data_generate' => $data_generate));
            } else {
                $id = $request->input('edit_id');

                if(isset($id)){
                    $userInfo = UserInfo::find($id);
                }

                /*if (isset($ward_id)) {
                    try {
                        $validator = Validator::make($request->all(), [
                            'ward_no' => 'nullable|ward_no|unique:wards,ward_no,' . $ward_id,
                        ], [
                            'ward_no.unique' => 'এই ইমেইল দিয়ে ইতিমধ্যে সিস্টেমে আইডি খোলা আছে!',
                        ]);

                        if ($validator->fails()) {
                            $firstError = $validator->errors()->first();
                            return redirect()->back()->with('error_message', $firstError)->withInput();
                        }
                        $admin_user = User::find($ward_id);
                    } catch (DecryptException $e) {
                        return redirect()->back()->with('error_message', 'Error: ' . $e->getMessage());
                    }
                } else {
                    $validator = Validator::make($request->all(), [
                        'email' => 'nullable|email|unique:users,email,',
                        'phone' => 'nullable|unique:users,phone,',
                    ], [
                        'email.unique' => 'এই ইমেইল দিয়ে ইতিমধ্যে সিস্টেমে আইডি খোলা আছে!',
                        'phone.unique' => 'এই মোবাইল নম্বর দিয়ে ইতিমধ্যে সিস্টেমে আইডি খোলা আছে!',
                    ]);

                    if ($validator->fails()) {
                        $firstError = $validator->errors()->first();
                        return redirect()->back()->with('error_message', $firstError)->withInput();
                    }
                }*/

                //if (!WorkFlowUsers::with('getApplicationFormWorkflowUser')->where('user_id', $admin_user_id)->exists()) {

                    $userInfo->user_id = $request['user_id'];
                    $userInfo->save();

                if (isset($id))
                    return redirect()->back()->with('TOASTR_MESSAGE', MessageTypeEnum::SUCCESS . trans('messages.lang.update_success_message'));
                else
                    return redirect()->back()->with('TOASTR_MESSAGE', MessageTypeEnum::SUCCESS . trans('messages.lang.insert_success_message'));
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back()->with('TOASTR_MESSAGE', MessageTypeEnum::ERROR . $e->getMessage());
        }

    }

    /* public function viewUserInfos()
    {
        try {
            $joinedData = DB::table('user_infos')
                ->join('users', 'user_infos.user_id', '=', 'users.id')
                ->select('user_infos.*', 'users.username')
                ->get();
            dd($joinedData);
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    } */
}
