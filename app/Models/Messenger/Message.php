<?php

namespace App\Models\Messenger;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'conversation_id',
        'content',
    ];

    protected $touches = ['conversation'];

    /**
     * ManyToOne Relation with User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\Auth\User')->withTrashed();
    }

    /**
     * ManyToOne Relation with Conversation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function conversation()
    {
        return $this->belongsTo('App\Models\Messenger\Conversation');
    }

}
