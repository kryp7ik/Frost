<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var array
     */
    protected $fillable = [
        'store',
        'start',
        'end',
        'in',
        'out'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\Auth\User')->withTrashed();
    }
}
