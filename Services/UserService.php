<?php

namespace Services;

use App\Models\User;
use Repositories\User\UserRepository;

class UserService
{
    public function __construct(
        public readonly UserRepository $userRepository
    ) {
    }

    public function register(array $data)
    {
       return $this->userRepository->store($data);
    }
}