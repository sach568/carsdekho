<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CarOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index()
    {
        $carOptions = CarOption::where('active', true)->get();
        return view('customer-form', compact('carOptions'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'email' => 'required|email',
            'address' => 'required|string',
            'car_options' => 'required|array|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Customer::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'address' => $request->address,
            'car_options' => $request->car_options,
        ]);

        return redirect()->route('customer.form')
            ->with('success', 'Thank you! Your form has been submitted successfully.');
    }
}