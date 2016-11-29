<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Comment extends Model
{
    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var array
     */
    protected $fillable = [
        'announcement_id',
        'user_id',
        'content',
    ];


    /**
     * ManyToOne Relation with User
     */
    public function user()
    {
        return $this->belongsTo('App\Models\Auth\User')->withTrashed();
    }

    /**
     * ManyToOne Relation with Announcement
     */
    public function announcement()
    {
        return $this->belongsTo('App\Models\Announcement');
    }
}
