<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::check()){
            return redirect()->route('admin.dashboard');
        }
        //post method
        if (request()->isMethod('post')) {
            // validation
            $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
                'checkbox' => 'required'
            ]);
            $credentials = ([
                'email' =>  $request->email,
                'password' => $request->password,
            ]);
            //attempt
            if (Auth::attempt($credentials)) {
                return redirect()->intended(route('admin.dashboard'));
            }
            // else 
            return back()->withErrors([
                'email' => 'Credential Not Matched'
            ])->onlyInput('email');
        }
        return view('backend.pages.login');
    }
    public function logout()
    {
        Auth::logout();
        return to_route('login');
    }
}
