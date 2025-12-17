<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\MostSearchedCar;
use App\Models\LatestCar;
use App\Models\Footer;
use App\Models\Header;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    //HEADER MANAGEMENT 
    public function header()
    {
        $header = Header::first();
        return view('admin.header', compact('header'));
    }

    public function updateHeader(Request $request)
    {
        $header = Header::firstOrNew([]);

        $request->validate([
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
        ]);

        $data = [
            'phone' => $request->phone,
            'email' => $request->email,
            'menu_items' => json_encode($request->menu_items ?? []),
        ];

        if ($request->hasFile('logo')) {
            if ($header->logo) {
                Storage::delete('public/' . $header->logo);
            }
            $data['logo'] = $request->file('logo')->store('header', 'public');
        }

        $header->fill($data)->save();

        return redirect()->route('admin.header')->with('success', 'Header updated successfully');
    }

    // BANNER MANAGEMENT
    public function banners()
    {
        $banners = Banner::orderBy('order')->get();
        return view('admin.banners', compact('banners'));
    }

    public function storeBanner(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $request->file('image')->store('banners', 'public');

        Banner::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'order' => $request->order ?? 0,
            'active' => $request->active ?? true,
        ]);

        return redirect()->route('admin.banners')->with('success', 'Banner added successfully');
    }

    public function updateBanner(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'order' => $request->order ?? 0,
            'active' => $request->active ?? true,
        ];

        if ($request->hasFile('image')) {
            Storage::delete('public/' . $banner->image);
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('admin.banners')->with('success', 'Banner updated successfully');
    }

    public function destroyBanner($id)
    {
        $banner = Banner::findOrFail($id);
        Storage::delete('public/' . $banner->image);
        $banner->delete();

        return redirect()->route('admin.banners')->with('success', 'Banner deleted successfully');
    }

    //MOST SEARCHED CARS 
    public function mostSearchedCars()
    {
        $cars = MostSearchedCar::all();
        return view('admin.most-searched-cars', compact('cars'));
    }

    public function storeMostSearchedCar(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $request->file('image')->store('most-searched-cars', 'public');

        MostSearchedCar::create([
            'name' => $request->name,
            'model' => $request->model,
            'price' => $request->price,
            'image' => $imagePath,
            'search_count' => $request->search_count ?? 0,
            'active' => $request->active ?? true,
        ]);

        return redirect()->route('admin.most-searched-cars')->with('success', 'Car added successfully');
    }

    public function updateMostSearchedCar(Request $request, $id)
    {
        $car = MostSearchedCar::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $data = [
            'name' => $request->name,
            'model' => $request->model,
            'price' => $request->price,
            'search_count' => $request->search_count ?? 0,
            'active' => $request->active ?? true,
        ];

        if ($request->hasFile('image')) {
            Storage::delete('public/' . $car->image);
            $data['image'] = $request->file('image')->store('most-searched-cars', 'public');
        }

        $car->update($data);

        return redirect()->route('admin.most-searched-cars')->with('success', 'Car updated successfully');
    }

    public function destroyMostSearchedCar($id)
    {
        $car = MostSearchedCar::findOrFail($id);
        Storage::delete('public/' . $car->image);
        $car->delete();

        return redirect()->route('admin.most-searched-cars')->with('success', 'Car deleted successfully');
    }

    //LATEST CARs
    public function latestCars()
    {
        $cars = LatestCar::all();
        return view('admin.latest-cars', compact('cars'));
    }

    public function storeLatestCar(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'model' => 'required',
            'price' => 'required|numeric',
            'image' => 'required|image',
        ]);

        $imagePath = $request->file('image')->store('latest-cars', 'public');

        LatestCar::create([
            'name' => $request->name,
            'model' => $request->model,
            'price' => $request->price,
            'features' => $request->features ?? null,
            'image' => $imagePath,
            'active' => $request->active ?? 1,
        ]);

        return back()->with('success', 'Car saved');
    }


    public function updateLatestCar(Request $request, $id)
    {
        $car = LatestCar::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $data = [
            'name' => $request->name,
            'model' => $request->model,
            'price' => $request->price,
            'features' => $request->features,
            'active' => $request->active ?? true,
        ];

        if ($request->hasFile('image')) {
            Storage::delete('public/' . $car->image);
            $data['image'] = $request->file('image')->store('latest-cars', 'public');
        }

        $car->update($data);

        return redirect()->route('admin.latest-cars')->with('success', 'Car updated successfully');
    }

    public function destroyLatestCar($id)
    {
        $car = LatestCar::findOrFail($id);
        Storage::delete('public/' . $car->image);
        $car->delete();

        return redirect()->route('admin.latest-cars')->with('success', 'Car deleted successfully');
    }

    // CUSTOMER MANAGEMENT
    public function customers()
    {
        $customers = Customer::latest()->paginate(10);
        return view('admin.customers', compact('customers'));
    }

    public function destroyCustomer($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('admin.customers')->with('success', 'Customer deleted successfully');
    }

    // FOOTER MANAGEMENT
    public function footer()
    {
        $footer = Footer::first();
        return view('admin.footer', compact('footer'));
    }

    public function updateFooter(Request $request)
    {
        $footer = Footer::firstOrNew([]);

        $request->validate([
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
        ]);

        $data = [
            'description' => $request->description,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'quick_links' => json_encode([
                ['name' => 'Home', 'url' => '/'],
                ['name' => 'About Us', 'url' => '/about'],
                ['name' => 'Contact', 'url' => '/contact'],
            ]),
            'social_links' => json_encode([
                ['platform' => 'Facebook', 'url' => $request->facebook_url ?? '#'],
                ['platform' => 'Twitter', 'url' => $request->twitter_url ?? '#'],
                ['platform' => 'Instagram', 'url' => $request->instagram_url ?? '#'],
            ]),
        ];

        if ($request->hasFile('logo')) {
            if ($footer->logo) {
                Storage::delete('public/' . $footer->logo);
            }
            $data['logo'] = $request->file('logo')->store('footer', 'public');
        }

        $footer->fill($data)->save();

        return redirect()->route('admin.footer')->with('success', 'Footer updated successfully');
    }
}