<?php

namespace App\CommandHandlers;

use App\Models\User;
use App\Bus\CommandHandler;
use App\Services\CountryService;
use App\Commands\CreateUserCommand;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CreateUserCommandHandler extends CommandHandler
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected CountryService $countryService,
    ) {}

    public function handle(CreateUserCommand $createUserCommand): User
    {
        $dailingCode = $this->countryService->getDailingCodeByCoutryIsoCode2($createUserCommand->createUserData->country);

        if (!$dailingCode) {
            throw new ModelNotFoundException("Dailing code not found for country " . $createUserCommand->createUserData->country);
        }

        return $this->userRepository->create([
            "name" => $createUserCommand->createUserData->name,
            "email" => $createUserCommand->createUserData->email,
            "contact_number" => $dailingCode . "-" . $createUserCommand->createUserData->contactNumber,
            "password" => Hash::make($createUserCommand->createUserData->password),
        ]);
    }
}
