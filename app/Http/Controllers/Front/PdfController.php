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
        $order = $orders->findById($id, true);
        $height = '270';
        $height += $order->liquidProducts->count() * 8;
        $height += $order->productInstances->count() * 8;
        $height += $order->discounts->count() * 8;
        if ($order->customer) $height += 30;
        $pdf = app()->make('snappy.pdf.wrapper');
        $pdf->loadView('pdf.order.receipt', compact('order'))
            ->setOption('page-height', $height)
            ->setOption('page-width', '180');
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
