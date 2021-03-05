<?php

namespace App\Http\Controllers\Frontend;

use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use App\Http\Requests\Frontend\UserRequest;
use App\Interfaces\Frontend\Repositories\IAuthUserRepository;

class UsersController extends Controller
{
    private $auth_user_repo;    // Auth user repository interface.
    private $pagination_count;  // Auth pagination count.
    private $user_service;      // User service object.

    /**
     * Construct Authenticated User Repository.
     * Construct Authenticated Pagination Count.
     * Construct User Service.
     *
     * @param IAuthUserRepository $auth_user_repo
     * @param UserService $user_service
     * @return void
     */
    public function __construct(IAuthUserRepository $auth_user_repo, UserService $user_service)
    {
        $this->auth_user_repo = $auth_user_repo;
        $this->pagination_count = config(
            'constants.AUTH_PAGINATION_COUNT'
        );
        $this->user_service = $user_service;
        $this->middleware([
            'auth',
            'verified'
        ]);
    }

    /**
     * Show all posts DESC related to the currently authenticated user in frontend user profile view.
     *
     * @return Illuminate\Support\Facades\View (frontend.user.profile, compact('posts'))
     */
    public function index()
    {
        try {
            $posts = $this->auth_user_repo->paginate_posts_desc(
                $this->pagination_count
            );
            return view('frontend.user.profile', compact('posts'));
        } catch (\Exception $e) {
            return redirect_to('frontend.index');
        }
    }

    /**
     * Show edit authenticated user information form.
     *
     * @return Illuminate\Support\Facades\View (frontend.user.edit)
     */
    public function edit()
    {
        return view('frontend.user.edit');
    }

    /**
     * Update auth user information.
     *
     * @return Illuminate\Http\Response
     */
    public function update(UserRequest $request)
    {
        try {
            $user = auth()->user();
            $file_name = ($image = $request->file(
                'user_image'
            ))? $this->user_service->handle_profile_image_in_server(
                $image,
                $user
            ): null;

            $this->auth_user_repo->info_update($request, $file_name);
            return redirect_with_msg(
                'frontend.profile',
                'User Info Updated Successfully',
                'success',
                $user->username
            );
        } catch (\Exception $e) {
            return redirect_with_msg(
                'frontend.profile.info.edit',
                'Oops, something went wrong',
                'danger',
                $user->username
            );
        }
    }

    /**
     * Remove auth user image from db and server.
     *
     * @return Illuminate\Http\Response
     */
    public function destroy()
    {
        try {
            $user = auth()->user();

            DB::beginTransaction();
            $user->update([
                'user_image' => null
            ]);
            DB::commit();

            return redirect_with_msg(
                'frontend.profile',
                'Profile image removed successfully',
                'success',
                $user->username
            );
        } catch (\Exception $e) {
            DB::rollback();
            return redirect_with_msg(
                'frontend.profile',
                'Oops, something went wrong',
                'danger',
                $user->username
            );
        }
    }
}
