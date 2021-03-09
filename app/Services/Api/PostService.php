<?php

namespace App\Services\Api;

use Exception;
use App\Models\Tag;
use App\Models\Post;
use App\Events\PostUpdated;
use Illuminate\Support\Facades\Cache;
use Stevebauman\Purify\Facades\Purify;
use App\Exceptions\QueryPendingException;
use App\Exceptions\QueryNotFoundException;
use App\Http\Requests\Frontend\PostRequest;
use App\Models\Category;

class PostService
{
    private $paginationCount;
    public function __construct()
    {
        $this->paginationCount = (int)config('constants.apiPaginationCount', 5);
    }

    /**
     * Update post from an authenticated user.
     *
     * @param App\Http\Requests\Frontend\PostRequest $request
     * @param object $post
     * @return void
     */
    public function updatePost($request, $post): void
    {
        try {
            $data = [
                'title'        => $request->title,
                'description'  => Purify::clean($request->description),
                'status'       => $request->status,
                'comment_able' => $request->comment_able,
                'category_id'  => $request->category_id,
            ];
            $post->update($data);
            event(new PostUpdated($post));
            if (count($request->tags)) {
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
            Cache::forget('global_tags');
        } catch (\Exception $ex) {
            throw new Exception($ex);
        }
    }

    /**
     * Store new post from an authenticated user.
     *
     * @param App\Http\Requests\Frontend\PostRequest $request
     * @return void
     */
    public function storePost($request): void
    {
        try {
            $data = [
                'title'        => $request->title,
                'description'  => Purify::clean($request->description),
                'status'       => $request->status,
                'comment_able' => $request->comment_able,
                'category_id'  => $request->category_id,
            ];
            auth()->user()->posts()->create($data);
        } catch (\Exception $ex) {
            throw new Exception($ex);
        }
    }

    /**
     * Retrieve active post with all relations where its user and category are both active.
     *
     * @param App\Models\Post $post
     * @return App\Models\Post
     */
    public function getPostWithRelations(Post $post): object
    {
        $exceptionThrowable = (!$post->user->status
            or !$post->category->status
            or $post->status != 'Active'
            or $post->post_type != 'post');
        if ($exceptionThrowable) throw new QueryPendingException;

        return $post->load([
            'user',
            'category',
            'media',
            'tags',
            'approved_comments' => function ($query) {
                $query->latest('id');
            },
        ]);
    }

    /**
     * Retrieve active posts where its user and category are both active.
     *
     * @param object $request
     * @return App\Models\Post
     */
    public function paginatePosts($request): object
    {
        $posts = Post::whereHas('category', function ($query) {
                $query->active();
            })
            ->whereHas('user', function ($query) {
                $query->active();
            })
            ->typePost()
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $query->search($request->keyword, null, true)->get();
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $categoryId = Category::whereSlug($request->category)->active()->firstOrFail()->id;
                $query->whereCategoryId($categoryId)->get();
            })
            ->when($request->filled('tag'), function ($query) use ($request) {
                $tagSlug = $request->tag;
                $query->whereHas('tags', function ($query)use($tagSlug){
                    $query->whereSlug($tagSlug);
                });
            })
            ->when($request->filled('author'), function ($query) use ($request) {
                $username = $request->author;
                $query->whereHas('user', function ($query)use($username){
                    $query->whereUsername($username);
                });
            })
            ->when($request->filled('archive'), function ($query) use ($request) {
                $date  = explode('-', $request->archive);
                $month = $date[0];
                $year  = $date[1];
                $query->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->get();
            })
            ->active()
            ->latest('id')
            ->paginate($this->paginationCount);

        if (!$posts->count()) throw new QueryNotFoundException;

        return $posts;
    }
}
