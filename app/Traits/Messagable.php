<?php

namespace App\Traits;

trait Messagable
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany('App\Models\Messenger\Message');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function conversations()
    {
        return $this->belongsToMany('App\Models\Messenger\Conversation', 'conversation_user')->withPivot('lastread');
    }

}
