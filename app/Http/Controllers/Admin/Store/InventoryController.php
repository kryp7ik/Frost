<?php

namespace App\Http\Controllers\Admin\Store;

use App\Repositories\Store\ProductInstance\ProductInstanceRepositoryContract;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{

    /**
     * @var ProductInstanceRepositoryContract
     */
    protected $productInstances;

    /**
     * ProductController constructor.
     * @param ProductInstanceRepositoryContract $instances
     */
    public function __construct(ProductInstanceRepositoryContract $instances)
    {
        $this->productInstances = $instances;
    }


}