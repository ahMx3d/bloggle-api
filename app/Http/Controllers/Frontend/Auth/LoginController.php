<?php

namespace App\Http\Controllers\Frontend\Auth;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use App\Providers\RouteServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    // protected $redirectTo = '/profile';
    protected $redirectTo = RouteServiceProvider::USER;

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
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if (!$user->status) {
            if ($request->wantsJson()) {
                return response()->json([
                    'errors'  => false,
                    'message' => 'Please Contact Bloggle Admin',
                    'status'  => Response::HTTP_OK,
                ], Response::HTTP_OK);
            }
            return redirect_with_msg(
                'frontend.index',
                'Please Contact Bloggle Admin',
                'warning'
            );
        }

        if ($request->wantsJson()) {
            $token = $user->createToken('access-token')->accessToken;
            return response()->json([
                'errors'  => false,
                'message' => 'Logged in Successfully',
                'token'   => $token,
                'status'  => Response::HTTP_OK,
            ], Response::HTTP_OK);
        }

        return redirect_with_msg(
            'frontend.profile',
            'Logged in Successfully',
            'success',
            $user->username
        );
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect_to('frontend.show_login_form');
    }

    /**
     * Obtain the user information from facebook.
     *
     * @param string $provider
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(string $provider)
    {
        $socialUser = Socialite::driver($provider)->user();
        // dd($provider,$socialUser,$socialUser->token);

        $token  = $socialUser->token;
        $id     = $socialUser->getId();
        $name   = $socialUser->getName();
        $email  = $socialUser->getEmail();
        $avatar = $socialUser->getAvatar();

        $data = [
            'name'              => $name,
            'username'          => trim(Str::lower(Str::snake($name))),
            'email'             => $email,
            'email_verified_at' => Carbon::now(),
            'mobile'            => $id,
            'status'            => 1,
            'receive_email'     => 1,
            'remember_token'    => $token,
            'password'          => Hash::make($email),
        ];

        $user = User::firstOrCreate(
            ['email' => $email],
            $data
        );

        if (!$user->user_image) {
            $fileName = "{$user->username}.jpg";
            $path      = public_path('/assets/users/' . $fileName);
            Image::make($avatar)->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
            $user->update(['user_image' => $fileName]);
        }
        $user->attachRole(Role::whereName('user')->first()->id);
        Auth::login($user, true);

        return redirect_with_msg(
            'frontend.index',
            'Logged in Successfully',
            'success'
        );
    }

    /**
     * Redirect the user to the facebook authentication page.
     *
     * @param string $provider
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
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
        if ($request->wantsJson()) {
            $validator = $this->validator($request->all());
            if ($validator->fails()) return response()->json([
                'message' => 'The given data is invalid.',
                'errors'  => $validator->errors(),
                'status'  => Response::HTTP_UNPROCESSABLE_ENTITY,
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}
