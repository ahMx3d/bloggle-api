<?php

namespace App\Http\Controllers\Backend;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Stevebauman\Purify\Facades\Purify;
use App\Http\Requests\Frontend\CategoryRequest;

class CategoriesController extends Controller
{
    private $pagination_count;  // Global pagination count constant.

    /**
     * Construct posts repository interface.
     * Construct posts pagination count constant.
     *
     * @param IPostRepository $post_repo
     * @return void
     */
    public function __construct() {
        $this->pagination_count = config(
            'constants.ADMIN_PAGINATION_COUNT'
        );

        // Bug Here
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
            'manage_post_categories,show_post_categories'
        )) return redirect_to('admin.index');

        $keyword  = (request()->filled('keyword'))? request()->keyword: null;
        $status   = (request()->filled('status'))? request()->status: null;
        $sort_by  = (request()->filled('sort_by'))? request()->sort_by: 'id';
        $order_by = (request()->filled('order_by'))? request()->order_by: 'desc';
        $limit_by = (request()->filled('limit_by'))? request()->limit_by: $this->pagination_count;

        $categories = Category::withCount('posts');
        $categories = ($keyword)? $categories->search($keyword): $categories;
        $categories = ($status != null)? $categories->whereStatus($status): $categories;
        $categories = $categories->orderBy(
            $sort_by,
            $order_by
        )->paginate($limit_by);

        return view('backend.categories.index', compact('categories'));
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
            'create_post_categories'
        )) return redirect_to('admin.index');

        return view('backend.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\CategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        if (!auth()->user()->ability(
            'admin',
            'create_post_categories'
        )) return redirect_to('admin.index');

        try {
            $data = [
                'name'   => $request->name,
                'status' => $request->status,
            ];
            Category::create($data);

            if($request->status == 1) Cache::forget('sidebar_categories');

            return redirect_with_msg(
                'admin.post_categories.index',
                'Category created successfully',
                'success'
            );
        } catch (\Exception $th) {
            return redirect_with_msg(
                'admin.post_categories.index',
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
        //
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
            'update_post_categories'
        )) return redirect_to('admin.index');

        $category = Category::whereId($id)->first();
        return view('backend.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\CategoryRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        if (!auth()->user()->ability(
            'admin',
            'update_post_categories'
        )) return redirect_to('admin.index');

        try {
            $category = Category::whereId($id)->first();
            if(!$category) return redirect_with_msg(
                'admin.post_categories.edit',
                'Oops, Something went wrong',
                'danger'
            );

            $data = [
                'name'   => $request->name,
                'status' => $request->status,
            ];
            $category->update($data);
            Cache::forget('sidebar_categories');

            return redirect_with_msg(
                'admin.post_categories.index',
                'Category updated successfully',
                'success'
            );
        } catch (\Exception $e) {
            return redirect_with_msg(
                'admin.post_categories.index',
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
            'delete_post_categories'
        )) return redirect_to('admin.index');

        try {
            $category = Category::whereId($id)->first();
            foreach ($category->posts as $post) {
                DB::beginTransaction();
                Post::destroy($post->id);
                DB::commit();
            }
            $category->delete();

            return redirect_with_msg(
                'admin.post_categories.index',
                'Category deleted successfully',
                'success'
            );
        } catch (\Exception $e) {
            DB::rollback();
            return redirect_with_msg(
                'admin.post_categories.index',
                'Oops, Something went wrong.',
                'danger'
            );
        }
    }
}
