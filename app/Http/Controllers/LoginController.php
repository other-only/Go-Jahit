<?php

namespace App\Http\Controllers;

use App\Http\Requests\Login\LoginRequest;
use App\Http\Requests\Login\RegisterRequest;
use App\Models\User;
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
            $user = auth()->user();
            if ($user->hasRole('pelanggan')) {
                return redirect()->route('client.belanja');
            }
            return redirect()->route('admin.dashboard');
        }
        return back()->withInput();
    }

    public function logout(Request $request)
    {
        auth()->logout();
        return redirect()->route('login');
    }

    public function register(Request $request)
    {
        return view('auth.register');
    }

    public function postRegister(RegisterRequest $request)
    {
        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'alamat' => $data['alamat'],
            'no_hp' => $data['no_hp'],
            'password' => $data['password'],
        ]);
        $user->syncRoles('pelanggan');
        auth()->login($user);
        return redirect()->route('client.belanja');
    }
}
