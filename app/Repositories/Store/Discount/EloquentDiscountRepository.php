<?php

namespace App\Repositories\Store\Discount;

use App\Models\Store\Discount;

class EloquentDiscountRepository implements DiscountRepositoryContract
{

    /**
     * @param bool $sort
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll($sort = false)
    {
        $discounts = Discount::all();
        if ($sort) {
            $sorted = [];
            foreach ($discounts as $discount) {
                if ($discount->approval) {
                    $sorted['Approval Required'][] = ['discount' => $discount->id, 'name' => $discount->name];
                } else {
                    $sorted['Standard'][] = ['discount' => $discount->id, 'name' => $discount->name];
                }
            }
            return $sorted;
        } else {
            return $discounts;
        }
    }

    /**
     * @param int $points Amount of reward points available
     * @return array
     */
    public function getRedeemable($points)
    {
        $discounts = Discount::where('redeemable', true)->where('value', '<=', $points)->get();
        return $discounts;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function findById($id)
    {
        return Discount::where('id', $id)->firstOrFail();
    }

    /**
     * @param array $data
     */
    public function create($data)
    {
        $discount = new Discount(array(
            'name' => $data['name'],
            'type' => $data['type'],
            'filter' => $data['filter'],
            'amount' => $data['amount'],
            'approval' => (isset($data['approval'])) ? true : false,
            'redeemable' => (isset($data['redeemable'])) ? true : false,
            'value' => $data['value']
        ));
        $discount->save();
        flash('The discount has been created successfully', 'success');
    }

    /**
     * @param int $id
     * @param array $data
     */
    public function update($id, $data)
    {
        $discount = Discount::whereId($id)->firstOrFail();
        $discount->name = $data['name'];
        $discount->type = $data['type'];
        $discount->filter = $data['filter'];
        $discount->amount = $data['amount'];
        $discount->approval = (isset($data['approval'])) ? true : false;
        $discount->redeemable = (isset($data['redeemable'])) ? true : false;
        $discount->value = $data['value'];
        $discount->save();
        flash('The discount has been updated successfully', 'success');
    }
}