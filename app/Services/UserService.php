<?php
namespace App\Services;

class UserService
{
    /**
     * Delete the profile old image and upload the new one & return the new image name.
     *
     * @param object $image
     * @param object $user
     * @param int $size
     * @return string
     */
    public function handle_profile_image_in_server($image, $user, $size=800)
    {
        $user_image = $user->user_image;
        if($user_image) image_remove(public_path("/assets/users/".$user_image));

        $file_name = image_upload(
            $user->username,
            $image->getClientOriginalExtension(),
            public_path("assets\users\\"),
            $image->getRealPath(),
            $size
        );
        return $file_name;
    }
}
