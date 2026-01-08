<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function showAdminLogin()
    {
        if (Auth::check() && in_array(Auth::user()->role, ['admin', 'owner'])) {
            return redirect()->route('admin.dashboard');
        }

        $settings = [];
        if (Storage::disk('local')->exists('settings.json')) {
            $settings = json_decode(Storage::disk('local')->get('settings.json'), true);
        }
        $storeSettings = $settings['store'] ?? [];

        return view('admin.auth.login', compact('storeSettings'));
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Check if user is admin
            if (in_array(auth()->user()->role, ['admin', 'owner'])) {
                return redirect()->intended(route('admin.dashboard'));
            }
            
            // If not admin, redirect to client home page
            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không đúng.',
        ])->onlyInput('email');
    }

    public function showLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'customer') {
                return redirect()->route('client.dashboard');
            } else {
                return redirect()->route('admin.dashboard');
            }
        }

        $settings = [];
        try {
            if (Storage::disk('local')->exists('settings.json')) {
                $settings = json_decode(Storage::disk('local')->get('settings.json'), true) ?: [];
            }
        } catch (\Throwable $e) {
            $settings = [];
        }
        $storeSettings = $settings['store'] ?? [];

        return view('client.auth.login', compact('storeSettings'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không đúng.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = \App\Models\User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => 'customer',
        ]);

        \App\Models\CustomerProfile::firstOrCreate(['user_id' => $user->id], [
            'club_level' => 'basic',
            'lifetime_value' => 0,
            'reward_points' => 0,
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
