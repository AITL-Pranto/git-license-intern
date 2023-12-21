<?php

namespace App\Http\Controllers\Admin\RolePermission;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Enum\MessageTypeEnum;
use App\Models\AdminPermission;
use App\Models\AdminRole;
use App\Models\AdminRolePermission;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RolePermissionController extends Controller
{

    public function rolePermission()
    {
        try {
            $roles_count = AdminRole::orderBy('id', 'desc')->get()->count();
            $roles = AdminRole::orderBy('id', 'desc')->paginate(env('PAGINATION_XSMALL'));
            return view('backend.pages.rolepermission.role_permission')->with('roles', $roles)->with('roles_count', $roles_count);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back()->with('error_message', trans('messages.error_message.global_error_message'));
        }
    }

    //save & edit role
    public function rolePermissionSave(Request $request)
    {
        try {
            $admin_roles = new AdminRole();
            $admin_roles_permissions = new AdminRolePermission();

            $allPermissions = AdminPermission::orderBy('name', 'asc')->get();

            if (isset($_GET['edit_id']) || isset($_GET['add_id'])) {

                if (isset($_GET['edit_id'])) {

                    $admin_roles = AdminRole::where('id', $_GET['edit_id'])->first();

                    $user_permissions = AdminRolePermission::leftjoin('admin_roles', 'admin_roles.id', '=', 'admin_role_permissions.admin_role_id')
                        ->leftjoin('admin_permissions', 'admin_permissions.id', '=', 'admin_role_permissions.admin_permission_id')
                        ->select('admin_role_permissions.admin_permission_id', 'admin_permissions.name')
                        ->where('admin_role_permissions.admin_role_id', $_GET['edit_id'])
                        ->orderBy('admin_permissions.name', 'asc')->get();

                    $assignedPermissions = AdminRole::join('admin_role_permissions', 'admin_roles.id', '=', 'admin_role_permissions.admin_role_id')
                        ->join('admin_permissions', 'admin_permissions.id', '=', 'admin_role_permissions.admin_permission_id')
                        ->select(
                            'admin_permissions.id as permission_id',
                            'admin_permissions.name as permission_name',
                            'admin_role_permissions.admin_permission_id'
                        )
                        ->where('admin_roles.id', '=', $_GET['edit_id'])
                        ->orderBy('admin_permissions.name', 'asc')->get();

                    $unassignedPermissions = [];
                    $pos = 0;
                    foreach ($allPermissions as $allPermission) {
                        for ($i = 0; $i < count($assignedPermissions); $i++) {
                            if ($assignedPermissions[$i]->admin_permission_id == $allPermission->id) break;
                        }
                        if ($i == count($assignedPermissions)) $unassignedPermissions[$pos++] = $allPermission;
                    }
                }

                $data_generate = '';
                $data_generate .= '<div class="row"><div class="col-sm-12"><div class="form-body"><div class="form-group"><label for="form_control_1" class="required">রোলের নাম</label>';
                $data_generate .= '<input type="text" name="name" class="form-control" id="name" value="' . $admin_roles->name . '" required></div></div></div></div>';
                $data_generate .= '<div class="row"><div class="col-sm-5"><label for="form_control_1">সকল পারমিশন</label><select style="width: 100%;height: 235px; -webkit-appearance: none;  -moz-appearance: none; appearance: none;" multiple="multiple" class="multi-select" id="my_multi_select1">';
                if (isset($_GET['add_id'])) {
                    if (isset($allPermissions[0])) {
                        foreach ($allPermissions as $ap) {
                            $data_generate .= '<option style="padding-left: 8px;" value="' . $ap->id . '">' . $ap->name . '</option>';
                        }
                    }
                } else {
                    if (isset($unassignedPermissions[0])) {
                        foreach ($unassignedPermissions as $ap) {
                            $data_generate .= '<option style="padding-left: 8px;" value="' . $ap->id . '">' . $ap->name . '</option>';
                        }
                    }
                }
                $data_generate .= '</select></div>';

                $data_generate .= '<div class="col-sm-2">';
                $data_generate .= '<div style="margin-bottom: 10px;margin-top: 70px;"><input id="left" type="button" style="width: 100%;font-size: 16px;" value="<"/></div>';
                $data_generate .= '<div style="margin-bottom: 10px;"><input type="button" id="right" style="width: 100%;font-size: 16px;" value=">"/></div>';
                $data_generate .= '<div style="margin-bottom: 10px;"><input type="button" style="width: 100%;font-size: 16px;" id="leftall" value="<<"/></div>';
                $data_generate .= '<div><input type="button" style="width: 100%;font-size: 16px;" id="rightall" value=">>"/></div></div>';

                $data_generate .= '<div class="col-sm-5"><label for="form_control_1">নিয়োগকৃত পারমিশন</label><select style="width: 100%;height: 235px;-webkit-appearance: none;  -moz-appearance: none; appearance: none;" multiple="multiple" class="multi-select" id="my_multi_select2" name="assigned_permissions[]">';
                if (isset($user_permissions[0])) {
                    foreach ($user_permissions as $ap) {
                        $data_generate .= '<option style="padding-left: 8px;" value="' . $ap->admin_permission_id . '">' . $ap->name . '</option>';
                    }
                }
                $data_generate .= '</select></div>';

                if (!isset($_GET['add_id']))
                    $data_generate .= '<input type="hidden" id="edit_id" class="form-control" name="edit_id" value="' . Crypt::encrypt($admin_roles->id) . '"></div>';

                return response()->json(array('success' => true, 'data_generate' => $data_generate));
            } else {


                $encrypted_id = $request->input('edit_id');

                if (isset($encrypted_id)) {
                    try {
                        $admin_roles = AdminRole::find(Crypt::decrypt($encrypted_id));
                        $admin_roles_permissions = AdminRolePermission::where('admin_role_id', Crypt::decrypt($encrypted_id));
                    } catch (DecryptException $e) {
                        return redirect()->back()->with('TOASTR_MESSAGE', MessageTypeEnum::ERROR . $e->getMessage());
                    }
                }
                DB::beginTransaction();
                if (isset($admin_roles)) {
                    $admin_roles->name = $request->input('name');
                    $admin_roles->save();
                }
                if (isset($admin_roles_permissions)) {
                    if (isset($encrypted_id)) AdminRolePermission::where('admin_role_id', Crypt::decrypt($encrypted_id))->delete();
                    $assigned_permissions = $request->input('assigned_permissions');
                    if (is_null($assigned_permissions)) {
                        $assigned_permissions = [];
                    }
                    $admin_roles_permissions = [];
                    for ($i = 0; $i < count($assigned_permissions); $i++) {
                        $admin_roles_permissions[] = [
                            'admin_role_id' => $admin_roles->id,
                            'admin_permission_id' => $assigned_permissions[$i]
                        ];
                    }

                    AdminRolePermission::insert($admin_roles_permissions);
                }

                DB::commit();

                if (isset($encrypted_id))
                    return redirect()->back()->with('TOASTR_MESSAGE', MessageTypeEnum::SUCCESS . 'রোল এবং পারমিশন সফলভাবে আপডেট করা হয়েছে');
                else
                    return redirect()->back()->with('TOASTR_MESSAGE', MessageTypeEnum::SUCCESS . 'রোল এবং পারমিশন সফলভাবে যুক্ত করা হয়েছে');
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back()->with('error_message', trans('messages.error_message.global_error_message'));
        }
    }

    //delete role
    public function rolePermissionDelete($id)
    {
        try {
            $role = AdminRole::findorfail($id);
            $user = User::where('admin_role_id', $id)->exists();
            if ($user == true) {
                return redirect()->back()->with('TOASTR_MESSAGE', MessageTypeEnum::ERROR . trans('messages.error_message.delete_user_under_this_role'));
            } else {
                DB::beginTransaction();
                AdminRolePermission::where('admin_role_id', $id)->delete();
                $role->delete();
                DB::commit();
                return redirect()->back()->with('TOASTR_MESSAGE', MessageTypeEnum::SUCCESS . 'সফলভাবে মুছে ফেলা হয়েছে');
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back()->with('error_message', trans('messages.error_message.global_error_message'));
        }
    }
}
