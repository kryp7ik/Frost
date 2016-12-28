<?php

namespace App\Models\Auth;

use App\Traits\Messagable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;
    use EntrustUserTrait {
        SoftDeletes::restore insteadof EntrustUserTrait;
    }
    use SoftDeletes;
    use Messagable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'store',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * OneToMany relation with App\Models\Store\ShopOrder
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany('App\Models\Store\ShopOrder');
    }

    /**
     * OneToMany relation with App\Models\Store\Shifts
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shifts()
    {
        return $this->hasmany('App\Models\Store\Shifts');
    }

    /**
     * OneToMany relation with App\Models\Announcement
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function announcements()
    {
        return $this->hasMany('App\Models\Announcement');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Models\Comments');
    }

    /**
     * Retrieves the users 'store' attribute based upon the location of the shift they are currently clocked in at except
     * in the case of admin's who can manually set which store they want to be acting as.
     * @param $value
     * @return int|bool
     */
    public function getStoreAttribute($value)
    {
        if ($this->hasRole('admin')) return $value;
        $shiftRepo = App::make('App\Repositories\Store\Shift\ShiftRepositoryContract');
        $shift = $shiftRepo->findForTodayByUser($this->id);
        if($shift) {
            return $shift->store;
        } else {
            return false;
        }
    }
}
