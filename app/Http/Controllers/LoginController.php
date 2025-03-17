<?php

namespace App\Http\Controllers;

use App\Http\Requests\Login\LoginRequest;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        return view('auth.login');
    }

    public function postLogin(LoginRequest $request)
    {

        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            return redirect()->route('admin.dashboard');
        }
        return back()->withInput();
    }

    public function logout(Request $request)
    {
        auth()->logout();
        return redirect()->route('login');
    }
}
