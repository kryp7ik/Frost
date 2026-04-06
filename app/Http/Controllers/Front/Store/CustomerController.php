<?php

namespace App\Http\Controllers\Front\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\CustomerFormRequest;
use App\Models\Store\Customer;
use App\Repositories\Store\Customer\CustomerRepositoryContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function __construct(protected CustomerRepositoryContract $customers)
    {
    }

    public function index(): InertiaResponse
    {
        return Inertia::render('Customers/Index', [
            'customers' => Customer::orderBy('name')
                ->get(['id', 'name', 'phone', 'email', 'points'])
                ->map(fn ($c) => [
                    'id' => $c->id,
                    'name' => $c->name,
                    'phone' => $c->phone,
                    'email' => $c->email,
                    'points' => $c->points,
                ])
                ->values(),
        ]);
    }

    public function dataTables()
    {
        return DataTables::of(Customer::query())
            ->setRowId(fn ($customer) => $customer->id)
            ->make(true);
    }

    public function store(CustomerFormRequest $request): RedirectResponse
    {
        $this->customers->create($request->all());

        return redirect('/customers');
    }

    public function show(int $id): InertiaResponse
    {
        $customer = $this->customers->findById($id);

        return Inertia::render('Customers/Show', [
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'email' => $customer->email,
                'points' => $customer->points,
                'preferred' => $customer->preferred ?? null,
            ],
        ]);
    }

    public function ajaxUpdate(int $id, Request $request): JsonResponse
    {
        $status = $this->customers->updateField(
            $id,
            $request->get('name'),
            $request->get('value')
        ) ? 1 : 0;

        return response()->json(['status' => $status]);
    }

    public function points(string $phone, CustomerRepositoryContract $customerRepo): JsonResponse
    {
        $customer = $customerRepo->findByPhone($phone);

        return response()->json($customer->points);
    }
}
