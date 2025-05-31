<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get all users
     * 
     * @return <Collection, \App\Models\User>
     */
    public function getAll(): Collection;
}
