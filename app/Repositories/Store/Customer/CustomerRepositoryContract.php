<?php
/**
 * Created by PhpStorm.
 * User: kryptik
 * Date: 8/6/16
 * Time: 8:43 PM
 */
namespace App\Repositories\Store\Customer;

interface CustomerRepositoryContract
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll();

    /**
     * @param int $id
     * @return mixed
     */
    public function findById($id);

    /**
     * Finds a customer by their phone number and if one does not exist it creates one
     * @param int $phone
     * @return Customer|bool
     */
    public function findByPhone($phone);

    /**
     * @param array $data
     * @return Customer
     */
    public function create($data);

    /**
     * @param int $id
     * @param string $fieldname
     * @param mixed $value
     * @return mixed
     */
    public function updateField($id, $fieldname, $value);

    /**
     * @param int $customer_id
     * @param int $earnedPoints
     * @return bool
     */
    public function earnPoints($customer_id, $earnedPoints);

    /**
     * @param int $customer_id
     * @param int $spentPoints
     * @return bool
     */
    public function spendPoints($customer_id, $spentPoints);
}