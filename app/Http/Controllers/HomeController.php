<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Header;
use App\Models\Banner;
use App\Models\MostSearchedCar;
use App\Models\LatestCar;
use App\Models\Footer;

class HomeController extends Controller
{

    /**
     * Show the application homepage.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        try {
            // Safely get data from database
            $header = Header::where('active', true)->first();
            $banners = Banner::where('active', true)->orderBy('order')->get();
            $mostSearchedCars = MostSearchedCar::where('active', true)->orderBy('search_count', 'desc')->limit(6)->get();
            $latestCars = LatestCar::where('active', true)->latest()->limit(6)->get();
            $footer = Footer::first();

            return view('home', compact('header', 'banners', 'mostSearchedCars', 'latestCars', 'footer'));
        } catch (\Exception $e) {
            // If tables don't exist, pass empty data
            return view('home', [
                'header' => null,
                'banners' => collect(),
                'mostSearchedCars' => collect(),
                'latestCars' => collect(),
                'footer' => null
            ]);
        }
    }
}