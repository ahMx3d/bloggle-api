<?php

namespace App\Repositories\Frontend;

use Stevebauman\Purify\Facades\Purify;
use App\Interfaces\Frontend\Repositories\ICommentRepository;
use App\Models\Comment;

class CommentRepository implements ICommentRepository
{
    private $comment_model;    // Repository model.
    /**
     * Construct comments model
     *
     * @return void
     */
    public function __construct()
    {
        $this->comment_model = Comment::class;
    }

    /**
     * Create new comment
     *
     * @param object $request
     * @param int $post_id
     * @param int $user_id
     * @return object
     */
    public function comment_store($request, $post_id, $user_id)
    {
        $data = [
            'name'       => $request->name,
            'email'      => $request->email,
            'url'        => $request->url,
            'ip_address' => $request->ip(),
            'comment'    => Purify::clean($request->comment),
            'post_id'    => $post_id,
            'user_id'    => $user_id
        ];
        return $this->comment_model::wherePostId($post_id)->create($data);
    }

    /**
     * Create new comment
     *
     * @param object $request
     * @param int $post_id
     * @param int $user_id
     * @return object
     */
    public function store_on_post($request, $post)
    {
        try {
            $user_id = auth()->check()? auth()->id(): null;
            $data = [
                'name'       => $request->name,
                'status'     => 1,
                'email'      => $request->email,
                'url'        => $request->url,
                'ip_address' => $request->ip(),
                'comment'    => Purify::clean($request->comment),
                'post_id'    => $post->id,
                'user_id'    => $user_id
            ];
            return $post->comments()->create($data);
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    /**
     * Limit Descending ordered active active comments.
     *
     * @param int $limitation_count
     * @return object
     */
    public function all_comments_limit($limitation_count)
    {
        return $this->comment_model::active()->orderDesc()->limit(
            $limitation_count
        )->get();
    }

    /**
     * Retrieve comment by id where its user is the current authenticated user.
     *
     * @param int $id
     * @return object
     */
    public function get_by_id($id)
    {
        return $this->comment_model::whereId($id)->whereHas(
            'post',
            function ($query)
            {
                $query->where(
                    'posts.user_id',
                    auth()->id()
                );
            }
        )->first();
    }

    /**
     * Update comment where its user is the current authenticated user.
     *
     * @param int $id
     * @return object
     */
    public function update($request, $comment)
    {
        try {
            $data = [
                'name'    => $request->name,
                'email'   => $request->email,
                'url'     => ($request->url)? $request->url: null,
                'status'  => $request->status,
                'comment' => Purify::clean($request->comment)
            ];
            $comment->update($data);
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    /**
     * Delete comment using its id.
     *
     * @param int $id
     * @return void
     */
    public function delete_by_id($id)
    {
        $this->comment_model::destroy($id);
    }

    /**
     * Paginate all comments related to a post.
     *
     * @param int $pagination_count
     * @param int $post_id
     * @return object
     */
    public function paginate_by_post_id($pagination_count, $post_id)
    {
        return $this->comment_model::wherePostId(
            $post_id
        )->orderDesc()->paginate(
            $pagination_count
        );
    }

    /**
     * Paginate all comments.
     *
     * @param int $pagination_count
     * @return object
     */
    public function paginate_all($pagination_count)
    {
        $posts_id = auth()->user()->posts->pluck('id')->toArray();
        return $this->comment_model::whereIn(
            'post_id',
            $posts_id
        )->orderDesc()->paginate(
            $pagination_count
        );
    }
}
