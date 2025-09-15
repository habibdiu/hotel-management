<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Middleware\backendAuthenticationMiddleware;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use PDOException;

class ProfileController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth'
        ];
    }


    public function profile(Request $request)
    {

        $data  = array();
        $data['active_menu'] = 'Profile';
        $data['page_title'] = 'Profile';
        return view('backend.pages.profile', compact('data'));
    }

    public function profile_info_update(Request $request)
    {
        $id = Auth::id();
        $current_user = User::find($id);
        $user_photo = $current_user->photo;
        try {
            if ($request->hasFile('photo')) {
                $image_extension = $request->file('photo')->extension();
                $image_name = 'backend_assets/images/user/' . uniqid() . '.' . $image_extension;
                $request->file('photo')->move('backend_assets/images/user', $image_name);
                if (File::exists($user_photo)) {
                    File::delete($user_photo);
                }
            } else {
                $image_name = $user_photo;
            }
            $current_user->update([
                'name' => $request->name,
                'photo' => $image_name,
                'email' => $request->email,
                'phone' => $request->phone,

            ]);
            return redirect()->back()->with('success', 'profile Updated Successfully');
        } catch (PDOException $e) {
            return redirect()->back()->with('error', 'Please Try Again' . $e->getMessage());
        }
    }

    public function profile_password_update(Request $request)
    {
        $id = Auth::id();
        $current_user = User::find($id);
        $request->validate([
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ], [
            'password' => 'Enter A Password',
            'password.confirmed' => 'Password  Not Matched',
        ]);
        try {
            $current_user->update([
                'password' => bcrypt($request->password)
            ]);
            return redirect()->back()->with('success', 'Password Update Successfully');
        } catch (PDOException $e) {
            return redirect()->back()->with('error', 'Password Update Failed');
        }
    }
}
