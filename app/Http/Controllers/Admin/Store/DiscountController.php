<?php

namespace App\Http\Controllers\Admin\Store;

use App\Models\Store\Discount;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\Store\DiscountFormRequest;
use App\Http\Controllers\Controller;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::all();
        $d = new Discount();
        return view('backend.store.discounts.index', compact('discounts', 'd'));
    }

    public function show($id)
    {
        $discount = Discount::whereId($id)->firstOrFail();
        return view('backend.store.discounts.show', compact('discount'));
    }

    public function store(DiscountFormRequest $request)
    {
        $discount = new Discount(array(
            'name' => $request->get('name'),
            'type' => $request->get('type'),
            'filter' => $request->get('filter'),
            'amount' => $request->get('amount'),
            'approval' => ($request->get('approval')) ? true : false
        ));
        $discount->save();
        return redirect('/admin/store/discounts')->with('status', 'A new discount has been created successfully.');
    }

    public function edit($id)
    {
        $discount = Discount::whereId($id)->firstOrFail();
        return view('backend.store.discounts.edit', compact('discount'));
    }

    public function update($id, DiscountFormRequest $request)
    {
        $discount = Discount::whereId($id)->firstOrFail();
        $discount->name = $request->get('name');
        $discount->type = $request->get('type');
        $discount->filter = $request->get('filter');
        $discount->amount = $request->get('amount');
        $discount->approval = ($request->get('approval')) ? true : false;
        $discount->save();
        return redirect('/admin/store/discounts')->with('status', 'The discount has been updated successfully');
    }
}
