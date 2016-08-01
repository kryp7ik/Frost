<?php

namespace App\Http\Controllers\Front\Store;

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
        return view('customers.index', compact('customers'));
    }

    public function store(CustomerFormRequest $request)
    {
        $customer = new Customer(array(
            'phone' => $request->get('phone'),
            'name' => $request->get('name'),
            'email' => $request->get('email')
        ));
        $customer->save();
        return redirect('/customers')->with('status', 'A new customer has been added.');
    }

    public function show($id)
    {
        $customer = Customer::whereId($id)->firstOrFail();
        return view('customers.show', compact('customer'));
    }

    public function ajaxUpdate($id, Request $request)
    {
        $name = $request->get('name');
        $value = $request->get('value');
        if($customer = Customer::where('id', $id)->update([$name => $value])) {
            return \Response::json(array('status' => 1));
        } else {
            return \Response::json(array('status' => 1));
        }
    }
}
