<?php

namespace App\Services\Store;

use App\Repositories\Store\ProductInstance\ProductInstanceRepositoryContract;
use App\Repositories\Store\ShopOrder\ShopOrderRepositoryContract;

class ReportService {

    /**
     * @var ShopOrderRepositoryContract
     */
    protected $orderRepo;

    /**
     * @var ProductInstanceRepositoryContract
     */
    protected $instanceRepo;

    /**
     * ReportService constructor.
     *
     * @param ShopOrderRepositoryContract $orderRepo
     * @param ProductInstanceRepositoryContract $instanceRepositoryContract
     */
    public function __construct(ShopOrderRepositoryContract $orderRepo, ProductInstanceRepositoryContract $instanceRepositoryContract)
    {
        $this->orderRepo = $orderRepo;
        $this->instanceRepo = $instanceRepositoryContract;
    }

    /**
     * Generates an inventory report consisting of the total stock for each product along with price,cost,category & name
     * If no store is specified it will generate for all stores
     *
     * @param int $store
     * @return array
     */
    public function generateInventoryReport($store = 0)
    {
        if($store == 0) {
            $instances = $this->instanceRepo->getAllActive();
        } else {
            $instances = $this->instanceRepo->getActiveWhereStore($store);
        }
        return $this->parseInstances($instances);
    }

    /**
     * Parses through all instances and returns a sorted data array
     *
     * @param array $instances
     * @return array
     */
    private function parseInstances($instances)
    {
        $data = [
            'totals' => [
                'cost' => 0,
                'value' => 0,
            ],
            'products' => []
        ];
        foreach ($instances as $instance) {
            if($instance->stock < 0) continue;
            if(!isset($data['products'][$instance->product_id])) {
                $data['products'][$instance->product_id] = [
                    'name' => $instance->product->name,
                    'category' => $instance->product->category,
                    'cost' => $instance->product->cost,
                    'price' => $instance->price,
                    'stock' => $instance->stock
                ];
            } else {
                $data['products'][$instance->product_id]['stock'] += $instance->stock;
            }
            $data['totals']['cost'] += $instance->product->cost * $instance->stock;
            $data['totals']['value'] += $instance->price * $instance->stock;
        }
        return $data;
    }

    /**
     * Optionally accepts a store and date range to find all orders within the given parameters
     * Parses through all orders to return a sorted array of all relevant sales data
     *
     * @param null|int $store
     * @param null|string $startDate
     * @param null|string $endDate
     * @param string $type expects either 'detailed' or 'minimal'
     * @return array
     */
    public function generateSalesReport($store = null, $startDate = null, $endDate = null, $type = 'detailed')
    {
        $start = ($startDate) ? new \DateTime($startDate) : new \DateTime();
        $end = ($endDate) ? new \DateTime($endDate) : new \DateTime();
        if ($store > 0) {
            $orders = $this->orderRepo->getByStore($store, $start->format('Y-m-d'), $end->format('Y-m-d'));
        } else {
            $orders = $this->orderRepo->getAll($start->format('Y-m-d'), $end->format('Y-m-d'));
        }
        if($type == 'detailed') {
            $reportData = $this->parseOrdersForDetailedSalesReport($orders);
        } elseif($type == 'minimal') {
            $reportData = $this->parseOrdersForMinimalSalesReport($orders);
        } else {
            $reportData = $this->parseOrdersForDetailedSalesReport($orders);
        }
        return $reportData;
    }

    /**
     * Parses all the orders to retrieve only the gross and subtotal(gross - sales tax)
     *
     * @param $orders
     * @return array
     */
    public function parseOrdersForMinimalSalesReport($orders)
    {
        $data = ['subtotal' => 0, 'gross' => 0];
        foreach ($orders as $order) {
            $data['subtotal'] += $order->subtotal;
            $data['gross'] += $order->total;
        }
        return $data;
    }

    /**
     * Parses through the orders array and returns the report data
     *
     * @param array $orders The array of ShopOrders
     * @return array $data
     */
    private function parseOrdersForDetailedSalesReport($orders)
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
                $data['productSales'] += $productInstance->price * $productInstance->pivot->quantity;
            }
            foreach ($order->liquidProducts as $liquid) {
                $data['liquidSales'] += $liquid->getPrice();
                $data['liquids'][$liquid->size] = (isset($data['liquids'][$liquid->size])) ? $data['liquids'][$liquid->size] + 1: 1;
                $data['totalMl'] += $liquid->size;
            }
            foreach ($order->discounts as $discount) {
                $data['discounts'] += $discount->pivot->applied;
            }
        }
        $data['net'] = $data['gross'] - $data['productCost'];
        $data['averageOrder'] = ($data['totalOrders'] > 0) ? $data['gross'] / $data['totalOrders'] : 0.00;
        return $data;
    }

    /**
     * Returns the data array template for sales report
     *
     * @return array
     */
    private function getSalesReportDataArray() {
        return [
            'gross' => 0,
            'net' => 0,
            'productCost' => 0,
            'productSales' => 0,
            'liquidSales' => 0,
            'totalMl' => 0,
            'liquids' => [

            ],
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