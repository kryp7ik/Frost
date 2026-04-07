<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Repositories\Announcement\AnnouncementRepositoryContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class AnnouncementController extends Controller
{
    public function __construct(protected AnnouncementRepositoryContract $announcementRepo)
    {
    }

    public function index(): InertiaResponse
    {
        $announcements = $this->announcementRepo->getAll(15);

        $mapAnnouncement = fn ($a) => [
            'id' => $a->id,
            'title' => $a->title,
            'content' => $a->content,
            'sticky' => (bool) $a->sticky,
            'type' => $a->type,
            'created_at' => $a->created_at?->diffForHumans(),
        ];

        $sticky = $announcements['sticky'] ?? collect();
        $standardPaginator = $announcements['standard'] ?? null;
        $standard = $standardPaginator ? collect($standardPaginator->items()) : collect();

        return Inertia::render('Announcements/Index', [
            'announcements' => [
                'sticky' => $sticky->map($mapAnnouncement)->values(),
                'standard' => $standard->map($mapAnnouncement)->values(),
            ],
        ]);
    }

    public function create(): InertiaResponse
    {
        return Inertia::render('Announcements/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->announcementRepo->create(Auth::user()->id, $request->all());

        return redirect('/announcements');
    }

    public function show(int $id): JsonResponse
    {
        $announcement = $this->announcementRepo->findById($id, true, true);

        return response()->json($announcement);
    }

    public function edit(int $id): InertiaResponse
    {
        $announcement = $this->announcementRepo->findById($id, false, false);

        return Inertia::render('Announcements/Edit', [
            'announcement' => [
                'id' => $announcement->id,
                'title' => $announcement->title,
                'content' => $announcement->content,
                'type' => $announcement->type,
                'sticky' => (bool) $announcement->sticky,
            ],
        ]);
    }

    public function update(int $id, Request $request): RedirectResponse
    {
        $this->announcementRepo->update($id, $request->all());

        return redirect('/announcements');
    }

    public function delete(int $id): RedirectResponse
    {
        $this->announcementRepo->delete($id);

        return redirect('/announcements');
    }

    public function addComment(int $id, Request $request): JsonResponse
    {
        $status = $this->announcementRepo->addComment($id, Auth::user()->id, $request->get('content'));

        return response()->json($status);
    }
}
