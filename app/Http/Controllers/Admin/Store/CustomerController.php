<?php

namespace App\Http\Controllers\Admin\Store;

use App\Models\Store\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\CustomerFormRequest;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('backend.store.customers.index', compact('customers'));
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
        return view('backend.store.customers.show', compact('customer'));
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
