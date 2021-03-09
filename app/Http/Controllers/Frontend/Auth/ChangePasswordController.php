<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\PasswordRequest;
use App\Interfaces\Frontend\Repositories\IAuthUserRepository;

class ChangePasswordController extends Controller
{
    private $auth_user_repo;    // Auth user repository interface.

    /**
     * Construct Authenticated User Repository.
     * Construct Authentication Middleware.
     *
     * @param IAuthUserRepository $auth_user_repo
     * @return void
     */
    public function __construct(IAuthUserRepository $auth_user_repo)
    {
        $this->auth_user_repo = $auth_user_repo;
        $this->middleware([
            'auth',
            'verified'
        ]);
    }
    /**
     * Show edit authenticated user Password form.
     *
     * @return Illuminate\Support\Facades\View (frontend.user.password)
     */
    public function edit()
    {
        return view('frontend.user.password');
    }

    /**
     * Update auth user password.
     *
     * @param App\Http\Requests\Frontend\PasswordRequest $request
     * @return Illuminate\Http\Response
     */
    public function update(PasswordRequest $request)
    {
        try {
            $this->auth_user_repo->password_update($request->password);
            return redirect_with_msg(
                'frontend.profile.password.edit',
                'Password Updated Successfully',
                'success',
                auth()->user()->username
            );
        } catch (\Exception $e) {
            return redirect_with_msg(
                'frontend.profile.password.edit',
                'Oops, something went wrong',
                'danger',
                auth()->user()->username
            );
        }
    }
}
