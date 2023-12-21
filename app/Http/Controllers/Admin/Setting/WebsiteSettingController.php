<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class WebsiteSettingController extends Controller
{
    protected $destinationPath;

    public function __construct()
    {
        $website_info = Setting::first();
        View::share('website_info', $website_info);
        $this->destinationPath = env('WEBSITE_PHOTO_PATH');
    }

    public function getWebsiteSettings()
    {
        try {
            $setting =  Setting::first();
            return view('backend.pages.settings.settings')->with('setting', $setting);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back()->with('error_message', trans('messages.error_message.global_error_message'));
        }
    }

    public function saveWebsiteSettings(Request $request)
    {
        try {
            $setting = Setting::first();
            $setting->website_name = $request->website_name;
            $setting->website_short_name = $request->website_short_name;
            $setting->website_url = $request->website_url;
            $setting->website_email = $request->website_email;
            $setting->website_fax = $request->website_fax;
            $setting->website_contact = $request->website_contact;
            $setting->website_code = $request->website_code;
            $setting->website_eiin = $request->website_eiin;
            $setting->website_moto = $request->website_moto;
            $setting->helpline = $request->helpline;
            $setting->website_copyright_text = $request->website_copyright_text;
            $setting->website_address = $request->website_address;
            $setting->website_place_id = $request->website_place_id;
            $website_logo = $request->website_logo;
            $admin_panel_logo = $request->admin_panel_logo;
            $student_panel_logo = $request->student_panel_logo;
            $user_panel_logo = $request->user_panel_logo;
            $website_favicon = $request->website_favicon;
            $website_home_page_banner_left_side = $request->website_home_page_banner_left_side;
            $website_home_page_banner_right_side = $request->website_home_page_banner_right_side;
            if (isset($request->website_logo)) {
                $imageName = time() . '_website_logo.png';
                if ($website_logo->move($this->destinationPath, $imageName)) {
                    $setting->website_logo = $imageName;
                }
            }
            if (isset($request->admin_panel_logo)) {
                $imageName = time() . '_admin_panel_logo.png';
                if ($admin_panel_logo->move($this->destinationPath, $imageName)) {
                    $setting->admin_panel_logo = $imageName;
                }
            }
            if (isset($request->student_panel_logo)) {
                $imageName = time() . '_student_panel_logo.png';
                if ($student_panel_logo->move($this->destinationPath, $imageName)) {
                    $setting->student_panel_logo = $imageName;
                }
            }
            if (isset($request->user_panel_logo)) {
                $imageName = time() . '_user_panel_logo.png';
                if ($user_panel_logo->move($this->destinationPath, $imageName)) {
                    $setting->user_panel_logo = $imageName;
                }
            }
            if (isset($request->website_favicon)) {
                $imageName = time() . '_website_favicon.png';
                if ($website_favicon->move($this->destinationPath, $imageName)) {
                    $setting->website_favicon = $imageName;
                }
            }
            if (isset($request->website_home_page_banner_left_side)) {
                $imageName = time() . '_website_home_page_banner_left_side.png';
                if ($website_home_page_banner_left_side->move($this->destinationPath, $imageName)) {
                    $setting->website_home_page_banner_left_side = $imageName;
                }
            }
            if (isset($request->website_home_page_banner_right_side)) {
                $imageName = time() . '_website_home_page_banner_right_side.png';
                if ($website_home_page_banner_right_side->move($this->destinationPath, $imageName)) {
                    $setting->website_home_page_banner_right_side = $imageName;
                }
            }
            $setting->home_banner_video_link = $request->home_banner_video_link;
            $setting->website_meta_keywords = $request->website_meta_keywords;
            $setting->website_meta_description = $request->website_meta_description;
            $setting->seo_keyword = $request->seo_keyword;
            $setting->website_facebook_link = $request->website_facebook_link;
            $setting->website_twitter_link = $request->website_twitter_link;
            $setting->website_youtube_page_link = $request->website_youtube_page_link;
            $setting->website_linkdein_link = $request->website_linkdein_link;
            $setting->website_printerest_link = $request->website_printerest_link;
            $setting->website_instagram_link = $request->website_instagram_link;
            $setting->whatsapp_number = $request->whatsapp_number;
            $setting->website_tiktok_link = $request->website_tiktok_link;
            $setting->play_store_link = $request->play_store_link;
            $setting->apple_store_link = $request->apple_store_link;
            $setting->wallet_minimum_balace = $request->wallet_minimum_balace;
            $setting->wallet_maximum_balance = $request->wallet_maximum_balance;
            $setting->website_video_id = $request->website_video_id;
            $setting->about_us = $request->about_us;
            $setting->refund_return = $request->refund_return;
            $setting->terms_condition = $request->terms_condition;
            $setting->privacy_policy = $request->privacy_policy;
            $setting->sms_gateway = $request->sms_gateway;
            $setting->save();
            return redirect()->back()->with('success_message', trans('messages.lang.update_success_message'));
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back()->with('error_message', trans('messages.error_message.global_error_message'));
        }
    }
}
