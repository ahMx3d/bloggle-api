<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\PostMedia;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Auth\TagResource;
use App\Http\Resources\Auth\CategoryResource;
use App\Http\Resources\Auth\UserPostResource;
use Symfony\Component\HttpFoundation\Response;

class UserPostController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function show(User $user, Post $post)
    {
        $tags       = Tag::select('id','name')->get();
        $categories = Category::active()->get();
        return [
            'post'       => new UserPostResource($post),
            'tags'       => TagResource::collection($tags),
            'categories' => CategoryResource::collection($categories),
        ];
    }

    public function destroyUserPostMedia(User $user, Post $post, PostMedia $media)
    {
        try {
            DB::beginTransaction();
            PostMedia::destroy($media->id);
            DB::commit();
            return response()->json([
                'errors'  => false,
                'message' => 'Media Deleted Successfully!',
                'status'  => Response::HTTP_OK,
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'errors'  => true,
                'message' => 'Internal Server Error',
                'status'  => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
