<?php namespace Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Auth\Events\Registered;
use Http\Requests\User\LoginRequest;
use Http\Requests\User\StoreRequest;
use Http\Requests\User\ForgotRequest;
use Http\Requests\User\ResetRequest;
use Repositories\User\UserRepositoryInterface as UserRepository;
use VnSource\Traits\ViewTrait;
use Carbon\Carbon;
use Auth;
use Socialize;
use Password;
use Avatar;
use Agent;

class AuthController extends \App\Http\Controllers\Controller
{
    use AuthenticatesUsers, ViewTrait;

    protected $users;

    public function __construct(UserRepository $users)
    {
        load_lang('auth');
        $this->users = $users;
    }

    public function getLogin()
    {
        return $this->assetView('login', 'auth');
    }

    public function postLogin(LoginRequest $request)
    {

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function getLogout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return back();
    }

    public function getRegister()
    {
        return $this->assetView('register', 'auth');
    }

    public function postRegister(StoreRequest $request)
    {
        event(new Registered($user = $this->users->create($request->only('email', 'phone', 'display_name', 'password'))));

        $this->guard()->login($user);

        return $this->sendLoginResponse($request);
    }

    public function getForgotPassword()
    {
        return $this->assetView('forgot', 'auth');
    }

    public function postForgotPassword(ForgotRequest $request)
    {
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );
        return $response == Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($request, $response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }

    public function getResetPassword(Request $request, $token = null)
    {
        return $this->assetView('reset', 'auth', ['token' => $token, 'email' => empty($request->email)?$request->old('email'):$request->email]);
    }

    public function postResetPassword(ResetRequest $request)
    {
        $response = $this->broker()->reset(
            $request->only(
                'email', 'password', 'password_confirmation', 'token'
            ),
            function ($user, $password) use ($request) {

                $thisLogin = $this->thisLogin($request);
                $user->password = bcrypt($password);
                $user->remember_token = str_random(60);
                $user->api_token = str_random(60);
                $user->last_login = empty($user->this_login)?$thisLogin:$user->this_login;
                $user->this_login = $thisLogin;
                $user->save();

                $this->guard()->login($user);
            }
        );

        return $response == Password::PASSWORD_RESET
            ? $this->sendResetResponse($request, $response)
            : $this->sendResetFailedResponse($request, $response);
    }

    public function getSocialiteAuthorize($provider)
    {
        return Socialize::with($provider)->redirect();
    }

    public function getSocialiteLogin(Request $request, $provider)
    {
        $social = Socialize::with($provider)->user();
        $user = $this->users->findEmail($social->getEmail());
        if (empty($user)) {
            $password = str_random(6);
            $user = $this->users->create([
                'email' => $social->getEmail(),
                'display_name' => $social->getName(),
                'password' => $password
            ]);

            Avatar::sync($user->id, $social->getAvatar());
        }

        if (empty($user->social[$provider])) {
            $user->social = array_add($user->social, $provider, $social->getId());
        }
        $thisLogin = $this->thisLogin($request, $provider);
        $user->last_login = empty($user->this_login)?$thisLogin:$user->this_login;
        $user->this_login = $thisLogin;
        $user->save();

        $this->guard()->login($user);

        return $this->assetView('social', 'auth', ['success'=>true]);
    }

    protected function broker() {
        return Password::broker();
    }

    protected function thisLogin(Request $request, $provider = 'local') {
        $agent = Agent::get();
        return [
            'provider' => $provider,
            'time' => Carbon::now()->toDateTimeString(),
            'ip' => $request->ip(),
            'os' => [
                'name' => $agent->platform(),
                'version' => $agent->version($agent->platform())
            ],
            'browser' => [
                'name' => $agent->browser(),
                'version' => $agent->version($agent->browser())
            ],
            'device' => $agent->device()
        ];
    }

    protected function credentials(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');
        if (is_numeric($credentials['email'])) {
            $credentials['phone'] = $credentials['email'];
            unset($credentials['email']);
        }
        $credentials['status'] = true;
        return $credentials;
    }

    protected function hasTooManyLoginAttempts(Request $request)
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request), config('site.max_auth_attempts'), config('site.auth_attempts_expire')
        );
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        $user = $this->guard()->user();

        $thisLogin = $this->thisLogin($request);
        $user->last_login = empty($user->this_login)?$thisLogin:$user->this_login;
        $user->this_login = $thisLogin;
        $user->save();

        if ($request->expectsJson()) {
            return response()->json($user);
        }

        return back();
    }

    protected function sendResetLinkResponse(Request $request, $response)
    {
        $request->flashOnly(['email']);

        if ($request->expectsJson()) {
            return response()->json(['status'=> __($response)]);
        }

        return back()->with('status', __($response));
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        if ($request->expectsJson()) {
            return response()->json(['email' => __($response)], 422);
        }

        return back()->withErrors(
            ['email' => __($response)]
        );
    }

    protected function sendResetResponse(Request $request, $response)
    {
        if ($request->expectsJson()) {
            return response()->json(['status'=> __($response)]);
        }

        return redirect()->route('home')
            ->with('status', __($response));
    }

    protected function sendResetFailedResponse(Request $request, $response)
    {
        if ($request->expectsJson()) {
            return response()->json(['email' => __($response)], 422);
        }
        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => __($response)]);
    }

}
