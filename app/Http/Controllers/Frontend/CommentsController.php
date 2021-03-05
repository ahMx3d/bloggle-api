<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Frontend\CommentRequest;
use App\Interfaces\Frontend\Repositories\IPostRepository;
use App\Interfaces\Frontend\Repositories\ICommentRepository;

class CommentsController extends Controller
{
    private $post_repo;  // Posts repository.
    private $comment_repo;  // Comments repository.
    private $pagination_count;  // pagination count.

    /**
     * Construct posts repository interface.
     * Construct comments repository interface.
     * Construct controller middleware.
     * Construct pagination count.
     *
     * @param IPostRepository $post_repo
     * @param ICommentRepository $comment_repo
     * @return void
     */
    public function __construct(
        IPostRepository $post_repo,
        ICommentRepository $comment_repo
    ) {
        $this->post_repo        = $post_repo;
        $this->comment_repo     = $comment_repo;
        $this->pagination_count = config(
            'constants.AUTH_PAGINATION_COUNT'
        );
        $this->middleware([
            'auth',
            'verified'
        ])->except([
            'store'
        ]);
    }

    /**
     * Show all comments in frontend comments index view.
     *
     * @return Illuminate\Support\Facades\View (frontend.user.comments.index, compact('posts'))
     */
    public function index(Request $request)
    {
        try {
            $comments = ($request->filled('post'))? $this->comment_repo->paginate_by_post_id(
                $this->pagination_count,
                $request->post
            ): $this->comment_repo->paginate_all(
                $this->pagination_count
            );

            return view('frontend.user.comments.index', compact('comments'));
        } catch (\Exception $e) {
            return redirect_to('frontend.index');
        }
    }

    /**
     * Store comment using the comment's post slug.
     *
     * @param Illuminate\Http\CommentRequest $request
     * @param string $post_slug
     * @return Illuminate\Http\Response
     */
    public function store(CommentRequest $request, $post_slug)
    {
        try {
            $post = $this->post_repo->post_get_by_slug($post_slug);
            if (!$post) return redirect_with_msg(
                'frontend.posts.show',
                'Something went wrong',
                'danger',
                $post_slug
            );

            DB::beginTransaction();
            $this->comment_repo->store_on_post($request, $post);
            DB::commit();

            return redirect_with_msg(
                'frontend.posts.show',
                'Comment added successfully',
                'success',
                $post_slug
            );
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect_with_msg(
                'frontend.index',
                'Oops, Something went wrong',
                'danger'
            );
        }
    }

    /**
     * Show form for Editing a post of an authenticated user.
     *
     * @return Illuminate\Support\Facades\View ('frontend.user.comments.edit', compact('comment'))
     */
    public function edit($id)
    {
        try {
            $comment =  $this->comment_repo->get_by_id($id);

            return ((!$comment)? redirect_with_msg(
                'user.comments.index',
                'Oops, something went wrong',
                'danger'
            ): view('frontend.user.comments.edit', compact('comment')));
        } catch (\Exception $e) {
            return redirect_with_msg(
                'user.comments.index',
                'Oops, something went wrong',
                'danger'
            );
        }
    }

    /**
     * Update comment data from edit comment form view.
     *
     * @return Illuminate\Http\Response redirect()
     */
    public function update(CommentRequest $request, $id)
    {
        try{
            $comment =  $this->comment_repo->get_by_id($id);
            if(!$comment) return redirect_with_msg(
                'user.comments.index',
                'Oops, something went wrong',
                'danger'
            );

            DB::beginTransaction();
            $this->comment_repo->update($request, $comment);
            DB::commit();

            return redirect_with_msg(
                'user.comments.index',
                'Comment Updated Successfully',
                'success'
            );
        } catch(\Exception $e){
            DB::rollback();
            return redirect_with_msg(
                'user.comments.index',
                'Oops, something went wrong',
                'danger'
            );
        }
    }

    /**
     * Delete comment.
     *
     * @return Illuminate\Http\Response redirect()
     */
    public function destroy($id)
    {
        try{
            $comment =  $this->comment_repo->get_by_id($id);
            if(!$comment) return redirect_with_msg(
                'user.comments.index',
                'Oops, something went wrong',
                'danger'
            );

            DB::beginTransaction();
            $this->comment_repo->delete_by_id($comment->id);
            DB::commit();

            return redirect_with_msg(
                'user.comments.index',
                'Comment Deleted Successfully',
                'success'
            );
        } catch(\Exception $e){
            DB::rollback();
            return redirect_with_msg(
                'user.comments.index',
                'Oops, something went wrong',
                'danger'
            );
        }
    }
}
