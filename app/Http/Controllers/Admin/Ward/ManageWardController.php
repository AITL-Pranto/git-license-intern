<?php

namespace App\Http\Controllers\Admin\Ward;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Enum\MessageTypeEnum;
use App\Http\Controllers\Enum\UserTypeEnum;
use App\Models\AdminRole;
use App\Models\Ward;
use App\Models\User;
use App\UtilityFunction;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ManageWardController extends Controller
{
    public function viewWards()
    {
        try {
            $count = Ward::count();
            $wards = Ward::latest()->first()->paginate(env('PAGINATION_XSMALL'));
            ;
            return view('backend.pages.ward.ward_list')->with('count', $count)->with('wards', $wards);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back()->with('error_message', trans('messages.error_message.global_error_message'));
        }
    }

    public function deleteWard($id)
    {
        try {
            $Ward = Ward::find(($id));
            $Ward->delete();
            return redirect()->back()->with('TOASTR_MESSAGE', MessageTypeEnum::WARNING . 'ওয়ার্ডের তথ্য মুছে ফেলা হয়েছে');
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back()->with('error_message', trans('messages.error_message.global_error_message'));
        }
    }

    public function addWard()
    {
        return view('backend.pages.ward.ward_form');
    }

    public function wardSave(Request $request)
    {

        try {
            $ward = new Ward();

            if (isset($_GET['edit_id']) || isset($_GET['add_id'])) {
                if (isset($_GET['edit_id'])) {
                    $ward = Ward::findOrFail($_GET['edit_id']);
                }
                $data_generate = '';
                $data_generate .= '<div class="form-group"><div class="row">';
                $data_generate .= '<div class="col-sm-12"><div class="form-group"><label class="required_field">ওয়ার্ডের নাম</label>
                                <input required type="text" class="form-control" name="bn_name"  value="' . $ward->bn_name . '"></div></div>';

                $data_generate .= '<div class="col-sm-6"><div class="form-group">ওয়ার্ডের নাম (English)<label></label>
                                <input type="text" class="form-control" name="name"  value="' . $ward->name . '"></div></div>';

                $data_generate .= '<div class="col-sm-6"><div class="form-group"><label>ওয়ার্ড নম্বর</label>
                                <input type="number" class="form-control" name="ward_no"  value="' . $ward->ward_no . '"></div></div>';
                /*if (!isset($_GET['add_id'])) {
                    $data_generate .= '<div class="col-sm-12"><span class="label text-info">(পুরাতন পাসওয়ার্ড বিদ্যমান রাখতে পাসওয়ার্ড ফাঁকা রাখুন)</span><div class="clearfix"></div></div>';
                }*/

                $data_generate .= '</div></div>';

                if (!isset($_GET['add_id']))
                    $data_generate .= '<input type="hidden" class="form-control" name="edit_id" value="' . $ward->id . '">';

                return response()->json(array('success' => true, 'data_generate' => $data_generate));
            } else {
                $ward_id = $request->input('edit_id');

                if(isset($ward_id)){
                    $ward = Ward::find($ward_id);
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

                    $ward->name = $request['name'];
                    $ward->bn_name = $request['bn_name'];
                    $ward->ward_no = $request['ward_no'];
                    $ward->save();

                if (isset($ward_id))
                    return redirect()->back()->with('TOASTR_MESSAGE', MessageTypeEnum::SUCCESS . trans('messages.lang.update_success_message'));
                else
                    return redirect()->back()->with('TOASTR_MESSAGE', MessageTypeEnum::SUCCESS . trans('messages.lang.insert_success_message'));
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back()->with('TOASTR_MESSAGE', MessageTypeEnum::ERROR . $e->getMessage());
        }

    }

    /*public function addWardSubmit(Request $request){

        $ward = new Ward();

        $ward->name = $request['name'];
        $ward->bn_name = $request['bn_name'];
        $ward->ward_no = $request['ward_no'];

        //dd($ward);
        $ward->save();

        return redirect()->route('viewWards');
    } */
}
