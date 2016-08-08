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
    public function getAll();

    public function findById($id);

    public function findByPhone($phone);

    public function create($data);

    public function updateField($id, $fieldname, $value);
}