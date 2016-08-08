<?php
/**
 * Created by PhpStorm.
 * User: kryptik
 * Date: 8/7/16
 * Time: 1:33 PM
 */
namespace App\Repositories\Store\LiquidProduct;

use App\Models\Store\LiquidProduct;

interface LiquidProductRepositoryContract
{
    public function getAll();

    /**
     * @param int $id
     * @return LiquidProduct|boolean
     */
    public function findById($id);

    /**
     * @param int $shop_order_id
     * @param array $data
     * @return bool
     */
    public function create($shop_order_id, $data);

    /**
     * Accepts an array of multiple LiquidProducts
     * @param int $shop_order_id
     * @param $data
     */
    public function createMultiple($shop_order_id, $data);

    /**
     * @param int $id
     * @param string $field_name
     * @param mixed $value
     * @return mixed
     */
    public function updateField($id, $field_name, $value);

    /**
     * @param int $id
     */
    public function delete($id);
}