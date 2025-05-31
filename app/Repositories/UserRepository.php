<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    /**
     * Get all users
     * 
     * @return <Collection, \App\Models\User>
     */
    public function getAll(): Collection
    {
        return $this->model->query()
            ->get();
    }
}
