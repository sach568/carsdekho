<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\MostSearchedCar;
use App\Models\LatestCar;
use App\Models\Customer;
use App\Models\Header;
use App\Models\Footer;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function dashboard()
    {
        $stats = [
            'banners' => Banner::count(),
            'most_searched_cars' => MostSearchedCar::count(),
            'latest_cars' => LatestCar::count(),
            'customers' => Customer::count(),
            'recent_customers' => Customer::latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}