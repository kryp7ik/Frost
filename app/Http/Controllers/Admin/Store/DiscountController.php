<?php

namespace App\Http\Controllers\Admin\Store;

use App\Models\Store\Discount;
use App\Repositories\Store\Discount\DiscountRepositoryContract;
use App\Http\Requests\Store\DiscountFormRequest;
use App\Http\Controllers\Controller;

class DiscountController extends Controller
{
    protected $discounts;

    public function __construct(DiscountRepositoryContract $discounts)
    {
        $this->discounts = $discounts;
    }

    public function index()
    {
        $discounts = $this->discounts->getAll();
        $d = new Discount();
        return view('backend.store.discounts.index', compact('discounts', 'd'));
    }

    public function show($id)
    {
        $discount = $this->discounts->findById($id);
        return view('backend.store.discounts.show', compact('discount'));
    }

    public function store(DiscountFormRequest $request)
    {
        $this->discounts->create($request->all());
        return redirect('/admin/store/discounts');
    }

    public function edit($id)
    {
        $discount = $this->discounts->findById($id);
        return view('backend.store.discounts.edit', compact('discount'));
    }

    public function update($id, DiscountFormRequest $request)
    {
        $this->discounts->update($id, $request->all());
        return redirect('/admin/store/discounts');
    }
}
