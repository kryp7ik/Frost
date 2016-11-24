<?php

namespace App\Http\Controllers\Front\Store;

use App\Models\Store\Customer;
use App\Repositories\Store\Customer\CustomerRepositoryContract;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\CustomerFormRequest;
use Yajra\Datatables\Datatables;

class CustomerController extends Controller
{

    /**
     * @var CustomerRepositoryContract
     */
    protected $customers;

    /**
     * CustomerController constructor.
     * @param CustomerRepositoryContract $customerRepository
     */
    public function __construct(CustomerRepositoryContract $customerRepository)
    {
        $this->customers = $customerRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('customers.index');
    }

    /**
     * Used for ajax calls from dataTables to retrieve Customers
     * @return mixed
     */
    public function dataTables()
    {
        return Datatables::of(Customer::query())->setRowId(function($customer) { return $customer->id; })->make(true);
    }

    /**
     * @param CustomerFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CustomerFormRequest $request)
    {
        $this->customers->create($request->all());
        return redirect('/customers');
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $customer = $this->customers->findById($id);
        return view('customers.show', compact('customer'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxUpdate($id, Request $request)
    {
        if($this->customers->updateField($id, $request->get('name'), $request->get('value'))) {
            return response()->json(array('status' => 1));
        } else {
            return response()->json(array('status' => 0));
        }
    }
}
