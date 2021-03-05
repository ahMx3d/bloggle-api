<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Interfaces\Frontend\Repositories\IAuthMediaRepository;

class MediaController extends Controller
{
    private $auth_media_repo;   // Authenticated Media Repository Interface.
    /**
     * Construct Authenticated Media Interface.
     * Construct post methods middleware.
     *
     * @param IAuthMediaRepository $auth_media_repo
     * @return void
     */
    public function __construct(IAuthMediaRepository $auth_media_repo)
    {
        $this->middleware([
            'auth',
            'verified'
        ]);
        $this->auth_media_repo = $auth_media_repo;
    }

    /**
     * Delete media from db and server.
     *
     * @param int $id
     * @return void
     */
    public function destroy($id)
    {
        try {
            $media = $this->auth_media_repo->get_by_id($id);
            if (!$media) return false;

            DB::beginTransaction();
            $this->auth_media_repo->delete_by_id($id);
            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }
}
