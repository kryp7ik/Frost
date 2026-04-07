<?php

namespace App\Http\Controllers\Admin\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\DiscountFormRequest;
use App\Repositories\Store\Discount\DiscountRepositoryContract;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class DiscountController extends Controller
{
    public function __construct(protected DiscountRepositoryContract $discounts)
    {
    }

    public function index(): InertiaResponse
    {
        $discounts = $this->discounts->getAll();

        return Inertia::render('Admin/Store/Discounts/Index', [
            'discounts' => collect($discounts)->map(fn ($d) => [
                'id' => $d->id,
                'name' => $d->name,
                'type' => $d->type,
                'filter' => $d->filter,
                'amount' => $d->amount,
                'approval' => (bool) $d->approval,
                'redeemable' => (bool) $d->redeemable,
                'value' => $d->value,
            ])->values(),
            'typeOptions' => [
                ['value' => 'amount', 'title' => 'Dollar Amount'],
                ['value' => 'percent', 'title' => 'Percentage'],
            ],
            'filterOptions' => [
                ['value' => 'none', 'title' => 'No Filter'],
                ['value' => 'product', 'title' => 'Products Only'],
                ['value' => 'liquid', 'title' => 'Liquids Only'],
            ],
        ]);
    }

    public function store(DiscountFormRequest $request): RedirectResponse
    {
        $this->discounts->create($request->all());

        return redirect('/admin/store/discounts');
    }

    public function edit(int $id): InertiaResponse
    {
        $discount = $this->discounts->findById($id);

        return Inertia::render('Admin/Store/Discounts/Edit', [
            'discount' => [
                'id' => $discount->id,
                'name' => $discount->name,
                'type' => $discount->type,
                'filter' => $discount->filter,
                'amount' => $discount->amount,
                'approval' => (bool) $discount->approval,
                'redeemable' => (bool) $discount->redeemable,
                'value' => $discount->value,
            ],
            'typeOptions' => [
                ['value' => 'amount', 'title' => 'Dollar Amount'],
                ['value' => 'percent', 'title' => 'Percentage'],
            ],
            'filterOptions' => [
                ['value' => 'none', 'title' => 'No Filter'],
                ['value' => 'product', 'title' => 'Products Only'],
                ['value' => 'liquid', 'title' => 'Liquids Only'],
            ],
        ]);
    }

    public function update(int $id, DiscountFormRequest $request): RedirectResponse
    {
        $this->discounts->update($id, $request->all());

        return redirect('/admin/store/discounts');
    }
}
