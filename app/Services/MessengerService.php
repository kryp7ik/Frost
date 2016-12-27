<?php

namespace App\Services;

use App\Repositories\Messenger\EloquentMessengerRepository;

class MessengerService
{

    protected $messengerRepo;

    public function __construct(EloquentMessengerRepository $eloquentMessengerRepository)
    {
        $this->messengerRepo = $eloquentMessengerRepository;
    }




}
