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

    /**
     * @param bool $sort
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll($sort = false);

    /**
     * @param int $points Amount of reward points available
     * @return array
     */
    public function getRedeemable($points);

    /**
     * @param int $id
     * @return mixed
     */
    public function findById($id);

    /**
     * @param array $data
     */
    public function create($data);

    /**
     * @param int $id
     * @param array $data
     */
    public function update($id, $data);
}