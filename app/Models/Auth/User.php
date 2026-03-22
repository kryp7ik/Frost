<?php

namespace App\Models\Auth;

use App\Models\Auth\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\App;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected static function newFactory()
    {
        return \Database\Factories\UserFactory::new();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'store',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * OneToMany relation with App\Models\Store\ShopOrder
     */
    public function orders()
    {
        return $this->hasMany('App\Models\Store\ShopOrder');
    }

    /**
     * OneToMany relation with App\Models\Store\Shift
     */
    public function shifts()
    {
        return $this->hasMany('App\Models\Store\Shift');
    }

    /**
     * OneToMany relation with App\Models\Announcement
     */
    public function announcements()
    {
        return $this->hasMany('App\Models\Announcement');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    /**
     * Retrieves the user's 'store' attribute based on the shift location they
     * are currently clocked in at, except admins who can set it manually.
     */
    public function getStoreAttribute($value)
    {
        if ($this->hasRole('admin')) return $value;
        $shiftRepo = App::make('App\Repositories\Store\Shift\ShiftRepositoryContract');
        $shift = $shiftRepo->findForTodayByUser($this->id);
        if ($shift) {
            return $shift->store;
        }
        return false;
    }
}
