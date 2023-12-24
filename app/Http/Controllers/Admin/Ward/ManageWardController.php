<?php

namespace App\Http\Controllers\Admin\Ward;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Enum\MessageTypeEnum;
use App\Http\Controllers\Enum\UserTypeEnum;
use App\Models\AdminRole;
use App\Models\Ward;
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
            $wards = Ward::latest()->first()->paginate(env('PAGINATION_XSMALL'));;
            return view('backend.pages.ward.ward_list')->with('count',$count)->with('wards',$wards);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back()->with('error_message', trans('messages.error_message.global_error_message'));
        }
    }

    public function deleteWard($id){
        try {
            $Ward = Ward::find(($id));
            $Ward->delete();
            return redirect()->back()->with('TOASTR_MESSAGE', MessageTypeEnum::WARNING . 'ওয়ার্ডের তথ্য মুছে ফেলা হয়েছে');
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back()->with('error_message', trans('messages.error_message.global_error_message'));
        }
    }

    public function addWard(){
        return view('backend.pages.ward.ward_form');
    }

    public function addWardSubmit(Request $request){

        $ward = new Ward();

        $ward->name = $request['name'];
        $ward->bn_name = $request['bn_name'];
        $ward->ward_no = $request['ward_no'];

        //dd($ward);
        $ward->save();

        return redirect()->route('viewWards');
    }

    public function modifyWard($id){
        $ward = Ward::find($id);
        return view('backend.pages.ward.ward_modify')->with('ward',$ward);

    }

    public function modifyWardSubmit(Request $request,$id){

        $ward = Ward::find($id);

        $ward->name = $request['name'];
        $ward->bn_name = $request['bn_name'];
        $ward->ward_no = $request['ward_no'];

        //dd($ward);
        $ward->save();

        return redirect()->route('viewWards');
    }
}
