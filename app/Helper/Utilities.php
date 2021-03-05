<?php

use App\Models\Permission;
use Illuminate\Support\Str;
use Spatie\Valuestore\Valuestore;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;


/**
 * Get Settings Value of a specific key.
 *
 * @param  string  $key
 *
 * @return mixed
 */
function get_settings_value_of($key)
{
    $settings = Valuestore::make(config_path('settings.json'));
    return $settings->get($key, 'Not Found');
}

/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mp | identicon | monsterid | wavatar | robohash | retro | blank ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source https://gravatar.com/site/implement/images/php/
 */
function get_gravatar( $email, $s = 80, $d = 'wavatar', $r = 'g', $img = false, $atts = array() ) {
    $url = 'https://www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&d=$d&r=$r";
    if ( $img ) {
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val )
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
}

/**
 * Redirect with session message to the specified route.
 *
 * @param  string  $route_name
 * @param  string  $session_msg
 * @param  string  $msg_type
 * @param  int|string $route_arg
 *
 * @return \Illuminate\Http\Response
 */
function redirect_with_msg($route_name, $session_msg, $msg_type, $route_arg = null)
{
    return (($route_arg)? redirect(route(
        $route_name,
        $route_arg
    ))->with([
        'message'    => $session_msg,
        'alert-type' => $msg_type
    ]): redirect(route($route_name))->with([
        'message'    => $session_msg,
        'alert-type' => $msg_type
    ]));
}

/**
 * Redirect to the specified route.
 *
 * @param  string  $route_name
 * @param  int|string $route_arg
 *
 * @return \Illuminate\Http\Response
 */
function redirect_to($route_name, $route_arg = null)
{
    return (($route_arg)? redirect(route(
        $route_name,
        $route_arg
    )): redirect(route($route_name)));
}

/**
 * Upload Images to the server's public folder and return array contains "file_name", "file_size", "file_type".
 *
 * @param  array  $images_src
 * @param  string $name_src
 * @param  string $public_folder_name
 *
 * @return array [$file_name,$file_size,$file_type]
 */
function images_upload($images_src,$name_src,$public_folder_name)
{
    $i = 1;
    $values = [];
    foreach ($images_src as $file) {
        $file_name = "{$name_src}-".time()."-{$i}.{$file->getClientOriginalExtension()}";
        $file_size = $file->getSize();
        $file_type = $file->getMimeType();
        $file_path = public_path("assets/{$public_folder_name}/{$file_name}");
        Image::make($file->getRealPath())->resize(
            800,
            null,
            function ($constraint)
            {
                $constraint->aspectRatio();
            }
        )->save($file_path, 100);
        $values[] = [
            'file_name' => $file_name,
            'file_size' => $file_size,
            'file_type' => $file_type,
        ];
        $i++;
    }
    return $values;
}

/**
 * Remove images from the server's public folder.
 *
 * @param  array|object  $images
 *
 * @return void
 */
function images_remove($images)
{
    foreach ($images as $image) {
        $file_name = (is_array($image))? $image['file_name']: $image->file_name;
        $file_path = "assets/posts/{$file_name}";
        if (File::exists($file_path)) unlink($file_path);
    }
}

/**
 * Remove single image from the server's public folder.
 *
 * @param string $file_path
 *
 * @return void
 */
function image_remove($file_path)
{
    if (File::exists($file_path)) unlink($file_path);
}

/**
 * Upload Image to the server & return the stored file name.
 *
 * @param string $store_name
 * @param string $file_extension
 * @param string $store_path
 * @param string $original_path
 * @param int $size
 *
 * @return string $file_name
 */
// public_path("assets\users\\")
function image_upload($store_name, $file_extension, $file_path, $original_path, $size=800)
{
    $file_name = Str::slug($store_name).".{$file_extension}";
    $file_path .= $file_name;
    Image::make($original_path)->resize(
        $size,
        null,
        function ($constraint)
        {
            $constraint->aspectRatio();
        }
    )->save($file_path, 100);
    return $file_name;
}

/**
 * Get the current sidebar link parent show DB attribute.
 *
 * @param string $route_name
 * @return mixed[integer|string]
 */
function get_parent_show_of($route_name)
{
    $needle = str_replace(
        'admin.',
        '',
        $route_name
    );
    $permission = Permission::whereAs($needle)->first();
    return ($permission)? $permission->parent_show: $needle;
}

/**
 * Get the current sidebar link parent DB attribute.
 *
 * @param string $route_name
 * @return mixed[integer|string]
 */
function get_parent_of($route_name)
{
    $needle = str_replace(
        'admin.',
        '',
        $route_name
    );
    $permission = Permission::whereAs($needle)->first();
    return ($permission)? $permission->parent: $needle;
}

/**
 * Get the current sidebar link parent id DB attribute.
 *
 * @param string $route_name
 * @return mixed[integer|string]
 */
function get_parent_id_of($route_name)
{
    $needle = str_replace(
        'admin.',
        '',
        $route_name
    );
    $permission = Permission::whereAs($needle)->first();
    return ($permission)? $permission->id: null;
}

/**
 * Get the current sidebar link menu id DB attribute.
 *
 * @param string $route_name
 * @return mixed[integer|null]
 */
function get_menu_id_of($menu_id)
{
    $permission = Permission::whereId($menu_id)->first();
    return ($permission)? $permission->parent_show: null;
}
