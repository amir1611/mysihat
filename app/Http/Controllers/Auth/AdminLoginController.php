<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class AdminLoginController extends Controller
{
    public function __construct()
    {
        // Use the 'guest:admin' middleware to ensure only guests can access the login form
        $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm()
    {
        // Return the admin login view
        return view('auth.admin-login');
    }

    public function login(Request $request): RedirectResponse
    {
        // Validate the login credentials
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to log in using the 'admin' guard and check for type '1' (admin)
        if (Auth::guard('admin')->attempt($credentials)) {
            // Check if the authenticated user is an admin
            if (Auth::guard('admin')->user()->type == 1) {
                return redirect()->route('admin.homepage');
            } else {
                Auth::guard('admin')->logout();
            }
        }

        // Redirect back to the login page with an error message if authentication fails
        return redirect()->route('admin.login')->with('error', 'Email or password is incorrect, or you do not have admin access.');
    }

    public function logout(Request $request): RedirectResponse
    {
        // Log out the admin user
        Auth::guard('admin')->logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate the session token
        $request->session()->regenerateToken();

        // Redirect to the admin login page
        return redirect('/admin');
    }
}
