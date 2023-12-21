<?php

namespace App\Http\Controllers\Language;

use App\Http\Controllers\Controller;
use App\UtilityFunction;
use Illuminate\Http\Request;

class SwitchLanguageController extends Controller
{
    public function switchLanguage()
    {
        UtilityFunction::switchLocal();
        return redirect()->back();
    }
}
