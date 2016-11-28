<?php

namespace App\Repositories\Announcement;


use App\Models\Announcement;

class EloquentAnnouncementRepository implements AnnouncementRepositoryContract
{

    /**
     * Retrieves recent announcements and stickied ones
     * @param int $limit
     * @return array $announcements['sticky' => [...], 'standard' => [...] ]
     */
    public function getRecent($limit = 5) {
        $announcements['sticky'] = $this->getSticky();
        $announcements['standard'] = $this->getStandard($limit);
        return $announcements;
    }

    /**
     * Retrieves the most recent Announcements that are not sticky
     * @param int $limit
     * @return mixed
     */
    public function getStandard($limit = 5) {
        $announcements = Announcement::orderBy('id', 'desc')
            ->where('sticky', '=', 0)
            ->limit($limit)
            ->get();
        return $announcements;
    }

    /**
     * Retrieves all sticky announcements
     * @return mixed
     */
    public function getSticky() {
        $announcements = Announcement::orderBy('id', 'desc')
            ->where('sticky', '=', 1)
            ->get();
        return $announcements;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function findById($id) {
        return Announcement::where('id', '=', $id)->firstOrFail();
    }

    /**
     * @param int $user_id
     * @param array $data
     * @return Announcement|bool
     */
    public function create($user_id, $data)
    {
        $announcement = new Announcement([
            'user_id' => $user_id,
            'title' => $data['title'],
            'type' => $data['type'],
            'sticky' => (isset($data['sticky'])) ? true : false,
            'content' => $data['content']
        ]);
        if ($announcement->save()) {
            flash('You have successfully posted a new Announcement!', 'success');
            return $announcement;
        } else {
            flash('Something went wrong while attempting to create a new Announcement!', 'danger');
            return false;
        }
    }

    /**
     * @param int $id
     * @param array $data
     * @return bool|Announcement
     */
    public function update($id, $data)
    {
        $announcement = $this->findById($id);
        if ($announcement) {
            $announcement->title = $data['title'];
            $announcement->type = $data['type'];
            $announcement->sticky = (isset($data['sticky'])) ? true : false;
            $announcement->content = $data['content'];
            $announcement->save();
            flash('The Announcement has been updated!', 'success');
            return $announcement;
        } else {
            flash('Something went wrong while attempting to update the Announcement!', 'danger');
            return false;
        }
    }

    /**
     * @param int $id
     */
    public function delete($id)
    {
        $announcement = $this->findById($id);
        if ($announcement) {
            $announcement->delete();
            flash('The Announcement has been deleted', 'warning');
        }
    }



}