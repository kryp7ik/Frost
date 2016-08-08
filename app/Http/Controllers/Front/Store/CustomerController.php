<?php

namespace App\Http\Controllers\Front\Store;

use App\Repositories\Store\Customer\CustomerRepositoryContract;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\CustomerFormRequest;

class CustomerController extends Controller
{

    protected $customers;

    public function __construct(CustomerRepositoryContract $customerRepository)
    {
        $this->customers = $customerRepository;
    }

    public function index()
    {
        $customers = $this->customers->getAll();
        return view('customers.index', compact('customers'));
    }

    public function store(CustomerFormRequest $request)
    {
        $this->customers->create($request->all());
        return redirect('/customers');
    }

    public function show($id)
    {
        $customer = $this->customers->findById($id);
        return view('customers.show', compact('customer'));
    }

    public function ajaxUpdate($id, Request $request)
    {
        if($this->customers->updateField($id, $request->get('name'), $request->get('value'))) {
            return \Response::json(array('status' => 1));
        } else {
            return \Response::json(array('status' => 1));
        }
    }
}
