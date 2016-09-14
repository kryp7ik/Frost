<?php

namespace App\Repositories\Store\Customer;

use App\Models\Store\Customer;

class EloquentCustomerRepository implements CustomerRepositoryContract
{

    /**
     * @return mixed
     */
    public function getAll()
    {
        $customers = Customer::paginate(50);
        return $customers;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function findById($id)
    {
        return Customer::where('id', $id)->firstOrFail();
    }

    /**
     * Finds a customer by their phone number and if one does not exist it creates one
     * @param int $phone
     * @return Customer|bool
     */
    public function findByPhone($phone)
    {
        if (strlen($phone) != 10) {
            flash('Phone number must be 10 digits', 'danger');
            return false;
        }
        $customer = Customer::where('phone', $phone)->first();
        if (!$customer instanceof Customer) {
            $customer = $this->create(['phone' => $phone]);
        }
        return $customer;
    }

    /**
     * @param array $data
     * @return Customer
     */
    public function create($data)
    {
        $customer = new Customer(array(
            'phone' => $data['phone'],
            'name' => (isset($data['name'])) ? $data['name'] : '',
            'email' => (isset($data['email'])) ? $data['email'] : ''
        ));
        $customer->save();
        return $customer;
    }

    /**
     * @param int $id
     * @param string $fieldname
     * @param mixed $value
     * @return mixed
     */
    public function updateField($id, $fieldname, $value)
    {
        return Customer::where('id', $id)->update([$fieldname => $value]);
    }

    /**
     * @param int $customer_id
     * @param int $earnedPoints
     * @return bool
     */
    public function earnPoints($customer_id, $earnedPoints)
    {
        $customer = $this->findById($customer_id);
        if ($customer) {
            $customer->points += $earnedPoints;
            return $customer->save();
        } return false;
    }

    /**
     * @param int $customer_id
     * @param int $spentPoints
     * @return bool
     */
    public function spendPoints($customer_id, $spentPoints)
    {
        $customer = $this->findById($customer_id);
        if ($customer) {
            $customer->points -= $spentPoints;
            return $customer->save();
        } return false;
    }
}