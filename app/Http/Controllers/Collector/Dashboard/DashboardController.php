<?php

namespace App\Http\Controllers\Collector\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function viewCollectorDashboard()
    {
        return view('frontend.pages.dashboard');
    }
}
