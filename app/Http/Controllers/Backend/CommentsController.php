<?php

namespace App\Http\Controllers\Backend;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Frontend\CommentRequest;

class CommentsController extends Controller
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
            'manage_post_comments,show_post_comments'
        )) return redirect_to('admin.index');

        $keyword  = (request()->filled('keyword'))? request()->keyword: null;
        $post_id  = (request()->filled('post_id'))? request()->post_id: null;
        $status   = (request()->filled('status'))? request()->status: null;
        $sort_by  = (request()->filled('sort_by'))? request()->sort_by: 'id';
        $order_by = (request()->filled('order_by'))? request()->order_by: 'desc';
        $limit_by = (request()->filled('limit_by'))? request()->limit_by: $this->pagination_count;

        $comments = Comment::query();
        $comments = ($keyword)? $comments->search($keyword): $comments;
        $comments = ($post_id)? $comments->wherePostId($post_id): $comments;
        $comments = ($status != null)? $comments->whereStatus($status): $comments;
        $comments = $comments->orderBy(
            $sort_by,
            $order_by
        )->paginate($limit_by);

        $posts = Post::typePost()->orderDesc()->pluck('title','id')->toArray();
        return view('backend.comments.index', compact('comments', 'posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'update_post_comments'
        )) return redirect_to('admin.index');

        $comment = Comment::whereId($id)->first();
        return view('backend.comments.edit', compact('comment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\CommentRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CommentRequest $request, $id)
    {
        if (!auth()->user()->ability(
            'admin',
            'update_post_comments'
        )) return redirect_to('admin.index');

        try {
            $comment = Comment::whereId($id)->first();
            if(!$comment) return redirect_with_msg(
                'admin.post_comments.edit',
                'Oops, Something went wrong',
                'danger'
            );

            DB::beginTransaction();
            $data = [
                'name'       => $request->name,
                'email'      => $request->email,
                'url'        => $request->url,
                'ip_address' => $request->ip_address,
                'status'     => $request->status,
                'comment'    => Purify::clean($request->comment),
            ];
            $comment->update($data);
            DB::commit();

            return redirect_with_msg(
                'admin.post_comments.index',
                'Comment updated successfully',
                'success'
            );
        } catch (\Exception $e) {
            DB::rollback();
            return redirect_with_msg(
                'admin.post_comments.index',
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
            'delete_post_comments'
        )) return redirect_to('admin.index');

        try {
            $comment = Comment::whereId($id)->first();
            if(!$comment) return redirect_with_msg(
                'admin.post_comments.index',
                'Oops, Something went wrong.',
                'danger'
            );

            DB::beginTransaction();
            Comment::destroy($comment->id);
            DB::commit();

            return redirect_with_msg(
                'admin.post_comments.index',
                'Comment deleted successfully',
                'success'
            );
        } catch (\Exception $e) {
            DB::rollback();
            return redirect_with_msg(
                'admin.post_comments.index',
                'Oops, Something went wrong.',
                'danger'
            );
        }
    }
}
