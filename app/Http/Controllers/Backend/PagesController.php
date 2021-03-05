<?php

namespace App\Http\Controllers\Backend;
use App\Models\Page;
use App\Models\Post;
use App\Models\Category;
use App\Events\PostUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Stevebauman\Purify\Facades\Purify;
use App\Http\Requests\Frontend\PageRequest;
use App\Http\Requests\Frontend\PostRequest;

class PagesController extends Controller
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
            'manage_pages,show_pages'
        )) return redirect_to('admin.index');

        $keyword     = (request()->filled('keyword'))? request()->keyword: null;
        $category_id = (request()->filled('category_id'))? request()->category_id: null;
        $status      = (request()->filled('status'))? request()->status: null;
        $sort_by     = (request()->filled('sort_by'))? request()->sort_by: 'id';
        $order_by    = (request()->filled('order_by'))? request()->order_by: 'desc';
        $limit_by    = (request()->filled('limit_by'))? request()->limit_by: $this->pagination_count;

        $pages = Page::typePage();
        $pages = ($keyword)? $pages->search($keyword): $pages;
        $pages = ($status != null)? $pages->whereStatus($status): $pages;
        $pages = ($category_id)? $pages->whereCategoryId($category_id): $pages;
        $pages = $pages->orderBy(
            $sort_by,
            $order_by
        )->paginate($limit_by);

        $categories = Category::orderDesc()->pluck('name','id')->toArray();
        return view('backend.pages.index', compact('pages', 'categories'));
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
            'create_pages'
        )) return redirect_to('admin.index');

        $categories = Category::orderDesc()->pluck('name','id')->toArray();
        return view('backend.pages.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\PageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PageRequest $request)
    {
        if (!auth()->user()->ability(
            'admin',
            'create_pages'
        )) return redirect_to('admin.index');

        try {
            DB::beginTransaction();
            $data = [
                'title'        => $request->title,
                'description'  => Purify::clean($request->description),
                'status'       => $request->status,
                'post_type'    => 'page',
                'comment_able' => 0,
                'category_id'  => $request->category_id,
            ];
            auth()->user()->posts()->create($data);
            DB::commit();

            return redirect_with_msg(
                'admin.pages.index',
                'Page created successfully',
                'success'
            );
        } catch (\Exception $th) {
            DB::rollback();
            return redirect_with_msg(
                'admin.pages.index',
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
            'display_pages'
        )) return redirect_to('admin.index');

        $page = Page::with(['media'])->whereId($id)->typePage()->first();
        return view('backend.pages.show', compact('page'));
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
            'update_pages'
        )) return redirect_to('admin.index');

        $categories = Category::orderDesc()->pluck('name','id')->toArray();
        $page       = Page::with(['media'])->whereId($id)->typePage()->first();
        return view('backend.pages.edit', compact('categories', 'page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\PageRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PageRequest $request, $id)
    {
        if (!auth()->user()->ability(
            'admin',
            'update_pages'
        )) return redirect_to('admin.index');

        try {
            $page = Post::whereId($id)->wherePostType('page')->first();
            if(!$page) return redirect_with_msg(
                'admin.pages.edit',
                'Oops, Something went wrong',
                'danger'
            );

            DB::beginTransaction();
            $data = [
                'title'        => $request->title,
                'description'  => Purify::clean($request->description),
                'status'       => $request->status,
                'category_id'  => $request->category_id,
            ];
            $page->update($data);
            event(new PostUpdated($page));
            DB::commit();

            return redirect_with_msg(
                'admin.pages.index',
                'Page updated successfully',
                'success'
            );
        } catch (\Exception $e) {
            DB::rollback();
            return redirect_with_msg(
                'admin.pages.index',
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
            'delete_pages'
        )) return redirect_to('admin.index');

        try {
            $page = Page::whereId($id)->typePage()->first();
            if(!$page) return redirect_with_msg(
                'admin.pages.index',
                'Oops, Something went wrong.',
                'danger'
            );

            DB::beginTransaction();
            Post::destroy($page->id);
            DB::commit();

            return redirect_with_msg(
                'admin.pages.index',
                'Page deleted successfully',
                'success'
            );
        } catch (\Exception $e) {
            DB::rollback();
            return redirect_with_msg(
                'admin.pages.index',
                'Oops, Something went wrong.',
                'danger'
            );
        }
    }
}
