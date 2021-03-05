<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Faker Factory
        $faker = Factory::create();

        // Admin Role
        $admin_role = Role::create([
            'name'          => 'admin',
            'display_name'  => 'Administrator',
            'description'   => 'System Administrator',
            'allowed_route' => 'admin',
        ]);
        // Editor Role
        $editor_role = Role::create([
            'name'          => 'editor',
            'display_name'  => 'Supervisor',
            'description'   => 'System Supervisor',
            'allowed_route' => 'admin',
        ]);
        // End User Role
        $user_role = Role::create([
            'name'          => 'user',
            'display_name'  => 'User',
            'description'   => 'Normal User',
            'allowed_route' => null,
        ]);

        // Admin
        $admin = User::create([
            'name'              => 'Admin',
            'username'          => 'admin',
            'email'             => 'admin@laravel.bloggle',
            'mobile'            => '966500000001',
            'email_verified_at' => Carbon::now(),
            'password'          => bcrypt('123123123'),
            'status'            => 1,
        ]);
        $admin->attachRole($admin_role);

        // Editor
        $editor = User::create([
            'name'              => 'Editor',
            'username'          => 'editor',
            'email'             => 'editor@laravel.bloggle',
            'mobile'            => '966500000002',
            'email_verified_at' => Carbon::now(),
            'password'          => bcrypt('123123123'),
            'status'            => 1,
        ]);
        $editor->attachRole($editor_role);

        // First Static User
        $first_user = User::create([
            'name'              => 'Ahmed Salah',
            'username'          => 'ahmed',
            'email'             => 'ahmed@laravel.bloggle',
            'mobile'            => '966500000003',
            'email_verified_at' => Carbon::now(),
            'password'          => bcrypt('123123123'),
            'status'            => 1,
        ]);
        $first_user->attachRole($user_role);

        // Second Static User
        $second_user = User::create([
            'name'              => 'John Doe',
            'username'          => 'john',
            'email'             => 'john@laravel.bloggle',
            'mobile'            => '966500000004',
            'email_verified_at' => Carbon::now(),
            'password'          => bcrypt('123123123'),
            'status'            => 1,
        ]);
        $second_user->attachRole($user_role);

        // Third Static User
        $third_user = User::create([
            'name'              => 'Lel Ahmed',
            'username'          => 'lel',
            'email'             => 'lel@laravel.bloggle',
            'mobile'            => '966500000005',
            'email_verified_at' => Carbon::now(),
            'password'          => bcrypt('123123123'),
            'status'            => 1,
        ]);
        $third_user->attachRole($user_role);

        // Rest of Users
        for ($i=0; $i < 10; $i++) {
            $user = User::create([
                'name'              => $faker->name,
                'username'          => $faker->userName,
                'email'             => $faker->email,
                'mobile'            => '9665' .random_int(1000000,99999999),
                'email_verified_at' => Carbon::now(),
                'password'          => bcrypt('123123123'),
                'status'            => 1,
            ]);
            $user->attachRole($user_role);
        }
    }
}
