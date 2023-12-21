<?php

namespace App\Http\Controllers\Admin\Collector;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Enum\MessageTypeEnum;
use App\Http\Controllers\Enum\UserTypeEnum;
use App\Models\AdminRole;
use App\Models\User;
use App\UtilityFunction;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ManageCollectorController extends Controller
{
    public function viewCollectors()
    {
        try {
            $users_count = User::where('user_type', UserTypeEnum::COLLECTOR)->where('user_status', UserTypeEnum::ACTIVE_USER)->orderby('id', 'DESC')->get()->count();
            $users = User::where('user_type', UserTypeEnum::COLLECTOR)->where('user_status', UserTypeEnum::ACTIVE_USER)->orderby('id', 'DESC')->paginate(env('PAGINATION_XSMALL'));
            return view('backend.pages.collectors.collector_lists')->with('users', $users)->with('users_count', $users_count);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back()->with('error_message', trans('messages.error_message.global_error_message'));
        }
    }

    public function collectorUserSave(Request $request)
    {
        try {
            $admin_user = new User();
            $admin_roles = AdminRole::all();

            if (isset($_GET['edit_id']) || isset($_GET['add_id'])) {
                if (isset($_GET['edit_id'])) {
                    $admin_user = User::findOrFail($_GET['edit_id']);
                }
                $data_generate = '';
                $data_generate .= '<div class="form-group"><div class="row">';

                $data_generate .= '<div class="col-sm-12"><div class="form-group"><label class="required_field">সিস্টেম জেনারেটেড কালেক্টর আইডি(কালেক্টর এই আইডি ব্যাবহার করে সিস্টেমে লগইন করতে পারবেন)</label>
                                <input required type="text" class="form-control" name="collector_id"';
                if (isset($admin_user->collector_id)) {
                    $data_generate .= ' value="' . $admin_user->collector_id . '" readonly>';
                } else {
                    $data_generate .= ' value="' . UtilityFunction::defaultSystemGeneratedCollectorId() . '" readonly>';
                }
                $data_generate .= '</div></div>';

                $data_generate .= '<div class="col-sm-12"><div class="form-group"><label class="required_field">ব্যবহারকারীর নাম</label>
                                <input required type="text" class="form-control" name="username"  value="' . $admin_user->username . '"></div></div>';

                $data_generate .= '<div class="col-sm-6"><div class="form-group"><label>ইমেইল(যদি জানা থাকে)</label>
                                <input type="email" class="form-control" name="email"  value="' . $admin_user->email . '"></div></div>';

                $data_generate .= '<div class="col-sm-6"><div class="form-group"><label>মোবাইল নম্বর(যদি জানা থাকে)</label>
                                <input type="text" class="form-control" name="phone"  value="' . $admin_user->phone . '"></div></div>';
                if (!isset($_GET['add_id'])) {
                    $data_generate .= '<div class="col-sm-12"><span class="label text-info">(পুরাতন পাসওয়ার্ড বিদ্যমান রাখতে পাসওয়ার্ড ফাঁকা রাখুন)</span><div class="clearfix"></div></div>';
                }
                $data_generate .= '<div class="col-sm-6"><div class="form-group"><label ';
                if (isset($_GET['add_id'])) {
                    $data_generate .= ' class="required_field" ';
                }
                $data_generate .= '>পাসওয়ার্ড</label>
                                    <input type="password" name="password" class="form-control"';
                if (isset($_GET['add_id'])) {
                    $data_generate .= ' required ';
                }
                $data_generate .= '>
                               </div></div>';

                $data_generate .= '<div class="col-sm-6"><div class="form-group"><label ';
                if (isset($_GET['add_id'])) {
                    $data_generate .= ' class="required_field" ';
                }
                $data_generate .= '>কনফার্ম পাসওয়ার্ড</label>
                                    <input type="password" name="confirm_password" class="form-control"';
                if (isset($_GET['add_id'])) {
                    $data_generate .= ' required ';
                }
                $data_generate .= '>
                               </div></div>';

                /* $data_generate .= '<div class="col-sm-6"><div class="form-group"><label class="required_field">রোল</label>
                                <select  class="form-control" name="admin_role_id" required><option value="">নির্বাচন করুন</option>';

                foreach ($admin_roles as $admin_role) {
                    $data_generate .= '<option ';
                    if ($admin_user->admin_role_id == $admin_role->id) $data_generate .= ' selected ';
                    $data_generate .= 'value="' . $admin_role->id . '">' . $admin_role->name . '</option>';
                }

                $data_generate .= '</select></div></div>'; */

                $data_generate .= '<div class="col-sm-12"><div class="form-group"><label>ছবি</label>
                                    <input type="file" name="profile_image" class="form-control">
                               </div></div>';

                $data_generate .= '</div></div>';

                if (!isset($_GET['add_id']))
                    $data_generate .= '<input type="hidden" class="form-control" name="edit_id" value="' . $admin_user->id . '">';

                return response()->json(array('success' => true, 'data_generate' => $data_generate));
            } else {
                $admin_user_id = $request->input('edit_id');

                if (isset($admin_user_id)) {
                    try {
                        $validator = Validator::make($request->all(), [
                            'email' => 'nullable|unique:users,email,' . $admin_user_id,
                            'phone' => 'nullable|unique:users,phone,' . $admin_user_id,
                        ], [
                            'email.unique' => 'এই ইমেইল দিয়ে ইতিমধ্যে সিস্টেমে আইডি খোলা আছে!',
                            'phone.unique' => 'এই মোবাইল নম্বর দিয়ে ইতিমধ্যে সিস্টেমে আইডি খোলা আছে!',
                        ]);

                        if ($validator->fails()) {
                            $firstError = $validator->errors()->first();
                            return redirect()->back()->with('error_message', $firstError)->withInput();
                        }
                        $admin_user = User::find($admin_user_id);
                    } catch (DecryptException $e) {
                        return redirect()->back()->with('error_message', 'Error: ' . $e->getMessage());
                    }
                } else {
                    $validator = Validator::make($request->all(), [
                        'email' => 'nullable|unique:users,email,',
                        'phone' => 'nullable|unique:users,phone,',
                    ], [
                        'email.unique' => 'এই ইমেইল দিয়ে ইতিমধ্যে সিস্টেমে আইডি খোলা আছে!',
                        'phone.unique' => 'এই মোবাইল নম্বর দিয়ে ইতিমধ্যে সিস্টেমে আইডি খোলা আছে!',
                    ]);

                    if ($validator->fails()) {
                        $firstError = $validator->errors()->first();
                        return redirect()->back()->with('error_message', $firstError)->withInput();
                    }
                }

                //if (!WorkFlowUsers::with('getApplicationFormWorkflowUser')->where('user_id', $admin_user_id)->exists()) {
                if (isset($admin_user)) {
                    $admin_user->collector_id = $request->collector_id;
                    $admin_user->username = $request->username;
                    $admin_user->email = $request->email;
                    $admin_user->phone = $request->phone;

                    if (!empty($request->password)) {
                        if ($request->password == $request->confirm_password) {
                            $admin_user->password = bcrypt($request->password);
                        } else {
                            return redirect()->back()->with('TOASTR_MESSAGE', MessageTypeEnum::ERROR . 'পাসওয়ার্ড ও কনফার্ম পাসওয়ার্ড একই হতে হবে');
                        }
                    }

                    //$admin_user->admin_role_id = $request->admin_role_id;

                    $path = env('USER_PHOTO_PATH');

                    $image = $request->file('profile_image');
                    if (isset($image)) {
                        $imageName = time() . '.png';
                        if ($image->move($path, $imageName)) {
                            $admin_user->photo = $imageName;
                        }
                    }
                    $admin_user->user_type = UserTypeEnum::COLLECTOR;
                    $admin_user->save();
                }

                if (isset($admin_user_id))
                    return redirect()->back()->with('TOASTR_MESSAGE', MessageTypeEnum::SUCCESS . trans('messages.lang.update_success_message'));
                else
                    return redirect()->back()->with('TOASTR_MESSAGE', MessageTypeEnum::SUCCESS . trans('messages.lang.insert_success_message'));
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back()->with('TOASTR_MESSAGE', MessageTypeEnum::ERROR . $e->getMessage());
        }
    }

    public function makeCollectorUserInactive($id)
    {
        try {
            $inactivate_user = User::find(($id));
            $inactivate_user->user_status = 0;
            $inactivate_user->save();
            return redirect()->back()->with('TOASTR_MESSAGE', MessageTypeEnum::WARNING . 'ব্যবহারকারীকে নিষ্ক্রিয় করা হয়েছে!');
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back()->with('error_message', trans('messages.error_message.global_error_message'));
        }
    }
    public function makeCollectorUserActive($id)
    {
        try {
            $active_user = User::find(($id));
            $active_user->user_status = 1;
            $active_user->save();
            return redirect()->back()->with('TOASTR_MESSAGE', MessageTypeEnum::SUCCESS . 'ব্যবহারকারীকে পুনরায় সক্রিয় করা হয়েছে!');
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back()->with('error_message', trans('messages.error_message.global_error_message'));
        }
    }
}
