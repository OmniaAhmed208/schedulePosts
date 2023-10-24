<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request; 
use App\Models\User; 


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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function authenticated(Request $request, $user) 
    {
        $user->status_for_messages = 'online';
        $user->save();
        return redirect()->intended($this->redirectPath());
    }
    public function logout(Request $request) 
    {
        $user = Auth::user();
        if ($user) { 
            $userModel = User::find($user->id);
            $userModel->status_for_messages = 'offline';
            $userModel->save();
        }
        Auth::logout();
        // Additional logout logic... 
        return redirect('/'); 
    }
    
}
