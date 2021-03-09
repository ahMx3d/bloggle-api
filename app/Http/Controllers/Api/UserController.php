<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Auth\PostResource;
use App\Http\Resources\Auth\UserResource;
use App\Http\Requests\Frontend\UserRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Frontend\PasswordRequest;
use App\Services\UserService;

class UserController extends Controller
{
    private $userService;
    private $paginationCount;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->paginationCount = config('constants.apiPaginationCount', 5);
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Auth::user()->posts()->typePost()->paginate($this->paginationCount);
        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return ($user->id != auth()->id())
            ? response()->json([
                'errors'  => true,
                'message' => 'Unauthorized',
                'status'  => Response::HTTP_UNAUTHORIZED,
            ], Response::HTTP_UNAUTHORIZED)
            : response()->json([
                'errors' => false,
                'data'   => new UserResource($user),
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\UserRequest  $request
     * @param  App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        if ($user->id != auth()->id()) return response()->json([
            'errors'  => true,
            'message' => 'Unauthorized',
            'status'  => Response::HTTP_UNAUTHORIZED,
        ], Response::HTTP_UNAUTHORIZED);

        $fileName = ($image = $request->file('user_image'))
            ? $this->userService->handle_profile_image_in_server(
                $image,
                $user
            )
            : null;
        $data = [
            'name'          => $request->name,
            'email'         => $request->email,
            'mobile'        => $request->mobile,
            'bio'           => $request->bio,
            'receive_email' => $request->receive_email,
            'user_image'    => $fileName,
        ];
        $user->update($data);

        return response()->json([
            'errors' => false,
            'data'   => 'Information updated successfully!',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\PasswordRequest  $request
     * @param  App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function passwordUpdate(PasswordRequest $request, User $user)
    {
        if ($user->id != auth()->id()) return response()->json([
            'errors'  => true,
            'message' => 'Unauthorized',
            'status'  => Response::HTTP_UNAUTHORIZED,
        ], Response::HTTP_UNAUTHORIZED);

        $user->update([
            'password' => bcrypt($request->password)
        ]);

        return response()->json([
            'errors' => false,
            'data'   => 'Password updated successfully!',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
