<?php

namespace App\Http\Controllers\Admin\Store;

use App\Models\Store\Customer;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\CustomerFormRequest;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('backend.customers.index', compact('customers'));
    }

    public function store(CustomerFormRequest $request)
    {
        $customer = new Customer(array(
            'phone' => $request->get('phone'),
            'name' => $request->get('name'),
            'email' => $request->get('email')
        ));
        $customer->save();
        return redirect('/admin/store/customers')->with('status', 'A new customer has been added.');
    }

    public function show($id)
    {
        $customer = Customer::whereId($id)->firstOrFail();
        return view('backend.customers.show', compact('customer'));
    }

    public function edit($id)
    {
        $customer = Customer::whereId($id)->firstOrFail();
        return view('backend.customers.edit', compact('customer'));
    }

    public function update($id, CustomerFormRequest $request)
    {
        $customer = Customer::whereId($id)->firstOrFail();
        $customer->name = $request->get('name');
        $customer->phone = $request->get('phone');
        $customer->email = $request->get('email');
        $customer->save();
        return view('backend.customers.show', compact('customer'))->with('status', 'The customer has been successfully updated.');
    }
}
