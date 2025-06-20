<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MsCourier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourierUpdateProfileController extends Controller
{
    public function show()
    {
        $couriers = Auth::guard('courier')->user();
        return view('courier-myprofile.index', compact('couriers'));
    }

    public function update(Request $request){
        $couriers = Auth::guard('courier')->user();

        $validateData = $request->validate([
            'courier_photo' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'courier_first_name' => 'nullable|max:199',
            'courier_last_name' => 'nullable|max:199',
            'courier_phone_number' => 'nullable|max:199',
            'courier_address' => 'nullable|max:255',
            'courier_password' => 'nullable|min:8|max:20|confirmed',
        ]);

        if (empty($request->courier_first_name)) {
            return back()->withErrors(['courier_first_name' => 'First name cannot be empty.'])->withInput();
        }

        if (empty($request->courier_last_name)) {
            return back()->withErrors(['courier_last_name' => 'Last name cannot be empty.'])->withInput();
        }

        if (empty($request->courier_phone_number)) {
            return back()->withErrors(['courier_phone_number' => 'Phone number cannot be empty.'])->withInput();
        }

        if (empty($request->courier_address)) {
            return back()->withErrors(['courier_address' => 'Address cannot be empty.'])->withInput();
        }

        if ($request->hasFile('courier_photo')) {
            if ($couriers->courier_photo) {
                Storage::delete('public/courier_photos/' . $couriers->courier_photo);
            }
            $file = $request->file('courier_photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/courier_photos', $fileName);
            $couriers->courier_photo = $fileName;
        }

        $couriers->courier_first_name = $validateData['courier_first_name'];
        $couriers->courier_last_name = $validateData['courier_last_name'];
        $couriers->courier_phone_number = $validateData['courier_phone_number'];
        $couriers->courier_address = $validateData['courier_address'];


        if ($request->courier_password){
            $couriers->courier_password = bcrypt($request->courier_password);
        }

        $couriers->save();

        $request->session()->flash('success', 'Your profile has been successfully updated!');

        return redirect('/courier/myprofile');
    }
}
