<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MsAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminUpdateProfileController extends Controller
{
    public function show()
    {
        $admins = Auth::guard('admin')->user();
        return view('admin-myprofile.index', compact('admins'));
    }

    public function update(Request $request){
        $admins = Auth::guard('admin')->user();

        $validateData = $request->validate([
            'admin_photo' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'admin_first_name' => 'nullable|max:199',
            'admin_last_name' => 'nullable|max:199',
            'admin_phone_number' => 'nullable|max:199',
            'admin_address' => 'nullable|max:255',
            'admin_password' => 'nullable|min:8|max:20|confirmed',
        ]);

        if (empty($request->admin_first_name)) {
            return back()->withErrors(['admin_first_name' => 'First name cannot be empty.'])->withInput();
        }

        if (empty($request->admin_last_name)) {
            return back()->withErrors(['admin_last_name' => 'Last name cannot be empty.'])->withInput();
        }

        if (empty($request->admin_phone_number)) {
            return back()->withErrors(['admin_phone_number' => 'Phone number cannot be empty.'])->withInput();
        }

        if (empty($request->admin_address)) {
            return back()->withErrors(['admin_address' => 'Address cannot be empty.'])->withInput();
        }

        if ($request->hasFile('admin_photo')) {
            if ($admins->admin_photo) {
                Storage::delete('public/admin_photos/' . $admins->admin_photo);
            }
            $file = $request->file('admin_photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/admin_photos', $fileName);
            $admins->admin_photo = $fileName;
        }

        $admins->admin_first_name = $validateData['admin_first_name'];
        $admins->admin_last_name = $validateData['admin_last_name'];
        $admins->admin_phone_number = $validateData['admin_phone_number'];
        $admins->admin_address = $validateData['admin_address'];


        if ($request->admin_password){
            $admins->admin_password = bcrypt($request->admin_password);
        }

        $admins->save();

        $request->session()->flash('success', 'Your profile has been successfully updated!');

        return redirect('/admin/myprofile');
    }
}
