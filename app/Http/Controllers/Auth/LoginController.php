<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Override the login logic to check both default and otithee_administrator databases.
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->login;
        $password = $request->password;

        // Try default connection first (local DB)
        $user = User::where('email', $login)
            ->orWhere('phone', $login)
            ->first();
        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user, $request->filled('remember'));
            return $this->sendLoginResponse($request);
        }

        // Try otithee_administrator connection
        $adminUser = DB::connection('otithee_administrator')
            ->table('users')
            ->where('email', $login)
            ->orWhere('phone', $login)
            ->first();
        if ($adminUser && isset($adminUser->password) && Hash::check($password, $adminUser->password)) {
            $name = isset($adminUser->name) ? $adminUser->name : (isset($adminUser->first_name) ? $adminUser->first_name . ' ' . ($adminUser->last_name ?? '') : ($adminUser->email ?? 'Unknown'));
            $email = isset($adminUser->email) ? $adminUser->email : null;
            $phone = isset($adminUser->phone) ? $adminUser->phone : null;

            if (!$email) {
                return redirect()->back()->withErrors([
                    'login' => 'User record in administrator DB is missing required fields.'
                ]);
            }

            // Only create if not exists
            $localUser = User::where('email', $email)->orWhere('phone', $phone)->first();
            if (!$localUser) {
                $localUser = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => $adminUser->password, // already hashed
                    'phone' => $phone,
                ]);
            } else if ($localUser->password !== $adminUser->password) {
                $localUser->password = $adminUser->password;
                $localUser->save();
            }
            Auth::login($localUser, $request->filled('remember'));
            return $this->sendLoginResponse($request);
        }

        // If neither found
        return $this->sendFailedLoginResponse($request);
    }
}
