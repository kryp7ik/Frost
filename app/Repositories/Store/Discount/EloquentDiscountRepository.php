<?php

namespace App\Repositories\Store\Discount;

use App\Models\Store\Discount;

class EloquentDiscountRepository implements DiscountRepositoryContract
{

    public function getAll()
    {
        return Discount::all();
    }

    public function findById($id)
    {
        return Discount::where('id', $id)->firstOrFail();
    }

    public function create($data)
    {
        $discount = new Discount(array(
            'name' => $data['name'],
            'type' => $data['type'],
            'filter' => $data['filter'],
            'amount' => $data['amount'],
            'approval' => (isset($data['approval'])) ? true : false
        ));
        $discount->save();
        flash('The discount has been created successfully', 'success');
    }

    public function update($id, $data)
    {
        $discount = Discount::whereId($id)->firstOrFail();
        $discount->name = $data['name'];
        $discount->type = $data['type'];
        $discount->filter = $data['filter'];
        $discount->amount = $data['amount'];
        $discount->approval = (isset($data['approval'])) ? true : false;
        $discount->save();
        flash('The discount has been updated successfully', 'success');
    }
}