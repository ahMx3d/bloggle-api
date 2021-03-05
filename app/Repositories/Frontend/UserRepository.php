<?php
namespace App\Repositories\Frontend;

use App\Models\User;
use App\Interfaces\Frontend\Repositories\IUserRepository;

class UserRepository implements IUserRepository
{
    private $user_model;    // Repository model.
    /**
     * Construct users model
     *
     * @return void
     */
    public function __construct()
    {
        $this->user_model = User::class;
    }

    /**
     * Get user by username.
     *
     * @param string $username
     * @return object
     */
    public function user_get_by_username($username)
    {
        return $this->user_model::whereUsername($username)->active()->first();
    }
}

