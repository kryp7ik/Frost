<?php

namespace App\Http\Controllers\Front;

use App\Http\Requests\Account\AccountEditRequest;
use App\Http\Controllers\Controller;
use App\Repositories\Announcement\AnnouncementRepositoryContract;
use App\Repositories\Auth\UserRepositoryContract;
use App\Repositories\Store\Shift\ShiftRepositoryContract;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    protected $userRepo;

    public function __construct(UserRepositoryContract $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function dashboard(ShiftRepositoryContract $shiftRepo, AnnouncementRepositoryContract $announcementRepo)
    {
        $user = $this->userRepo->findById(Auth::user()->id);
        $shifts = $shiftRepo->getCurrentWeekForUser(Auth::user()->id);
        $announcements = $announcementRepo->getAll();
        return view('account.dashboard', compact('user', 'shifts', 'announcements'));
    }

    public function edit()
    {
        $user = $this->userRepo->findById(Auth::user()->id);
        return view('account.edit', compact('user'));
    }

    public function update(AccountEditRequest $request)
    {
        $this->userRepo->update(Auth::user()->id, $request->all());
        return redirect('/');
    }

}
