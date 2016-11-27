<?php

namespace App\Services\Store;

use App\Events\OrderCompleted;
use App\Models\Store\Discount;
use App\Models\Store\ShopOrder;
use Illuminate\Support\Facades\DB;
use App\Services\Store\InventoryService;

class ShopOrderCalculator {

    /**
     * @var ShopOrder
     */
    public $order;

    private $liquidTotal = 0;

    private $productTotal = 0;

    private $subTotal = 0;

    public function __construct(ShopOrder $order)
    {
        $this->order = $order;
    }

    /**
     * Calculates number of reward points a customer will earn from the given ShopOrder
     * @return int $points
     */
    public function getPoints()
    {
        $this->calculateOrder();
        $points = ceil($this->liquidTotal * 2);
        $points += ceil($this->productTotal);
        return $points;
    }

    /**
     * Function is only called after a payment has been added or removed
     * If the order has been fully paid for set the complete attribute to true
     * If a payment was removed and the order is no longer paid in full but was previously complete, set to incomplete
     * and fire the OrderCompleted event with the reverse parameter set to true which causes the products to be added
     * back into inventory and subtracts the points that the customer had earned from this order
     * @return bool Completed?
     */
    public function checkComplete()
    {
        $this->order = $this->order->fresh(['payments']);
        if ($this->getRemainingBalance() == 0) {
            $this->order->complete = true;
            $this->order->save();
            return true;
        } else {
            if ($this->order->complete) {
                $this->order->complete = false;
                $this->order->save();
                event(new OrderCompleted($this->order, true));
            }
            return false;
        }
    }

    /**
     * Iterates through all payments and returns the remaining balance
     * @return float $balance
     */
    public function getRemainingBalance()
    {
        $balance = $this->order->total;
        foreach ($this->order->payments as $payment) {
            $balance -= $payment->amount;
        }
        return number_format($balance, 2);
    }

    /**
     * Calculates the order total & subtotal, sets the ShopOrder's attributes to those values and saves the ShopOrder
     * If the order is complete do not recalc total in the event that a price had been changed since it's completion
     * @return ShopOrder
     */
    public function calculateTotal()
    {
        if ($this->order->complete) return $this->order;
        $tax = config('store.tax');
        $this->calculateOrder();
        $this->order->subtotal = str_replace(',','', number_format($this->subTotal, 2));
        $this->order->total = $this->subTotal * (1 + $tax);
        $this->order->save();
        return $this->order;
    }

    /**
     * Calculates the subtotal, liquidTotal & productTotal
     */
    private function calculateOrder()
    {
        $this->order = $this->order->fresh(['liquidProducts', 'productInstances', 'discounts']);
        $this->calculateLiquidProductTotal();
        $this->calculateProductTotal();
        $this->applyDiscounts();
    }

    /**
     * Calculates the total of all LiquidProducts
     */
    private function calculateLiquidProductTotal()
    {
        foreach ($this->order->liquidProducts as $liquidProduct){
            $this->liquidTotal += $liquidProduct->getPrice();
        }
    }

    /**
     * Calculates the total of all ProductInstances
     */
    private function calculateProductTotal()
    {
        foreach ($this->order->productInstances as $productInstance) {
            $this->productTotal += $productInstance->price * $productInstance->pivot->quantity;
        }
    }

    /**
     * Applies all discounts and generates $this->subTotal
     * NOTE: If a discount does not have a filter it is stacked to an array that is applied after all other
     * discounts have been applied so the no filter discounts do not apply to the full value of already discounted items
     */
    private function applyDiscounts()
    {
        $noFilterDiscounts = [];
        foreach ($this->order->discounts as $discount) {
            switch ($discount->filter) {
                case 'none':
                    $noFilterDiscounts[] = $discount;
                    break;
                case 'product':
                    $this->applyProductDiscount($discount);
                    break;
                case 'liquid':
                    $this->applyLiquidDiscount($discount);
                    break;
                default:
                    break;
            }
        }
        $this->subTotal = $this->liquidTotal + $this->productTotal;
        if (count($noFilterDiscounts) > 0) $this->applyNoFilterDiscounts($noFilterDiscounts);
    }

    /**
     * Applies remaining discounts to the subtotal after all other discounts have been applied
     * @param array $noFilterDiscounts An array of discounts with no filter
     */
    private function applyNoFilterDiscounts($noFilterDiscounts)
    {
        foreach ($noFilterDiscounts as $discount) {
            if ($discount->type == 'percent') {
                $amount = number_format($this->subTotal * ($discount->amount / 100), 2);
                if ($discount->pivot->applied != $amount) {
                    DB::table('order_discount')->where('id', $discount->pivot->id)->update(['applied' => $amount]);
                }
                $this->subTotal -= $amount;
            } else {
                if ($discount->amount > $this->subTotal) {
                    DB::table('order_discount')->where('id', $discount->pivot->id)->update(['applied' => $this->subTotal]);
                    $this->subTotal = 0;
                } else {
                    $this->subTotal -= $discount->amount;
                }
            }
        }
    }

    /**
     * Applies discounts to the productTotal only
     * @param Discount $discount
     */
    private function applyProductDiscount(Discount $discount)
    {
        if($discount->type == 'percent') {
            $amount = number_format($this->productTotal * ($discount->amount / 100), 2);
            if ($discount->pivot->applied != $amount) {
                DB::table('order_discount')->where('id', $discount->pivot->id)->update(['applied' => $amount]);
            }
            $this->productTotal -= $amount;
        } else {
            if ($discount->amount > $this->productTotal) {
                DB::table('order_discount')->where('id', $discount->pivot->id)->update(['applied' => $this->productTotal]);
                $this->productTotal = 0;
            } else {
                $this->productTotal -= $discount->amount;
            }
        }
    }

    /**
     * Applies discounts to the liquidTotal only
     * @param Discount $discount
     */
    private function applyLiquidDiscount(Discount $discount)
    {
        if($discount->type == 'percent') {
            $amount = number_format($this->liquidTotal * ($discount->amount / 100), 2);
            if ($discount->pivot->applied != $amount) {
                DB::table('order_discount')->where('id', $discount->pivot->id)->update(['applied' => $amount]);
            }
            $this->liquidTotal -= $amount;
        } else {
            if ($discount->amount > $this->liquidTotal) {
                DB::table('order_discount')->where('id', $discount->pivot->id)->update(['applied' => $this->liquidTotal]);
                $this->liquidTotal = 0;
            } else {
                $this->liquidTotal -= $discount->amount;
            }
        }
    }
}