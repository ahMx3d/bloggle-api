<?php

namespace App\Http\Controllers\Backend;


use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Stevebauman\Purify\Facades\Purify;
use App\Http\Requests\Backend\TagRequest;

class TagsController extends Controller
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
            'manage_post_tags,show_post_tags'
        )) return redirect_to('admin.index');

        $keyword  = (request()->filled('keyword'))? request()->keyword: null;
        $sort_by  = (request()->filled('sort_by'))? request()->sort_by: 'id';
        $order_by = (request()->filled('order_by'))? request()->order_by: 'desc';
        $limit_by = (request()->filled('limit_by'))? request()->limit_by: $this->pagination_count;

        $tags = Tag::withCount('posts');
        $tags = ($keyword)? $tags->search($keyword): $tags;
        $tags = $tags->orderBy(
            $sort_by,
            $order_by
        )->paginate($limit_by);

        return view('backend.tags.index', compact('tags'));
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
            'create_post_tags'
        )) return redirect_to('admin.index');

        return view('backend.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\TagRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TagRequest $request)
    {
        if (!auth()->user()->ability(
            'admin',
            'create_post_tags'
        )) return redirect_to('admin.index');

        try {
            $data = [
                'name'   => $request->name,
            ];
            Tag::create($data);
            Cache::forget('global_tags');

            return redirect_with_msg(
                'admin.post_tags.index',
                'Tag created successfully',
                'success'
            );
        } catch (\Exception $th) {
            return redirect_with_msg(
                'admin.post_tags.index',
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
            'update_post_tags'
        )) return redirect_to('admin.index');

        $tag = Tag::whereId($id)->first();
        return view('backend.tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\TagRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TagRequest $request, $id)
    {
        if (!auth()->user()->ability(
            'admin',
            'update_post_tags'
        )) return redirect_to('admin.index');

        try {
            $tag = Tag::whereId($id)->first();
            if(!$tag) return redirect_with_msg(
                'admin.post_tags.edit',
                'Oops, Something went wrong',
                'danger'
            );

            $data = [
                'name'   => $request->name,
            ];
            $tag->update($data);
            Cache::forget('global_tags');

            return redirect_with_msg(
                'admin.post_tags.index',
                'Tag updated successfully',
                'success'
            );
        } catch (\Exception $e) {
            return redirect_with_msg(
                'admin.post_tags.index',
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
            'delete_post_tags'
        )) return redirect_to('admin.index');

        try {
            $tag = Tag::whereId($id)->first();
            $tag->delete();

            return redirect_with_msg(
                'admin.post_tags.index',
                'Tag deleted successfully',
                'success'
            );
        } catch (\Exception $e) {
            DB::rollback();
            return redirect_with_msg(
                'admin.post_tags.index',
                'Oops, Something went wrong.',
                'danger'
            );
        }
    }
}
