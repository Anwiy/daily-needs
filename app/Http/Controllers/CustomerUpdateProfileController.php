<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\MsCustomer;

class CustomerUpdateProfileController extends Controller
{
    public function show()
    {
        $customers = Auth::guard('customer')->user();
        return view('myprofile.index', compact('customers'));
    }

    public function update(Request $request){
        $customers = Auth::guard('customer')->user();

        $validateData = $request->validate([
            'customer_photo' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'customer_first_name' => 'nullable|max:199',
            'customer_last_name' => 'nullable|max:199',
            'customer_phone_number' => 'nullable|max:199',
            'customer_gender' => 'nullable|in:Male,Female,Prefer not to say',
            'customer_password' => 'nullable|min:8|max:20|confirmed',
        ]);

        if (empty($request->customer_first_name)) {
            return back()->withErrors(['customer_first_name' => 'First name cannot be empty.'])->withInput();
        }

        if (empty($request->customer_last_name)) {
            return back()->withErrors(['customer_last_name' => 'Last name cannot be empty.'])->withInput();
        }

        if (empty($request->customer_phone_number)) {
            return back()->withErrors(['customer_phone_number' => 'Phone number cannot be empty.'])->withInput();
        }

        if (empty($request->customer_gender)) {
            return back()->withErrors(['customer_gender' => 'gender cannot be empty.'])->withInput();
        }

        if ($request->hasFile('customer_photo')) {
            if ($customers->customer_photo) {
                Storage::delete('public/customer_photos/' . $customers->customer_photo);
            }
            $file = $request->file('customer_photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/customer_photos', $fileName);
            $customers->customer_photo = $fileName;
        }

        $customers->customer_first_name = $validateData['customer_first_name'];
        $customers->customer_last_name = $validateData['customer_last_name'];
        $customers->customer_phone_number = $validateData['customer_phone_number'];
        $customers->customer_gender = $validateData['customer_gender'];

        if ($request->customer_password){
            $customers->customer_password = bcrypt($request->customer_password);
        }

        $customers->save();

        $request->session()->flash('success', 'Your profile has been successfully updated!');

        return redirect('/dashboard/myprofile');
    }

}
