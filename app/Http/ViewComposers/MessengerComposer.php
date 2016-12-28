<?php

namespace App\Http\ViewComposers;

use App\Repositories\Messenger\EloquentMessengerRepository;
use Illuminate\View\View;

class MessengerComposer
{

    protected $messengerRepo;

    public function __construct(EloquentMessengerRepository $eloquentMessengerRepository)
    {
        $this->messengerRepo = $eloquentMessengerRepository;
    }

    /**
     * Binds the Users to the messenger view
     * @param View $view
     */
    public function compose(View $view)
    {
        $users = $this->messengerRepo->getUserList();
        $view->with(['users' => $users]);
    }
}