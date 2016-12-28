<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait Messagable
{

    /**
     * Checks to see if the user is online using Cache which is set from Middleware (UserOnlineStatus)
     * @return mixed
     */
    public function isOnline()
    {
        return Cache::has('user-is-online-' . $this->id);
    }

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
