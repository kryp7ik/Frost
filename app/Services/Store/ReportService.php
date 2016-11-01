<?php

namespace App\Services\Store;

use App\Repositories\Store\ShopOrder\ShopOrderRepositoryContract;

class ReportService {

    protected $orderRepo;

    public function __construct(ShopOrderRepositoryContract $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    public function generateSalesReport($store = null, $startDate = null, $endDate = null)
    {
        $start = ($startDate) ? new \DateTime($startDate) : new \DateTime();
        $end = ($endDate) ? new \DateTime($endDate) : new \DateTime();
        if ($store > 0) {
            $orders = $this->orderRepo->getByStore($store, $start->format('Y-m-d'), $end->format('Y-m-d'));
        } else {
            $orders = $this->orderRepo->getAll($start->format('Y-m-d'), $end->format('Y-m-d'));
        }
        $reportData = $this->parseOrdersForSalesReport($orders);
        return $reportData;
    }

    private function parseOrdersForSalesReport($orders)
    {
        $data = $this->getSalesReportDataArray();
        foreach ($orders as $order) {
            $data['subtotal'] += $order->subtotal;
            $data['gross'] += $order->total;
            $data['employee'][$order->user->name] = (isset($data['employee'][$order->user->name])) ? $data['employee'][$order->user->name] + $order->total : $order->total;
            $date = new \DateTime($order->created_at);
            $data['hourly'][$date->format('h\:\X\Xa')] = (isset($data['hourly'][$date->format('h\:\X\Xa')])) ? $data['hourly'][$date->format('h\:\X\Xa')] + $order->total : $order->total;
            $data['customers'][$date->format('h\:\X\Xa')] = (isset($data['customers'][$date->format('h\:\X\Xa')])) ? $data['customers'][$date->format('h\:\X\Xa')] + 1 : 1;
            $data['totalOrders']++;
            foreach ($order->payments as $payment) {
                $data[$payment->type] += $payment->amount;
            }
            foreach ($order->productInstances as $productInstance) {
                $data['productCost'] += $productInstance->product->cost * $productInstance->pivot->quantity;
            }
            foreach ($order->discounts as $discount) {
                $data['discounts'] += $discount->pivot->applied;
            }
        }
        $data['net'] = $data['gross'] - $data['productCost'];
        $data['averageOrder'] = ($data['totalOrders'] > 0) ? $data['gross'] / $data['totalOrders'] : 0.00;
        return $data;
    }

    private function getSalesReportDataArray() {
        return [
            'gross' => 0,
            'net' => 0,
            'productCost' => 0,
            'subtotal' => 0,
            'cash' => 0,
            'credit' => 0,
            'totalOrders' => 0,
            'averageOrder' => 0,
            'discounts' => 0,
            'employee' => [],
            'hourly' => [
                '09:XXam' => 0,
                '10:XXam' => 0,
                '11:XXam' => 0,
                '12:XXpm' => 0,
                '01:XXpm' => 0,
                '02:XXpm' => 0,
                '03:XXpm' => 0,
                '04:XXpm' => 0,
                '05:XXpm' => 0,
                '06:XXpm' => 0,
                '07:XXpm' => 0,
                '08:XXpm' => 0,
            ],
            'customers' => [
                '09:XXam' => 0,
                '10:XXam' => 0,
                '11:XXam' => 0,
                '12:XXpm' => 0,
                '01:XXpm' => 0,
                '02:XXpm' => 0,
                '03:XXpm' => 0,
                '04:XXpm' => 0,
                '05:XXpm' => 0,
                '06:XXpm' => 0,
                '07:XXpm' => 0,
                '08:XXpm' => 0,
            ]
        ];
    }


}