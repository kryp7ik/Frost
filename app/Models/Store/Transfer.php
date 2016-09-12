<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

/**
 * Actions for this entity can be found in App\Http\Controllers\Admin\Store\InventoryController
 * Class Transfer
 * @package App\Models\Store
 */
class Transfer extends Model
{

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var array
     */
    protected $fillable = [
        'from_store',
        'to_store',
        'received'
    ];


    public function productInstances()
    {
        return $this->belongsToMany('App\Models\Store\ProductInstance', 'instance_transfer')->withPivot('quantity', 'id');
    }
}
