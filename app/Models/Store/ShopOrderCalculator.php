<?php

namespace App\Models\Store;

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
     * Iterates through all payments and returns the remaining balance
     * @return float $balance
     */
    public function getRemainingBalance()
    {
        $balance = $this->order->total;
        foreach ($this->order->payments as $payment) {
            $balance -= $payment->amount;
        }
        return $balance;
    }

    /**
     * Calculates the order total & subtotal, sets the ShopOrder's attributes to those values and saves the ShopOrder
     * @return ShopOrder
     */
    public function calculateTotal()
    {
        $tax = config('store.tax');
        $this->calculateOrder();
        $this->order->subtotal = number_format($this->subTotal, 2);
        $this->order->total = $this->subTotal * (1 + $tax);
        $this->order->save();
        return $this->order;
    }

    /**
     * Calculates the subtotal, liquidTotal & productTotal
     */
    private function calculateOrder()
    {
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
     * discounts have been applied so the no filter discounts do not apply to already discounted items
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
                $this->subTotal *= (100 - $discount->amount) / 100;
            } else {
                $this->subTotal -= $discount->amount;
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
            $this->productTotal *= (100 - $discount->amount) / 100;
        } else {
            $this->productTotal -= $discount->amount;
        }
    }

    /**
     * Applies discounts to the liquidTotal only
     * @param Discount $discount
     */
    private function applyLiquidDiscount(Discount $discount)
    {
        if($discount->type == 'percent') {
            $this->liquidTotal *= (100 - $discount->amount) / 100;
        } else {
            $this->liquidTotal -= $discount->amount;
        }
    }
}