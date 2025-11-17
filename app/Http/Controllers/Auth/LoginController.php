<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAchievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Simple demo login - anyone can login with any email/password
        $demoUser = $this->createOrGetDemoUser($request->email);

        // Log the user in with demo credentials
        Auth::login($demoUser);

        // Update last login
        $demoUser->update(['last_login' => now()]);

        // Check for login achievements
        $this->checkLoginAchievements($demoUser);

        // Record login activity
        $demoUser->recordActivity('login');

        return $this->sendLoginResponse($request);
    }

    /**
     * Create or get a demo user for simple authentication.
     *
     * @param  string  $email
     * @return \App\Models\User
     */
    protected function createOrGetDemoUser($email)
    {
        // Try to find existing user
        $user = User::where('email', $email)->first();

        if (!$user) {
            // Create new demo user
            $name = explode('@', $email)[0]; // Get name from email
            $user = User::create([
                'name' => ucfirst($name),
                'email' => $email,
                'password' => Hash::make('demo123'), // Simple demo password
                'email_verified_at' => now(),
                'avatar_url' => 'https://ui-avatars.com/api/?name=' . urlencode(ucfirst($name)) . '&color=7F9CF5&background=EBF4FF',
                'user_level' => 1,
                'total_xp' => 0,
                'last_login' => now(),
            ]);

            // Create initial stats
            \App\Models\UserStats::create([
                'user_id' => $user->id,
                'total_views' => 0,
                'total_favorites' => 0,
                'total_downloads' => 0,
                'total_collections' => 0,
                'achievement_points' => 0,
            ]);
        }

        return $user;
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        return redirect()->intended($this->redirectTo);
    }

    /**
     * Send the response after a failed login attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        
        // Record logout activity
        if ($user) {
            $user->recordActivity('logout');
        }
        
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Check and award login-related achievements.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    protected function checkLoginAchievements(User $user)
    {
        // Award first login achievement
        UserAchievement::checkAndAward($user, 'first_view');
        
        // Check for consecutive login achievements
        $loginCount = $user->activities()->where('activity_type', 'login')->count();
        
        if ($loginCount >= 7) {
            UserAchievement::checkAndAward($user, 'active_7_days');
        }
        
        if ($loginCount >= 30) {
            UserAchievement::checkAndAward($user, 'community_member');
        }
    }
}
