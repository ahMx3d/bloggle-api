<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Stevebauman\Purify\Facades\Purify;
use App\Http\Resources\Auth\CommentResource;
use App\Http\Requests\Api\CommentRequest;
use App\Http\Resources\Global\CommentResource as GlobalCommentResource;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('store');
    }
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $comments = Comment::query();
        $comments = (($request->filled('post'))
            ? $comments->wherePostId($request->post)
            : $comments->whereIn(
                'post_id',
                auth()->user()->posts->pluck('id')->toArray()
            ));
        $comments = $comments->latest('id')->get();

        return CommentResource::collection($comments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param App\Http\Requests\Api\CommentRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommentRequest $request)
    {
        try {
            $userId = auth()->check()
                ? auth()->id()
                : null;

            $data = [
                'name'       => $request->name,
                'status'     => 1,
                'email'      => $request->email,
                'url'        => $request->url,
                'ip_address' => $request->ip(),
                'comment'    => Purify::clean($request->comment),
                'post_id'    => $request->postId,
                'user_id'    => $userId
            ];

            DB::beginTransaction();
            $comment = Comment::create($data);
            DB::commit();

            return response()->json([
                'errors' => true,
                'data'   => new GlobalCommentResource($comment),
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'errors'  => true,
                'message' => 'Oops, Internal server error',
                'status'  => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        return ($comment->post->user_id != auth()->id())
            ? response()->json([
                'errors'  => true,
                'message' => 'Unauthorized',
                'status'  => Response::HTTP_UNAUTHORIZED,
            ], Response::HTTP_UNAUTHORIZED)
            : response()->json([
                'errors'   => false,
                'data' => new CommentResource($comment),
                'status'  => Response::HTTP_OK,
            ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Frontend\CommentRequest  $request
     * @param  App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(CommentRequest $request, Comment $comment)
    {
        if ($comment->post->user_id != auth()->id()) return response()->json([
            'errors'  => true,
            'message' => 'Unauthorized',
            'status'  => Response::HTTP_UNAUTHORIZED,
        ], Response::HTTP_UNAUTHORIZED);

        $data = [
            'name'    => $request->name,
            'email'   => $request->email,
            'url'     => ($request->url) ? $request->url : null,
            'status'  => $request->status,
            'comment' => $request->comment,
        ];
        $comment->update($data);
        Cache::forget('recent_comments');

        return response()->json([
            'errors'  => false,
            'message' => 'Comment updated successfully!',
            'status'  => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        if ($comment->post->user_id != auth()->id()) return response()->json([
            'errors'  => true,
            'message' => 'Unauthorized',
            'status'  => Response::HTTP_UNAUTHORIZED,
        ], Response::HTTP_UNAUTHORIZED);

        $comment->delete();
        Cache::forget('recent_comments');

        return response()->json([
            'errors'  => false,
            'message' => 'Comment deleted successfully!',
            'status'  => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }
}
