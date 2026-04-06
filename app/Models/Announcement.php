<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Announcement extends Model
{
    use HasFactory;

    protected static function newFactory(): \Database\Factories\AnnouncementFactory
    {
        return \Database\Factories\AnnouncementFactory::new();
    }
    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var array
     */
    protected $fillable = [
        'type',
        'title',
        'content',
        'sticky',
        'user_id',
    ];


    /**
     * ManyToOne Relation with User
     */
    public function user()
    {
        return $this->belongsTo('App\Models\Auth\User')->withTrashed();
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }
}
