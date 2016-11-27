<?php

namespace App\Http\Controllers\Front;

use App\Repositories\Store\ProductInstance\ProductInstanceRepositoryContract;
use App\Repositories\Store\ShopOrder\ShopOrderRepositoryContract;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PdfController extends Controller
{

    /**
     * Generates a receipt for a given ShopOrder
     * @param int $id
     * @param ShopOrderRepositoryContract $orders
     * @return mixed
     */
    public function orderReceipt($id, ShopOrderRepositoryContract $orders)
    {
        $order = $orders->findById($id);
        $pdf = app()->make('snappy.pdf.wrapper');
        $pdf->loadView('pdf.order.receipt', compact('order'));
        return $pdf->inline();
    }

    public function inventory(ProductInstanceRepositoryContract $productRepo)
    {
        $sortedInstances = $productRepo->getActiveWhereStore(Auth::user()->store, true);
        $pdf = app()->make('snappy.pdf.wrapper');
        $pdf->loadView('pdf.inventory', compact('sortedInstances'));
        return $pdf->inline();
    }
}
