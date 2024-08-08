<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Inertia\Inertia;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;

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
     * Show the application's login form.
     *
     * @return \Inertia\Response
     */
    public function showLoginForm()
    {
       
        
        return Inertia::render('Auth/Login');
    }


   /**
     * Log the user out of the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        // Get the user ID before logging out
        $userId = Auth::id();

        // Perform any updates or actions with the user data
        if ($userId) {
            // Example: Update user status or log logout activity
            \Log::info('User is logging out', ['user_id' => $userId]);

            //  Update user 
            $user = Auth::user();
            $user->pushtoken = null; 
            $user->save();
        }

        // Call the parent logout method
        Auth::logout();

        // Redirect or perform additional actions after logout
        return $this->loggedOut($request) ?: redirect('/');
    }

    /**
     * The user has been logged out of the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|null
     */
    protected function loggedOut(Request $request)
    {
        // Additional actions after logout
        // Redirect to a specific route or URL
        return redirect('/');
    }
}
