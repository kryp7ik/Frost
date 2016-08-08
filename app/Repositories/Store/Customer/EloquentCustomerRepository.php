<?php

namespace App\Repositories\Store\Customer;

use App\Models\Store\Customer;

class EloquentCustomerRepository implements CustomerRepositoryContract
{

    public function getAll()
    {
        $customers = Customer::all();
        return $customers;
    }

    public function findById($id)
    {
        return Customer::where('id', $id)->firstOrFail();
    }

    public function findByPhone($phone)
    {
        return Customer::where('phone', $phone)->firstOrFail();
    }

    public function create($data)
    {
        $customer = new Customer(array(
            'phone' => $data['phone'],
            'name' => $data['name'],
            'email' => $data['email']
        ));
        $customer->save();
    }

    public function updateField($id, $fieldname, $value)
    {
        return Customer::where('id', $id)->update([$fieldname => $value]);
    }
}