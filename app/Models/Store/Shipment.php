<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Actions for this entity can be found in App\Http\Controllers\Admin\Store\InventoryController
 * Class Shipment
 * @package App\Models\Store
 */
class Shipment extends Model
{
    use HasFactory;

    protected static function newFactory(): \Database\Factories\ShipmentFactory
    {
        return \Database\Factories\ShipmentFactory::new();
    }

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var array
     */
    protected $fillable = [
        'store',
    ];

    public function productInstances()
    {
        return $this->belongsToMany('App\Models\Store\ProductInstance', 'instance_shipment')->withPivot('quantity', 'id');
    }
}
