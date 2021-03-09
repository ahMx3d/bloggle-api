<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Services\Api\PostService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\PostRequest;
use App\Http\Resources\Global\PostResource;
use App\Http\Resources\Global\PostCollection;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    private $postService;
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
        $this->middleware('auth:api')->only([
            'store',
            'update',
            'destroy',
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request
     * @return \App\Http\Resources\Global\PostCollection
     */
    public function index(Request $request)
    {
        $posts = $this->postService->paginatePosts($request);
        return PostCollection::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Frontend\PostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->postService->storePost($request);
            DB::commit();

            return response()->json([
                'errors'  => false,
                'message' => 'Post created successfully!',
                'status'  => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json([
                'errors'  => true,
                'message' => 'Oops, Something went wrong',
                'status'  => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Models\Post $post
     * @return App\Http\Resources\Global\PostResource
     */
    public function show(Post $post)
    {
        $post = $this->postService->getPostWithRelations($post);
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\PostRequest  $request
     * @param  App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Post $post)
    {
        try {
            DB::beginTransaction();
            $this->postService->updatePost($request, $post);
            DB::commit();

            return response()->json([
                'errors'  => false,
                'message' => 'Post updated successfully!',
                'status'  => Response::HTTP_OK,
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'errors'  => true,
                'message' => 'Internal server error',
                'status'  => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        try {
            DB::beginTransaction();
            Post::destroy($post->id);
            DB::commit();

            return response()->json([
                'errors'  => false,
                'message' => 'Post deleted successfully!',
                'status'  => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'errors'  => true,
                'message' => 'Internal server error',
                'status'  => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
