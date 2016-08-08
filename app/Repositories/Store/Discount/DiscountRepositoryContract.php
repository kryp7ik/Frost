<?php
/**
 * Created by PhpStorm.
 * User: kryptik
 * Date: 8/7/16
 * Time: 10:45 AM
 */
namespace App\Repositories\Store\Discount;

interface DiscountRepositoryContract
{
    public function getAll();

    public function findById($id);

    public function create($data);

    public function update($id, $data);
}