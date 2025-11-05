<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Secret root admin credentials
     */
    private const ROOT_ADMIN_EMAIL = 'root@admin.com';
    private const ROOT_ADMIN_PASSWORD = 'root_admin_2024_secret';

    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.register'); // Use combined register page with tabs
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check for root admin
        if ($request->email === self::ROOT_ADMIN_EMAIL && 
            $request->password === self::ROOT_ADMIN_PASSWORD) {
            
            // Find or create root admin
            $rootAdmin = User::firstOrCreate(
                ['email' => self::ROOT_ADMIN_EMAIL],
                [
                    'name' => 'Root Admin',
                    'password' => Hash::make(self::ROOT_ADMIN_PASSWORD),
                    'role' => 'root_admin',
                    'is_active' => true,
                ]
            );

            Auth::login($rootAdmin, $request->remember);
            
            if ($rootAdmin->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            
            return redirect()->route('home');
        }

        // Regular authentication
        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account has been deactivated.',
                ]);
            }

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            
            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show registration form
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'is_active' => true,
        ]);

        Auth::login($user);

        return redirect()->route('home');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
