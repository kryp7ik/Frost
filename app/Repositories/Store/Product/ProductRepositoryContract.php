<?php
/**
 * Created by PhpStorm.
 * User: kryptik
 * Date: 8/7/16
 * Time: 11:13 AM
 */
namespace App\Repositories\Store\Product;

interface ProductRepositoryContract
{
    public function getAll();

    public function findById($id);

    public function create($data);

    public function update($id, $data);

    public function updateField($id, $fieldName, $value);
}