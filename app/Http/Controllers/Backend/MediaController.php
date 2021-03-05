<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\PostMedia;

class MediaController extends Controller
{
    public function destroy(Request $request)
    {
        if (!auth()->user()->ability(
            'admin',
            'delete_posts'
        )) return redirect_to('admin.index');

        try {
            $media = PostMedia::whereId($request->key)->first();
            if (!$media) return false;

            DB::beginTransaction();
            PostMedia::destroy($media->id);
            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }
}
