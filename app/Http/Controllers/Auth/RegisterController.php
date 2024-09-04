<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Rules\GenderMatchesIcNumber;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Database\QueryException; // Add this line

class RegisterController extends Controller
{

    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'ic_number' => ['required', 'string', 'size:12', 'unique:users'],
            'gender' => ['required', 'string', 'in:Male,Female', new GenderMatchesIcNumber],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'type' => ['required', 'string', 'in:patient,doctor'],
            'medical_license_number' => ['required_if:type,doctor', 'string', 'nullable'],
            'date_of_birth' => ['required', 'date'],
            'phone_number' => ['required', 'string', 'regex:/^\+60[0-9]{9,10}$/'],
        ]);
    }

    protected function create(array $data)
    {
        Log::info('Attempting to create user: ' . json_encode($data));
        
        try {
            $user = User::create([
                'name' => $data['name'],
                'ic_number' => (int)$data['ic_number'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'gender' => $data['gender'],
                'date_of_birth' => $data['date_of_birth'],
                'phone_number' => $data['phone_number'], 
                'type' => $data['type'] === 'doctor' ? 2 : 0,
                'medical_license_number' => $data['type'] === 'doctor' ? $data['medical_license_number'] : null,
            ]);
            
            Log::info('User created successfully: ' . $user->id);
            return $user;
        } catch (QueryException $e) {
            Log::error('Failed to create user: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function getRedirectPath($user)
    {
        if ($user->type == 2) {
            return '/doctor/dashboard';
        }
        return '/patient/dashboard';
    }

    protected function registered(Request $request, $user)
    {
        Log::info('User registered with type: ' . $user->type);
        
        if ($user->type == 2) {
            return redirect('/doctor/dashboard');
        }
        return redirect('/patient/dashboard');
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        Log::info('Redirecting user with type: ' . $user->type);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect($this->getRedirectPath($user));
    }
}
