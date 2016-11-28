<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Announcement\AnnouncementRepositoryContract;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    protected $announcementRepo;

    public function __construct(AnnouncementRepositoryContract $announcementRepositoryContract)
    {
        $this->announcementRepo = $announcementRepositoryContract;
    }

    public function create()
    {
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        $this->announcementRepo->create(Auth::user()->id, $request->all());
        return redirect('/');
    }

    public function edit($id)
    {
        $announcement = $this->announcementRepo->findById($id);
        return view('announcements.edit', compact('announcement'));
    }

    public function update($id, Request $request)
    {
        $this->announcementRepo->update($id, $request->all());
        return redirect('/');
    }

    public function delete($id)
    {
        $this->announcementRepo->delete($id);
        return redirect('/');
    }

}
