<?php

namespace App\Http\Controllers\Backend;

use App\Models\Tag;
use App\Models\Post;
use App\Models\Category;
use App\Events\PostUpdated;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Stevebauman\Purify\Facades\Purify;
use App\Http\Requests\Frontend\PostRequest;

class PostsController extends Controller
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
            'manage_posts,show_posts'
        )) return redirect_to('admin.index');

        $keyword     = (request()->filled('keyword'))? request()->keyword: null;
        $category_id = (request()->filled('category_id'))? request()->category_id: null;
        $tag_id      = (request()->filled('tag_id'))? request()->tag_id: null;
        $status      = (request()->filled('status'))? request()->status: null;
        $sort_by     = (request()->filled('sort_by'))? request()->sort_by: 'id';
        $order_by    = (request()->filled('order_by'))? request()->order_by: 'desc';
        $limit_by    = (request()->filled('limit_by'))? request()->limit_by: $this->pagination_count;

        $posts = Post::with([
            'user',
            'category',
            'comments'
        ])->typePost();
        $posts = ($keyword)? $posts->search($keyword): $posts;
        $posts = ($status != null)? $posts->whereStatus($status): $posts;
        $posts = ($category_id)? $posts->whereCategoryId($category_id): $posts;
        $posts = ($tag_id)? $posts->whereHas('tags', function ($query)use($tag_id){
            $query->whereId($tag_id);
        }): $posts;
        $posts = $posts->orderBy(
            $sort_by,
            $order_by
        )->paginate($limit_by);

        $categories = Category::orderDesc()->pluck('name','id')->toArray();
        return view('backend.posts.index', compact('posts', 'categories'));
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
            'create_posts'
        )) return redirect_to('admin.index');

        $tags = Tag::pluck('name', 'id')->toArray();
        $categories = Category::orderDesc()->pluck('name','id')->toArray();
        return view('backend.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\PostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        if (!auth()->user()->ability(
            'admin',
            'create_posts'
        )) return redirect_to('admin.index');

        try {
            DB::beginTransaction();
            $data = [
                'title'        => $request->title,
                'description'  => Purify::clean($request->description),
                'status'       => $request->status,
                'comment_able' => $request->comment_able,
                'category_id'  => $request->category_id,
            ];
            auth()->user()->posts()->create($data);
            DB::commit();

            return redirect_with_msg(
                'admin.posts.index',
                'Post created successfully',
                'success'
            );
        } catch (\Exception $th) {
            DB::rollback();
            return redirect_with_msg(
                'admin.posts.index',
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
            'display_posts'
        )) return redirect_to('admin.index');

        $post = Post::with([
            'media',
            'category',
            'user',
        ])->whereId($id)->typePost()->first();
        $comments = $post->comments()->paginate($this->pagination_count);
        return view('backend.posts.show', compact('post', 'comments'));
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
            'update_posts'
        )) return redirect_to('admin.index');

        $tags = Tag::pluck('name', 'id')->toArray();
        $categories = Category::orderDesc()->pluck('name','id')->toArray();
        $post       = Post::with(['media'])->whereId($id)->typePost()->first();
        return view('backend.posts.edit', compact('categories', 'post', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\PostRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, $id)
    {
        if (!auth()->user()->ability(
            'admin',
            'update_posts'
        )) return redirect_to('admin.index');

        try {
            $post = Post::whereId($id)->typePost()->first();
            if(!$post) return redirect_with_msg(
                'admin.posts.edit',
                'Oops, Something went wrong',
                'danger'
            );

            DB::beginTransaction();
            $data = [
                'title'        => $request->title,
                'description'  => Purify::clean($request->description),
                'status'       => $request->status,
                'comment_able' => $request->comment_able,
                'category_id'  => $request->category_id,
            ];
            $post->update($data);
            event(new PostUpdated($post));
            if(count($request->tags)){
                $tags = [];
                foreach ($request->tags as $tag) {
                    $tag = Tag::firstOrCreate(
                        ['id'   => $tag],
                        ['name' => $tag],
                    );
                    $tags[] = $tag->id;
                }
                $post->tags()->sync($tags);
            }
            DB::commit();
            Cache::forget('global_tags');

            return redirect_with_msg(
                'admin.posts.index',
                'Post updated successfully',
                'success'
            );
        } catch (\Exception $e) {
            DB::rollback();
            return redirect_with_msg(
                'admin.posts.index',
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
            'delete_posts'
        )) return redirect_to('admin.index');

        try {
            $post = Post::whereId($id)->typePost()->first();
            if(!$post) return redirect_with_msg(
                'admin.posts.index',
                'Oops, Something went wrong.',
                'danger'
            );

            DB::beginTransaction();
            Post::destroy($post->id);
            DB::commit();

            return redirect_with_msg(
                'admin.posts.index',
                'Post deleted successfully',
                'success'
            );
        } catch (\Exception $e) {
            DB::rollback();
            return redirect_with_msg(
                'admin.posts.index',
                'Oops, Something went wrong.',
                'danger'
            );
        }
    }
}
