<?php

namespace App\Http\Controllers\Admin;

use App\Events\UserLoggedIn;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    // =============================================
    // AUTHENTICATION VIEW METHODS
    // =============================================

    public function showLoginForm(): View
    {
        return view('admin.auth.login');
    }

    // =============================================
    // LOGIN/LOGOUT ACTIONS
    // =============================================

    public function login(Request $request): RedirectResponse
    {
        // Validate login credentials
        $credentials = $request->validate([
            'mobile' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        // Attempt authentication
        if (Auth::attempt(['mobile' => $credentials['mobile'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            // Regenerate session for security
            $request->session()->regenerate();

            // Trigger login event
            UserLoggedIn::dispatch($request->user());

            // Log authentication activity (Persian)
            activity('احراز هویت')
                ->causedBy($request->user())
                ->withProperties(['موبایل' => $credentials['mobile']])
                ->log('کاربر وارد حساب شد');

            // Redirect to intended page or admin dashboard
            return redirect()->intended('admin');
        }

        // Failed authentication response
        return back()->withErrors([
            'mobile' => 'اطلاعات وارد شده اشتباه است!', // Persian error message
        ])->onlyInput('mobile');
    }

    public function logout(Request $request): RedirectResponse
    {
        // Get current authenticated user
        $user = $request->user();

        // Log logout activity if user exists (Persian)
        if ($user) {
            activity('احراز هویت')
                ->causedBy($user)
                ->withProperties(['موبایل' => $user->mobile])
                ->log('کاربر از حساب خارج شد');
        }

        // Perform logout actions
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to login page
        return redirect('/login');
    }
}
