<?php

namespace App\Services;

use App\Repositories\Auth\UserRepositoryContract;
use App\Repositories\Messenger\EloquentMessengerRepository;

class MessengerService
{

    protected $messengerRepo;

    protected $userRepo;

    public function __construct(EloquentMessengerRepository $eloquentMessengerRepository, UserRepositoryContract $userRepositoryContract)
    {
        $this->messengerRepo = $eloquentMessengerRepository;
        $this->userRepo = $userRepositoryContract;
    }

    public function getUsers()
    {
        $users = $this->userRepo->getAll();
        return $users;
    }
}
