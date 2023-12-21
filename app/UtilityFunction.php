<?php

namespace App;

use App\Http\Controllers\Enum\MessageTypeEnum;
use App\Http\Controllers\Enum\UserTypeEnum;
use App\Models\Setting;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;

class UtilityFunction
{

    public static function getToastrMessage($message)
    {
        $message_array = explode("::", $message);
        if (sizeof($message_array) < 2 && $message_array[0] != "") {
            $message_array[1] = $message_array[0];
            $message_array[0] = MessageTypeEnum::SUCCESS; //default toastr message type success
        }

        $toastr = "";
        $message_array[0] = $message_array[0] . MessageTypeEnum::SEPARATOR;

        $toastr_configuration = '
            toastr.options = {
                extend: function () {
                    return {
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": true,
                        "progressBar": true,
                        "positionClass": "toastr-top-right",
                        "preventDuplicates": true,
                        "onclick": null,
                        "showDuration": "200",
                        "hideDuration": "1000",
                        "timeOut": "3000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    };
                }
            }
        ';


        if ($message_array[0] == MessageTypeEnum::SUCCESS)
            $toastr = 'toastr.success("' . $message_array[1] . '", "Success")';
        elseif ($message_array[0] == MessageTypeEnum::ERROR)
            $toastr = 'toastr.error("' . $message_array[1] . '", "Error")';
        elseif ($message_array[0] == MessageTypeEnum::WARNING)
            $toastr = 'toastr.warning("' . $message_array[1] . '", "Warning")';
        elseif ($message_array[0] == MessageTypeEnum::INFO)
            $toastr = 'toastr.info("' . $message_array[1] . '", "Info")';

        return "<script>" . $toastr_configuration . $toastr . "</script>";
    }

    public static function safe_b64decode($string)
    {
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    public static function safe_b64encode($string)
    {

        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }

    public static function getLocal()
    {
        try {
            $locale = Cookie::get('locale');

            if (is_null($locale) || empty($locale)) $locale = "bn";
            if ($locale != "en" && $locale != "bn")
                $locale = decrypt($locale);

            app()->setLocale($locale);
            return $locale;
        } catch (\Exception $e) {
            app()->setLocale("bn");

            return $locale;
        }
    }

    public static function switchLocal()
    {
        $locale = UtilityFunction::getLocal();
        if ($locale == 'bn') $locale = 'en';
        elseif ($locale == 'en') $locale = 'bn';
        Cookie::queue('locale', $locale);
        app()->setLocale(UtilityFunction::getLocal());
    }


    public static function setLocal()
    {
        $locale = UtilityFunction::getLocal();
        Cookie::queue('locale', $locale);
        app()->setLocale(UtilityFunction::getLocal());
    }


    public static function getBangleNumber($number)
    {
        $bn_digits = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');
        $en_digits = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        $bangle_number = str_replace($en_digits, $bn_digits, $number);
        return $bangle_number;
    }

    public static function getEnglishNumber($number)
    {
        $bn_digits = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');
        $en_digits = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        $english_number = str_replace($bn_digits, $en_digits, $number);
        return $english_number;
    }

    public static function getDateOnLanguage($date, $lan)
    {

        if (!empty($date)) {
            $engDATE = array(
                1, 2, 3, 4, 5, 6, 7, 8, 9, 0, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'am', 'pm', 'AM', 'PM', 'hours', 'ago', 'minute', 'day', 'year', 's', 'week', 'Month', 'second'
            );
            $bangDATE = array(
                '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯', '০', 'জানুয়ারী', 'ফেব্রুয়ারী', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর', 'জানুয়ারী', 'ফেব্রুয়ারী', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর', 'শনিবার', 'রবিবার', 'সোমবার', 'মঙ্গলবার', 'বুধবার', 'বৃহস্পতিবার', 'শুক্রবার', 'শনিবার', 'রবিবার', 'সোমবার', 'মঙ্গলবার', 'বুধবার', 'বৃহস্পতিবার', 'শুক্রবার', 'এম', 'পিএম', 'এম', 'পিএম', 'ঘন্টা', 'আগে', 'মিনিট', 'দিন', 'বছর', '', 'সপ্তাহ', 'মাস', 'সেকেন্ড'
            );
            if ($lan == 'bn') {
                $date = str_replace($engDATE, $bangDATE, $date);
            } elseif ($lan == 'en') {
                $date = str_replace($bangDATE, $engDATE, $date);
            }
        }
        return $date;
    }


    public static function recaptchaV3($secret_key, $recaptcha_response)
    {
        if (env('APP_ENV') == "production") {
            $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
            $recaptcha_secret = $secret_key;

            // Make and decode POST request:
            $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
            $recaptcha = json_decode($recaptcha);

            $status = true;
            if ($recaptcha->success == false) {
                $status = false;
                if (!empty($recaptcha->score) && $recaptcha->score < 0.5) {
                    $status = false;
                }
            }
            return $status;
        }
        return true;
    }

    public static function greetingMessage()
    {
        $message = '';

        date_default_timezone_set('Asia/Dhaka');

        // 24-hour format of an hour without leading zeros (0 through 23)
        $current_hour = date('G');

        if ($current_hour >= 5 && $current_hour <= 11) {
            $message = "Good Morning , " . auth()->user()->user_name;
        } else if ($current_hour >= 12 && $current_hour <= 15) {
            $message = "Good Noon , " . auth()->user()->user_name;
        } else if ($current_hour >= 16 || $current_hour <= 17) {
            $message = "Good Afternoon , " . auth()->user()->user_name;
        } else if ($current_hour >= 18 || $current_hour <= 19) {
            $message = "Good Evening , " . auth()->user()->user_name;
        } else if ($current_hour >= 20 || $current_hour <= 4) {
            $message = "Good Night , " . auth()->user()->user_name;
        }

        return $message;
    }

    public static function protocol()
    {
        if (env('APP_ENV') == "production") {
            $protocol = 'https://';
        } else {
            $protocol = 'http://';
        }
        return $protocol;
    }

    public static function serverURL()
    {
        if (env('APP_ENV') == "production") {
            $server_url = $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']);
        } else {
            $server_url = $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF'])  . '/';
        }
        return $server_url;
    }

    public static function globalImagePath($file_path, $image_name)
    {
        if (env('APP_ENV') == 'local') {
            $full_image_url = asset("images/{$file_path}/{$image_name}");
        } else {
            $full_image_url = asset("images/{$file_path}/{$image_name}");
        }
        return $full_image_url;
    }

    public function globalImagePathForPdf($file_path, $image_name)
    {
        if (env('APP_ENV') == 'local') {
            $full_image_url = public_path("images/{$file_path}/{$image_name}");
        } else {
            $full_image_url = asset("images/{$file_path}/{$image_name}");
        }
        return $full_image_url;
    }

    public static function  serverPath()
    {
        if (env('APP_ENV') == "production") {
            $server_url = '#';
        } else {
            $server_url = '#';
        }
        return $server_url;
    }

    public static function secretKeyForApiAuthentication()
    {
        $secret_key = 'c17fedafcf8b0642f178718ba0e9c97544bdccf0';

        return $secret_key;
    }

    public static function websiteDetails()
    {
        $website_info = Setting::first();
        $result = isset($website_info) ? $website_info : '';
        return $result;
    }

    public static function make_slug($string)
    {
        // Replace spaces, commas, and special characters with a hyphen
        $slug = preg_replace('/[\s,]+/u', '-', trim($string));

        return $slug;
    }

    public static function tableIndex($data)
    {
        $index = ($data->currentPage() - 1) * $data->perPage() + 1;
        return $index;
    }

    public static function defaultSystemGeneratedCollectorId()
    {
        $max_collector_id = User::where('user_type', UserTypeEnum::COLLECTOR)->max('collector_id');
        if (isset($max_collector_id)) {
            $collector_id =  $max_collector_id + 1;
        } else {
            $collector_id = 1000;
        }
        return $collector_id;
    }
}
