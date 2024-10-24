<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->type == 0) {
            return redirect()->route('patient.chatbot');
        }

        else if ($user->type == 1) {
            return redirect()->route('admin.dashboard');
        }

        else if ($user->type == 2) {
            return redirect()->route('doctor.dashboard');
        session(['user_info' => $user]);

        switch ($user->type) {
            case 0:
                return redirect()->route('patient.dashboard');
            case 1:
                return redirect()->route('admin.dashboard');
            case 2:
                return redirect()->route('doctor.dashboard');
            default:
                return redirect('/');
        }
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {


            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }
}
