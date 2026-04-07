<?php

namespace App\Http\Controllers\Front;

use App\Http\Requests\Account\AccountEditRequest;
use App\Http\Controllers\Controller;
use App\Repositories\Announcement\AnnouncementRepositoryContract;
use App\Repositories\Auth\UserRepositoryContract;
use App\Repositories\Store\Shift\ShiftRepositoryContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class AccountController extends Controller
{
    public function __construct(protected UserRepositoryContract $userRepo)
    {
    }

    public function dashboard(
        ShiftRepositoryContract $shiftRepo,
        AnnouncementRepositoryContract $announcementRepo
    ): InertiaResponse {
        $user = $this->userRepo->findById(Auth::user()->id);
        $shifts = $shiftRepo->getCurrentWeekForUser(Auth::user()->id);
        $announcements = $announcementRepo->getAll();

        $mapAnnouncement = fn ($a) => [
            'id' => $a->id,
            'title' => $a->title,
            'content' => $a->content,
            'sticky' => (bool) $a->sticky,
            'created_at' => $a->created_at?->diffForHumans(),
        ];

        $sticky = $announcements['sticky'] ?? collect();
        $standardPaginator = $announcements['standard'] ?? null;
        $standard = $standardPaginator ? collect($standardPaginator->items()) : collect();

        return Inertia::render('Dashboard', [
            'dashboardUser' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'shifts' => $shifts ? collect($shifts)->map(fn ($s) => [
                'id' => $s->id,
                'start' => (string) $s->start,
                'end' => (string) $s->end,
                'store' => $s->store,
            ])->values() : [],
            'announcements' => [
                'sticky' => $sticky->map($mapAnnouncement)->values(),
                'standard' => $standard->map($mapAnnouncement)->values(),
            ],
        ]);
    }

    public function edit(): InertiaResponse
    {
        $user = $this->userRepo->findById(Auth::user()->id);

        return Inertia::render('Account/Edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    public function update(AccountEditRequest $request): RedirectResponse
    {
        $this->userRepo->update(Auth::user()->id, $request->all());

        return redirect('/');
    }

    public function twoFactor(): InertiaResponse
    {
        return Inertia::render('Profile/TwoFactor');
    }
}
