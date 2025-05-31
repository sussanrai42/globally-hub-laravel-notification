<?php

namespace App\QueryHandlers;

use App\Models\User;
use App\Queries\GetUserByEmailQuery;
use App\Repositories\Interfaces\UserRepositoryInterface;

class GetUserByEmailQueryHandler
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
    ) {}

    public function handle(GetUserByEmailQuery $query): ?User
    {
        return $this->userRepository->findBy('email', $query->email);
    }
}
