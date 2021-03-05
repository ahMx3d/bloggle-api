<?php

namespace App\Repositories\Frontend;

use App\Interfaces\Frontend\Repositories\IAuthUserRepository;

class AuthUserRepository implements IAuthUserRepository
{
    /**
     * Paginate all posts DESC related to the currently logged in user with relationships.
     *
     * @param int $pagination_count
     * @return object
     */
    public function paginate_posts_desc($pagination_count)
    {
        return auth()->user()->posts()->with([
            'media',
            'category',
            'user'
        ])->withCount('comments')->typePost()->orderDesc()->paginate(
            $pagination_count
        );
    }

    /**
     * Update the authenticated user plain text to hashed password .
     *
     * @param string $password
     * @return boolean
     */
    public function password_update($password)
    {
        return auth()->user()->update([
            'password' => bcrypt($password)
        ]);
    }

    /**
     * Update the authenticated user info.
     *
     * @param object $request
     * @param string|null $file_name
     * @return void
     */
    public function info_update($request, $file_name=null)
    {
        $data = [
            'name'          => $request->name,
            'email'         => $request->email,
            'mobile'        => $request->mobile,
            'bio'           => $request->bio,
            'receive_email' => $request->receive_email,
        ];
        if($file_name) $data['user_image'] = $file_name;
        auth()->user()->update($data);
    }
}
