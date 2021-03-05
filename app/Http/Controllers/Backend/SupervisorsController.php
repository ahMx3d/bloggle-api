<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Models\UserPermission;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Stevebauman\Purify\Facades\Purify;
use App\Http\Requests\Backend\UserRequest;
use App\Http\Requests\Backend\SupervisorRequest;

class SupervisorsController extends Controller
{
    private $pagination_count;  // Global pagination count constant.
    private $user_service;

    /**
     * Construct posts pagination count constant.
     *
     * @return void
     */
    public function __construct(UserService $user_service) {
        $this->pagination_count = config(
            'constants.ADMIN_PAGINATION_COUNT'
        );
        $this->user_service = $user_service;

        // Bug here
        if(auth()->check()){$this->middleware('auth');}
        else{return view('backend.auth.login');}
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->ability(
            'admin',
            'manage_supervisors,show_supervisors'
        )) return redirect_to('admin.index');

        $keyword     = (request()->filled('keyword'))? request()->keyword: null;
        $status      = (request()->filled('status'))? request()->status: null;
        $sort_by     = (request()->filled('sort_by'))? request()->sort_by: 'id';
        $order_by    = (request()->filled('order_by'))? request()->order_by: 'desc';
        $limit_by    = (request()->filled('limit_by'))? request()->limit_by: $this->pagination_count;

        $users = User::whereHas('roles', function ($query){
            $query->whereName('editor');
        });
        $users = ($keyword)? $users->search($keyword): $users;
        $users = ($status != null)? $users->whereStatus($status): $users;
        $users = $users->orderBy(
            $sort_by,
            $order_by
        )->paginate($limit_by);

        return view('backend.supervisors.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->ability(
            'admin',
            'create_supervisors'
        )) return redirect_to('admin.index');

        $permissions = Permission::pluck('display_name', 'id')->toArray();
        return view('backend.supervisors.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\UserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupervisorRequest $request)
    {
        if (!auth()->user()->ability(
            'admin',
            'create_supervisors'
        )) return redirect_to('admin.index');

        try {
            DB::beginTransaction();
            $data = [
                'name'              => $request->name,
                'username'          => $request->username,
                'email'             => $request->email,
                'email_verified_at' => Carbon::now(),
                'mobile'            => $request->mobile,
                'password'          => bcrypt($request->password),
                'status'            => $request->status,
                'bio'               => $request->bio,
                'receive_email'     => $request->receive_email,
            ];
            $user = User::create($data);
            $image = $request->file('user_image');
            if($image){
                $profile_image = $this->user_service->handle_profile_image_in_server(
                    $image,
                    $user,
                    300
                );
                $user->user_image = $profile_image;
            }
            $user->attachRole(Role::whereName('editor')->first()->id);
            $request->whenFilled('permissions', function ($permissions) use($user) {
                $user->permissions()->sync($permissions);
            });
            $user->save();
            DB::commit();

            return redirect_with_msg(
                'admin.supervisors.index',
                'User created successfully',
                'success'
            );
        } catch (\Exception $e) {
            if($image) image_remove(public_path("assets\users\{$image}"));
            DB::rollback();
            return redirect_with_msg(
                'admin.supervisors.index',
                'Oops, Something went wrong',
                'danger'
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!auth()->user()->ability(
            'admin',
            'display_supervisors'
        )) return redirect_to('admin.index');

        $user = User::whereId($id)->first();
        return (!$user)? redirect_with_msg(
            'admin.supervisors.index',
            'Oops, Something went wrong',
            'danger'
        ): view('backend.supervisors.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->ability(
            'admin',
            'update_supervisors'
        )) return redirect_to('admin.index');

        $user = User::whereId($id)->first();
        if (!$user) return redirect_with_msg(
            'admin.supervisors.index',
            'Oops, Something went wrong',
            'danger'
        );

        $permissions      = Permission::pluck('display_name', 'id')->toArray();
        $user_permissions = UserPermission::whereUserId($id)->pluck('permission_id')->toArray();

        return view('backend.supervisors.edit', compact('user', 'permissions', 'user_permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\UserRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SupervisorRequest $request, $id)
    {
        if (!auth()->user()->ability(
            'admin',
            'update_supervisors'
        )) return redirect_to('admin.index');

        try {
            $user = User::whereId($id)->first();
            if(!$user) return redirect_with_msg(
                'admin.supervisors.edit',
                'Oops, Something went wrong',
                'danger'
            );

            $data = [
                'name'              => $request->name,
                'username'          => $request->username,
                'email'             => $request->email,
                'mobile'            => $request->mobile,
                'status'            => $request->status,
                'bio'               => $request->bio,
                'receive_email'     => $request->receive_email,
            ];

            if($password = trim($request->password)) $data['password'] = bcrypt($password);
            $image = $request->file('user_image');
            if($image) $data['user_image'] = image_upload(
                $data['username'],
                $image->getClientOriginalExtension(),
                public_path("assets\users\\"),
                $image->getRealPath(),
                300
            );
            if(!($request->permissions)) $request->merge(['permissions' => []]);

            DB::beginTransaction();
            $request->whenFilled('permissions', function ($permissions) use($user) {
                $user->permissions()->sync($permissions);
            });
            $user->update($data);
            DB::commit();

            return redirect_with_msg(
                'admin.supervisors.index',
                'User updated successfully',
                'success'
            );
        } catch (\Exception $e) {
            if($image) image_remove(public_path("assets\users\{$image}"));
            DB::rollback();
            return redirect_with_msg(
                'admin.supervisors.index',
                'Oops, Something went wrong',
                'danger'
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->ability(
            'admin',
            'delete_supervisors'
        )) return redirect_to('admin.index');

        try {
            $user = User::whereId($id)->first();
            if(!$user) return redirect_with_msg(
                'admin.supervisors.index',
                'Oops, Something went wrong.',
                'danger'
            );

            DB::beginTransaction();
            User::destroy($user->id);
            DB::commit();

            return redirect_with_msg(
                'admin.supervisors.index',
                'Supervisor deleted successfully',
                'success'
            );
        } catch (\Exception $e) {
            DB::rollback();
            return redirect_with_msg(
                'admin.supervisors.index',
                'Oops, Something went wrong.',
                'danger'
            );
        }
    }

    /**
     * Remove the specified resource's image from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy_image(Request $request)
    {
        if (!auth()->user()->ability(
            'admin',
            'delete_supervisors'
        )) return redirect_to('admin.index');

        $user = User::whereId($request->user_id)->first();
        if(!$user) return false;

        if(File::exists($path = public_path("assets\users\{$user->user_image}"))) unlink($path);
        $user->user_image = null;
        $user->save();
        return true;
    }
}

