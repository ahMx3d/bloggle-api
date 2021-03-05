<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'display_name' => 'Site Title',
            'key'          => 'site_title',
            'value'        => 'Bloggle',
            'details'      => null,
            'type'         => 'text',
            'section'      => 'general',
            'ordering'     => 1
        ]);


        Setting::create([
            'display_name' => 'Site Slogan',
            'key'          => 'site_slogan',
            'value'        => 'bloggle',
            'details'      => null,
            'type'         => 'text',
            'section'      => 'general',
            'ordering'     => 2
        ]);
        Setting::create([
            'display_name' => 'Site Description',
            'key'          => 'site_description',
            'value'        => 'Bloggle Content management system',
            'details'      => null,
            'type'         => 'text',
            'section'      => 'general',
            'ordering'     => 3
        ]);
        Setting::create([
            'display_name' => 'Site Keywords',
            'key'          => 'site_keywords',
            'value'        => 'Bloggle, blog, multi writer',
            'details'      => null,
            'type'         => 'text',
            'section'      => 'general',
            'ordering'     => 4
        ]);
        Setting::create([
            'display_name' => 'Site Email',
            'key'          => 'site_email',
            'value'        => 'admin@bloggle.test',
            'details'      => null,
            'type'         => 'text',
            'section'      => 'general',
            'ordering'     => 5
        ]);
        Setting::create([
            'display_name' => 'Site Status',
            'key'          => 'site_status',
            'value'        => 'Active',
            'details'      => null,
            'type'         => 'text',
            'section'      => 'general',
            'ordering'     => 6
        ]);
        Setting::create([
            'display_name' => 'Admin Title',
            'key'          => 'admin_title',
            'value'        => 'Bloggle',
            'details'      => null,
            'type'         => 'text',
            'section'      => 'general',
            'ordering'     => 7
        ]);
        Setting::create([
            'display_name' => 'Phone Number',
            'key'          => 'phone_number',
            'value'        => '03403400435',
            'details'      => null,
            'type'         => 'text',
            'section'      => 'general',
            'ordering'     => 8
        ]);
        Setting::create([
            'display_name' => 'Address',
            'key'          => 'address',
            'value'        => 'M57F+QM ipsum dolor sit amet consectetur, Cairo',
            'details'      => null,
            'type'         => 'text',
            'section'      => 'general',
            'ordering'     => 9
        ]);
        Setting::create([
            'display_name' => 'Map Latitude',
            'key'          => 'address_latitude',
            'value'        => '21.671914',
            'details'      => null,
            'type'         => 'text',
            'section'      => 'general',
            'ordering'     => 10
        ]);
        Setting::create([
            'display_name' => 'Map Longitude',
            'key'          => 'address_longitude',
            'value'        => '39.173875',
            'details'      => null,
            'type'         => 'text',
            'section'      => 'general',
            'ordering'     => 11
        ]);
        Setting::create([
            'display_name' => 'Google Maps API Key',
            'key'          => 'google_maps_api_key',
            'value'        => null,
            'details'      => null,
            'type'         => 'text',
            'section'      => 'social_accounts',
            'ordering'     => 1
        ]);
        Setting::create([
            'display_name' => 'Google Recaptcha API Key',
            'key'          => 'google_recaptcha_api_key',
            'value'        => null,
            'details'      => null,
            'type'         => 'text',
            'section'      => 'social_accounts',
            'ordering'     => 2
        ]);
        Setting::create([
            'display_name' => 'Google Analytics Client ID',
            'key'          => 'google_analytics_client_id',
            'value'        => null,
            'details'      => null,
            'type'         => 'text',
            'section'      => 'social_accounts',
            'ordering'     => 3
        ]);
        Setting::create([
            'display_name' => 'Facebook ID',
            'key'          => 'facebook_id',
            'value'        => 'https://www.facebook.com/_ahmedxsalah',
            'details'      => null,
            'type'         => 'text',
            'section'      => 'social_accounts',
            'ordering'     => 4
        ]);
        Setting::create([
            'display_name' => 'Twitter ID',
            'key'          => 'twitter_id',
            'value'        => 'https://twitter.com/_ahmedXsalah',
            'details'      => null,
            'type'         => 'text',
            'section'      => 'social_accounts',
            'ordering'     => 5
        ]);
        Setting::create([
            'display_name' => 'Instagram ID',
            'key'          => 'instagram_id',
            'value'        => 'https://instagram.com/_ahmedxsalah',
            'details'      => null,
            'type'         => 'text',
            'section'      => 'social_accounts',
            'ordering'     => 6
        ]);
        Setting::create([
            'display_name' => 'Patreon ID',
            'key'          => 'flickr_id',
            'value'        => 'https://www.patreon.com/_ahmedxsalah',
            'details'      => null,
            'type'         => 'text',
            'section'      => 'social_accounts',
            'ordering'     => 7
        ]);
        Setting::create([
            'display_name' => 'Youtube ID',
            'key'          => 'youtube_id',
            'value'        => 'https://www.youtube.com/_ahmedxsalah',
            'details'      => null,
            'type'         => 'text',
            'section'      => 'social_accounts',
            'ordering'     => 8
        ]);
        Setting::create([
            'display_name' => 'Site Address',
            'key'          => 'site_address',
            'value'        => 'https://laravel.bloggle',
            'details'      => null,
            'type'         => 'text',
            'section'      => 'social_accounts',
            'ordering'     => 9
        ]);
    }
}
